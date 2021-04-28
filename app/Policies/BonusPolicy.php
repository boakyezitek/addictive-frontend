<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bonus;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\NovaPermissions\NovaPermissionPolicy;

class BonusPolicy extends NovaPermissionPolicy
{
	use HandlesAuthorization;
    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'bonuses';

    /**
     * Determine whether the user cansee the detail of a bonus.
     *
     * @param App\Models\User $user
     * @param App\Models\Bonus $bonus
     * @return mixed
     */
    public function show(User $user, Bonus $bonus)
    {
        return $user->audioBooks()->where('audio_book_id', $bonus->audio_book_id)->count() === 1;
    }

    /**
     * Determine whether the user can access the audio of a bonus.
     *
     * @param App\Models\User $user
     * @param App\Models\Bonus $bonus
     * @return mixed
     */
    public function audio(User $user, Bonus $bonus)
    {
        return $user->audioBooks()->where('audio_book_id', $bonus->audio_book_id)->count() === 1;
    }

    /**
     * Determine whether the user can access the video of a bonus.
     *
     * @param App\Models\User $user
     * @param App\Models\Bonus $bonus
     * @return mixed
     */
    public function video(User $user, Bonus $bonus)
    {
        return $user->audioBooks()->where('audio_book_id', $bonus->audio_book_id)->count() === 1;
    }
}
