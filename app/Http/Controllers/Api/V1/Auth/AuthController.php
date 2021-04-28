<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Carbon\Carbon;
use Google_Client;
use App\Models\User;
use App\Models\Installation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Proxies\OAuthProxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Responsables\V1\UserResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\Api\V1\Auth\EmailRequest;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Notifications\Emails\RegisterNotification;
use App\Http\Requests\Api\V1\Auth\SocialLoginRequest;
use App\Http\Requests\Api\V1\Auth\RefreshTokenRequest;

class AuthController extends Controller
{
    /**
     * Authenticate a user with email/password.
     *
     * @param  \App\Http\Requests\Api\V1\Auth\LoginRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        
        $oauth_proxy_response = OAuthProxy::redirectToPasswordGrant(
            $request,
            optional($user)->email ?? '',
            $request->password ?? ''
        );

        $status = $oauth_proxy_response->status();
        if ($status === 200) {
            if($user->email_verified_at !== null) {
                $response_content = json_decode($oauth_proxy_response->content(), false, 512, JSON_THROW_ON_ERROR);
                $user->claimInstallationId($request->attributes->get('installation_id'));
            } elseif ($user->email_verification_sent_at < Carbon::now()->subDays(2)) {
                $user->sendEmailVerificationNotification();
                return response()->json(['error' => 'error_login', 'message' => trans('auth.errors.unverified_email')], 403);
            } else {
                return response()->json(['error' => 'error_login', 'message' => trans('auth.errors.unverified_email')], 403);
            }
        } elseif ($status == 400) {
            return response()->json(['error' => 'error_login', 'message' => trans('auth.errors.unverified_email')], 403);
        }

        return $oauth_proxy_response;
        
    }

    /**
     * Refresh an access token
     *
     * @param  RefreshRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function refreshToken(RefreshTokenRequest $request)
    {
        return OAuthProxy::redirectToRefreshToken($request, $request->refresh_token);
    }

    /**
     * Authenticate a user from a provider token.
     *
     * @param  App\Http\Requests\Api\V1\Users\SocialLogin  $request
     * @param  string $provider
     *
     * @return Illuminate\Http\Response
     */
    public function socialLogin(SocialLoginRequest $request, $provider)
    {
        if($provider == 'google'){
            $client = new Google_Client(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($request->token);
            if($payload){
                $userId = $payload['sub'];

                $user = User::whereHas('socialAccounts', function ($query) use ($userId) {
                        $query->where('provider_name', 'google')
                        ->where('provider_id', $userId);
                })->first();
                if(!$user){
                    $user = User::where('email', $payload['email'])->first();
                    if(!$user){
                        $user = User::create([
                            'username' => $payload['name'],
                            'email' => $payload['email'],
                            'terms_accepted_at' => now(),
                            'push_settings' => json_encode(['general' => false])
                        ]);
                        $user->sendRegisterNotification();
                        $user->markEmailAsVerified();  
                    }
                    $user->registerWithSocialAccount($provider, $userId);
                }

                $token = $provider.':'.$request->token;
                $oauth_proxy_response = OAuthProxy::redirectToPasswordGrant(
                    $request,
                    $provider.':'.$userId,
                    $token
                );

                $status = $oauth_proxy_response->status();
                if ($status === 200) {
                    $response_content = json_decode($oauth_proxy_response->content(), false, 512, JSON_THROW_ON_ERROR);
                    $user->claimInstallationId($request->attributes->get('installation_id'));
                }

                return $oauth_proxy_response;
            }
        } else {
            $user = User::withProviderToken($provider, $request->token)->first();
            $social_user = Socialite::driver($provider)->userFromToken($request->token);
            if(!$user){
                $user = User::where('email', $social_user->email)->first();

                if(!$user){
                    $name = $request->username ?? 'Default';
                    $user = User::create([
                        'username' => $social_user->name ?? $name,
                        'email' => $social_user->email,
                        'terms_accepted_at' => now(),
                        'push_settings' => json_encode(['general' => false])
                    ]);

                    $user->sendRegisterNotification();
                    $user->markEmailAsVerified();
                } 
                $user->registerWithSocialAccount($provider, $request->token);
            }
            $token = $provider.':'.$request->token;
            $oauth_proxy_response = OAuthProxy::redirectToPasswordGrant(
                $request,
                $provider.':'.$social_user->getId(),
                $token
            );

            $status = $oauth_proxy_response->status();
            if ($status === 200) {
                $response_content = json_decode($oauth_proxy_response->content(), false, 512, JSON_THROW_ON_ERROR);
                $user->claimInstallationId($request->attributes->get('installation_id'));
            }

            return $oauth_proxy_response;
        }
    }

    /**
     * Logout the user
     *
     * @return void
     */
    public function logout()
    {
        $installation = Installation::whereUuid(request()->attributes->get('installation_id'))->firstOrFail();
        $installation->user()->dissociate();
        $installation->save();

        Auth::user()->token()->revoke();
    }

    /**
     * Check if the given Email exist in the database.
     *
     * @param  \App\Http\Requests\Api\V1\Auth\EmailRequest $request
     *
     * @return App\Responsables\V1\UserResponse
     */
    public function emailVerify(EmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user){
            if($user->socialAccounts->count() > 0){
                return response()->json([
                    'data' => [
                        'message' => 'Ce compte a été créé via '.$user->socialAccounts->first()->provider_name,
                    ]
                ], 409);
            }
            return response()->json([
                'data' => [
                    'message' => 'OK',
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'NO CONTENT',
                ]
            ], 204);
        }
    }
}
