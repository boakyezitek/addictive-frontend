<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AudioBook;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\NovaPermissions\NovaPermissionPolicy;

class AudioBookPolicy extends NovaPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'audio_books';


    /**
     * Determine whether the user can mask/unmask an audiobook
     *
     * @param App\Models\User $user
     * @param App\Models\AudioBook $audiobook
     * @return mixed
     */
    public function mask(User $user, AudioBook $audiobook)
    {
        return $user->audioBooks()->where('audio_book_id', $audiobook->id )->count() === 1;
    }

    /**
     * Determine whether the user can markAsRead/markAsUnread an audiobook
     *
     * @param App\Models\User $user
     * @param App\Models\AudioBook $audiobook
     * @return mixed
     */
    public function mark(User $user, AudioBook $audiobook)
    {
        return $user->audioBooks()->where('audio_book_id', $audiobook->id )->count() === 1;
    }

    /**
     * Determine whether the user can purchase an audiobook
     *
     * @param App\Models\User $user
     * @param App\Models\AudioBook $audiobook
     * @return mixed
     */
    public function purchase(User $user, AudioBook $audiobook)
    {

        return $user->getCreditsCount() >= 1 && $user->audioBooks()->where('audio_book_id', $audiobook->id )->count() === 0;
    }
}
