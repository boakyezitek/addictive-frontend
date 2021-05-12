<?php

namespace App\Models;

use Carbon\Carbon;
use Google_Client;
use App\Models\AudioBook;
use Illuminate\Support\Str;
use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Laravel\Passport\HasApiTokens;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Hash;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use App\Transformers\V1\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Notifications\Emails\VerifyApiEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\Emails\RegisterNotification;
use App\Notifications\Emails\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Emails\AlreadyRegisteredNotification;
use App\Notifications\Emails\AlreadySocialRegisteredNotification;

class User extends Authenticatable implements Transformable, HasMedia, MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens, InteractsWithMedia, ManageMedia, Eventable;

    /** @var integer */
    public const FIRST_FREQUENCY = 10;

    /** @var integer */
    public const SECOND_FREQUENCY = 30;

    /** @var integer */
    public const THIRD_FREQUENCY = 60;

    /** @var integer */
    public const FOURTH_FREQUENCY = 100;

    /** @var integer */
    public const FIFTH_FREQUENCY = 150;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'terms_accepted_at', 'push_settings', 'email_verified_at', 'is_listening', 'display_rating_when', 'email_verification_sent_at', 'accepted_free_subscription', 'intro_subscription_used', 'interacted_free_subscription',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'terms_accepted_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'email_verification_sent_at' => 'datetime',
        'intro_subscription_used' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            $user->hash_id = Hashids::encode($user->id);
            $user->save();
        });
    }

    /**
     * Define a transformer to be used by the response
     *
     * @return App\Transformers\V1\UserTransformer
     */

    public static function transformer() : TransformerAbstract
    {
        return new UserTransformer();
    }

    /**
     * Scope a query to return users associated to the token.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $provider
     * @param  string $token
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithProviderToken($query, $provider, $token)
    {
        try {
            $provider_id = Socialite::driver($provider)->userFromToken($token)->getId();
        } catch (ClientException $exception) {
            $response = (string) $exception->getResponse()->getBody();
            $json = json_decode($response);

            abort(400, $json->error->message ?? 'An undefined client error has been returned from your provider.');
        } catch (RequestException $exception) {
            abort(502, 'Error on OAuth server of your provider.');
        }

        return $query->whereHas('socialAccounts', function ($query) use ($provider, $provider_id) {
            $query->where('provider_name', $provider)
                ->where('provider_id', $provider_id);
        });
    }

    /**
     * Use this function when a new user is registering with a social account.
     *
     * @param string $provider_name
     * @param string $token
     *
     * @return SocialAccount
     */
    public function registerWithSocialAccount($provider_name, $token)
    {
        $social_account = new SocialAccount;

        $social_account->register($provider_name, $token);
        $social_account->user()->associate($this);

        $social_account->save();

        return $social_account;
    }

    /**
     * Get the social account connected to the user.
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class, 'user_items')->withPivot('archived_at', 'status')->withTimestamps();
    }

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_user')->withPivot('time_elapsed')->withTimestamps();
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Chapter::class, 'bookmarks')->withPivot('id', 'name', 'from', 'to', 'synchronized_at', 'internal_updated_at', 'timestamp_reference', 'deleted_at')->withTimestamps();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function creditPurchases()
    {
        return $this->hasMany(CreditPurchase::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * Get count of credits
     *
     * @return integer
     */
    public function getCreditsCount()
    {
        return $this->credits()->whereNull('used_at')->where('expire_at', '>', Carbon::now())->where('available_at', '<', Carbon::now())->count();
    }

    /**
     * Get the credit that have the closest expiration date
     *
     * @return App\Models\Credit $credit
     */
    public function getExpiringCredit()
    {
        return $this->credits()->whereNull('used_at')->where('expire_at', '>', Carbon::now())->where('available_at', '<', Carbon::now())->orderBy('expire_at')->first();
    }

    public function events()
    {
        return $this->morphMany(Event::class, 'owner');
    }

    // public function credits()
    // {
    //     // TODO: filter by status
    //     $creditFromCreditPurchases = $this->creditPurchases()->sum(function($transaction) {
    //         // TODO : get relation and create attribute credits $transaction->transactionable->credits;
    //         return 0;
    //     });

    //     // TODO: filter by status
    //     $creditFromSubscriptions = $this->subscriptions()->where('created_at', '>=', Carbon::now()->subMonths(3))->get()->sum(function($transaction) {
    //         // TODO : get relation and create attribute credits $transaction->transactionable->credits;
    //         return 0;
    //     });
    //     $creditFromOrders = $this->orders->sum(function($order) {
    //         return $order->status == Order::STATUS_SUCCESS ? $order->audioBook->price : 0;
    //     });

    //     return $creditFromCreditPurchases + $creditFromSubscriptions - $creditFromOrders;
    // }

    /**
     * Determine if the user possesses the given book
     * @param App/Models/AudioBook $audiobook
     *
     * @return bool
     */
    public function possessesAudioBook(AudioBook $audiobook)
    {
        return $this->audioBooks()->where('audio_book_id', $audiobook->id )->count() === 1;
    }

    /**
     * Reset the progression for a given audiobook
     * @param App/Models/AudioBook $audiobook
     *
     * @return void
     */
    public function resetAudioBook(AudioBook $audiobook)
    {
        $chapters = Chapter::where('audio_book_id', $audiobook->id)->get();
        foreach ($chapters as $chapter) {
            $pivot = $chapter->users()->where('user_id', $this->id)->first();
            if($pivot) {
                if($pivot->pivot->time_elapsed > 0)
                $chapter->users()->updateExistingPivot($this->id, ['time_elapsed' => 0]);
            } else {
                $chapter->users()->save($this, ['time_elapsed' => 0]);
            }
        }
    }

    /**
     * Complete the progression for a given audiobook
     * @param App/Models/AudioBook $audiobook
     *
     * @return void
     */
    public function completeAudioBook(AudioBook $audiobook)
    {
        $chapters = Chapter::where('audio_book_id', $audiobook->id)->get();
        foreach ($chapters as $chapter) {
            $pivot = $chapter->users()->where('user_id', $this->id)->first();
            if($pivot) {
                if($pivot->pivot->time_elapsed < $chapter->duration)
                $chapter->users()->updateExistingPivot($this->id, ['time_elapsed' => $chapter->duration]);
            } else {
                $chapter->users()->save($this, ['time_elapsed' => $chapter->duration]);
            }
        }
    }

    /**
     * Determine if user is subscribed
     *
     * @return boolean
     */
    public function isSubscribed()
    {
        return $this->subscriptions()->whereNull('cancelled_at')->where('expiration_at', '>', Carbon::now())->where('status', Subscription::STATUS_IN_PROGRESS)->first() ? true : false;
    }

        /**
     * Return the active subscription
     *
     * @return App/Models/Subscription
     */
    public function activeSubscription()
    {
        return $this->subscriptions()->whereNull('cancelled_at')->where('expiration_at', '>', Carbon::now())->where('status', Subscription::STATUS_IN_PROGRESS)->first();
    }

    public function installations()
    {
        return $this->hasMany(Installation::class);
    }

    public function claimInstallationId(string $installation_id)
    {
        $installation = Installation::whereUuid($installation_id)->first();

        if ($installation)
            $this->installations()->save($installation);
    }


    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Set the user's password.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        if (Str::startsWith($password, 'facebook') || Str::startsWith($password, 'apple')) {
            [$provider_name, $token] = explode(':', $password);
            $social_user = Socialite::driver($provider_name)->userFromToken($token);

            $provider = $this->socialAccounts()->where('provider_name', $provider_name)->first();
            if ($provider === null) {
                return false;
            }

            return $social_user->getId() === $provider->provider_id;
        }elseif(Str::startsWith($password, 'google')){
            [$provider_name, $token] = explode(':', $password);

            $client = new Google_Client(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($token);

            $provider = $this->socialAccounts()->where('provider_name', $provider_name)->first();
            if ($provider === null) {
                return false;
            }
            return $payload['sub'] === $provider->provider_id;
        }
        return Hash::check($password, $this->password);
    }

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\Models\User
     */
    public function findForPassport($username)
    {
        if (Str::startsWith($username, 'facebook') || Str::startsWith($username, 'google') || Str::startsWith($username, 'apple')) {

            [$provider_name, $provider_id] = explode(':', $username);

            return User::whereHas('socialAccounts', function (Builder $query) use ($provider_name, $provider_id) {
                $query->where('provider_name', $provider_name)->where('provider_id', $provider_id);
            })->first();
        }

        return $this->where('email', $username)->first();
    }

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/avatars')
            ->useDisk('gcs')
            ->singleFile();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->email_verification_sent_at = Carbon::now();
        $this->notify(new VerifyApiEmail);
        $this->save();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the register notification.
     *
     * @return void
     */
    public function sendRegisterNotification()
    {
        $this->notify(new RegisterNotification);
    }

    /**
     * Send the already registered notification.
     *
     * @return void
     */
    public function sendAlreadyRegisteredNotification()
    {
        $this->notify(new AlreadyRegisteredNotification);
    }

    /**
     * Send the already social registered notification.
     *
     * @return void
     */
    public function sendAlreadySocialRegisteredNotification($provider)
    {
        $this->notify(new AlreadySocialRegisteredNotification($provider));
    }
}
