<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chapter;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\NovaPermissions\NovaPermissionPolicy;

class ChapterPolicy extends NovaPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'chapters';


    /**
     * Determine whether the user can add a bookmark to the chapter.
     *
     * @param App\Models\User $user
     * @param App\Models\Chapter $chapter
     * @return mixed
     */
    public function bookmark(User $user, Chapter $chapter)
    {
        return $user->audioBooks()->where('audio_book_id', $chapter->audio_book_id)->count() === 1;
    }

    /**
     * Determine whether the user can download a chapter audio file.
     *
     * @param App\Models\User $user
     * @param App\Models\Chapter $chapter
     * @return mixed
     */
    public function download(User $user, Chapter $chapter)
    {
        return $user->audioBooks()->where('audio_book_id', $chapter->audio_book_id)->count() === 1;
    }

    /**
     * Determine whether the user can update his progression on a chapter.
     *
     * @param App\Models\User $user
     * @param App\Models\Chapter $chapter
     * @return mixed
     */
    public function progress(User $user, Chapter $chapter)
    {
        return $user->audioBooks()->where('audio_book_id', $chapter->audio_book_id)->count() === 1;
    }
}
