<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Bookmark;
use App\Models\AudioBook;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;
use App\Mail\UserAlreadyRegistered;
use App\Http\Controllers\Controller;
use App\Services\Proxies\OAuthProxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Responsables\V1\UserResponse;
use App\Responsables\V1\ModelResponse;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Api\V1\Users\TokenRequest;
use App\Http\Requests\Api\V1\Users\RatingRequest;
use App\Http\Requests\Api\V1\Users\UpdateRequest;
use App\Http\Requests\Api\V1\Users\PasswordRequest;
use App\Http\Requests\Api\V1\Users\RegisterRequest;

class UserController extends Controller
{
    /**
     * Get user detail
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Responsables\V1\UserResponse
     */
    public function show (Request $request)
    {
        return new ModelResponse($request->user(), false);
    }

    /**
     * Create a new user.
     *
     * @param  \App\Http\Requests\Api\V1\Users\RegisterRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        $existing_user = User::where('email', $request->email)->first();
        if($existing_user) {
            if($existing_user->socialAccounts()->count() >= 1) {
                $provider = $existing_user->socialAccounts()->first()->provider_name;
                $existing_user->sendAlreadySocialRegisteredNotification($provider);
                return response()->json(['message' => 'Nous vous avons envoyé un email pour confirmer votre inscription'], 200);
            }
            $existing_user->sendAlreadyRegisteredNotification();
            return response()->json(['message' => 'Nous vous avons envoyé un email pour confirmer votre inscription'], 200);
        }
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'terms_accepted_at' => now(),
            'push_settings' => json_encode(['general' => $request->notification])
        ]);

        $user->claimInstallationId($request->attributes->get('installation_id'));
        
        event(new Registered($user));

        return response()->json(['message' => 'Nous vous avons envoyé un email pour confirmer votre inscription'], 200);
    }

    /**
     * Update a user.
     *
     * @param \App\Http\Requests\Api\V1\Users\UpdateRequest $request
     *
     * @return \App\Responsable\V1\User\UserResponse
     */
    public function update(UpdateRequest $request)
    {
        $user = $request->user();

        if(isset($request->email)){
            if($user->email == $request->email) {
                $same_email = true;
            } else {
                $user->email_verified_at = null;
                $same_email = false;
            }
        }

        if(isset($request->notification)){
            $user->push_settings = json_encode(['general' => $request->notification]);
        }
        
        $user->update($request->validated());

        if(isset($request->email) && $same_email == false){
             $user->sendEmailVerificationNotification();
        }

        return new ModelResponse($user, false);
    }

    /**
     * Update password of a user.
     *
     * @param \App\Http\Requests\Api\V1\Users\PasswordRequest $request
     *
     * @return \App\Responsable\V1\User\UserResponse
     */
    public function updatePassword(PasswordRequest $request)
    {
        $user = $request->user();
        
        $user->update($request->validated());

        return new ModelResponse($user, false);
    }

    /**
     * Delete a user
     *
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->username = null;
        $user->email = null;
        $user->push_settings = null;
        $user->terms_accepted_at = null;
        $user->save();
        $user->socialAccounts()->delete();
        $user->subscriptions()->delete();
        $user->delete();
    }

    /**
     * Retrieve audio books that belongs to the current user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function ownedAudioBooks(Request $request)
    {
        $audioBooks = $request->user()->audioBooks()->orderBy('pivot_created_at', 'desc');

        if($audioBooks->count() >= 1) {
            return new ModelResponse($audioBooks, true);
        } else {
            return response(null, 204);
        }
    }

    /**
     * Retrieve audio books that belongs to the current user and that are not archived.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function libraryAudioBooks(Request $request)
    {
        if($request->has('type')){
            $type = $request->type;
        } else {
            $type = 'all';
        }

        if($request->has('order_by')){
            $orderBy = $request->order_by;
        } else {
            $orderBy = 'most_recent';
        }

        $audioBooks = $request->user()->audioBooks()->whereNull('archived_at')->libraryFilters($type, $orderBy);
        if($audioBooks->count() >= 1) {
            return new ModelResponse($audioBooks, true);
        } else {
            return response(null, 204);
        }
    }

    /**
     * Retrieve audio books where user posted a bookmark on it.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function ownedAudioBooksBookmarked(Request $request)
    {
        $audioBooksId = $request->user()->bookmarks()->groupBy('audio_book_id')->pluck('audio_book_id')->toArray();

        $audioBooks = AudioBook::whereIn('id', $audioBooksId);

        if($audioBooks->count() >= 1) {
            return new ModelResponse($audioBooks, true);
        } else {
            return response(null, 204);
        }
    }

    /**
     * Retrieve audio books where user posted a bookmark on it.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function bookmarksFromOwnedAudioBook(Request $request, AudioBook $audioBook)
    {
        $bookmarksId = $request->user()->bookmarks()->where('audio_book_id', $audioBook->id)->get()->pluck('pivot.id');

        $bookmarks = Bookmark::whereIn('id', $bookmarksId);

        if($bookmarks->count() >= 1) {
            return new ModelResponse($bookmarks, true);
        } else {
            return response(null, 204);
        }
    }

    /**
     * Retrieve bookmarks that belongs to the current user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function ownedBookmarks(Request $request)
    {
        $bookmarksId = $request->user()->bookmarks()->get()->pluck('pivot.id');

        $bookmarks = Bookmark::whereIn('id', $bookmarksId);

        if($bookmarks->count() >= 1) {
            return new ModelResponse($bookmarks, true);
        } else {
            return response(null, 204);
        }
    }

    /**
     * Retrieve credit count of the current user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function credits(Request $request)
    {
        $platform = $request->header('X-MOBILE-OS');
        $user = $request->user();
        $credit_count = $user->getCreditsCount();

        $subscribed = $user->isSubscribed();

        $expiration_count = $user->credits()->whereNull('used_at')->where('expire_at', '>', Carbon::now())->where('expire_at', '<', Carbon::now()->addDays(30))->where('available_at', '<', Carbon::now())->count();

        $expiration_comment = null;

        if ($expiration_count == 1) {
            $expiration_comment = "1 crédit expire sous 30 jours ! N’oubliez pas de l’utiliser.";
        } elseif ($expiration_count > 1) {
            $expiration_comment = $expiration_count." crédits expirent sous 30 jours ! N'oubliez pas de les utiliser.";
        }

        if ($subscribed) {
            $subscription = $user->activeSubscription();
            if ($platform == 'android') {
                if ($platform == $subscription->platform) {
                    $subscription_comment = "Votre abonnement et la facturation sont gérés par votre compte Play Store";
                    $can_manage_subscription = true;
                } else {
                    $subscription_comment = "Votre abonnement et la facturation sont gérés par une autre plateforme que celle du Play Store";
                    $can_manage_subscription = false;
                }
            } elseif ($platform == 'ios') {
                if ($platform == $subscription->platform) {
                    $subscription_comment = "Votre abonnement et la facturation sont gérés par votre compte App Store.";
                    $can_manage_subscription = true;
                } else {
                    $subscription_comment = "Votre abonnement et la facturation sont gérés par une autre plateforme que celle de l’App Store";
                    $can_manage_subscription = false;
                }
            }

            return response()->json([
                'data' => [
                    'available_credits' => $credit_count,
                    'expiration_comment' => $expiration_comment,
                    'subscription_comment' => $subscription_comment,
                    'can_manage_subscription' => $can_manage_subscription,
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'available_credits' => $credit_count,
                    'expiration_comment' => $expiration_comment,
                    'subscription_comment' => null,
                    'can_manage_subscription' => false,
                ]
            ], 200);
        }

    }

    /**
     * User interacted with the rating section of the home page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function rating(Request $request)
    {
        $user = $request->user();

        if($user->display_rating_when == User::FIRST_FREQUENCY && $user->home_count >= User::FIRST_FREQUENCY) {
            $user->display_rating_when = User::SECOND_FREQUENCY;
        } elseif($user->display_rating_when == User::SECOND_FREQUENCY && $user->home_count >= User::SECOND_FREQUENCY) {
            $user->display_rating_when = User::THIRD_FREQUENCY;
        } elseif($user->display_rating_when == User::THIRD_FREQUENCY && $user->home_count >= User::THIRD_FREQUENCY) {
            $user->display_rating_when = User::FOURTH_FREQUENCY;
        } elseif($user->display_rating_when == User::FOURTH_FREQUENCY && $user->home_count >= User::FOURTH_FREQUENCY) {
            $user->display_rating_when = User::FIFTH_FREQUENCY;
        }
        
        $user->save();
    }

    /**
     * User Accepted the free subscription section of the home page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function freeSubscription(Request $request)
    {
        $user = $request->user();

        $user->accepted_free_subscription = true;

        $user->interacted_free_subscription = true;

        $user->save();
    }

    /**
     * User Declined the free subscription section of the home page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function freeSubscriptionDeclined(Request $request)
    {
        $user = $request->user();

        $user->accepted_free_subscription = false;

        $user->interacted_free_subscription = true;

        $user->save();
    }
}
