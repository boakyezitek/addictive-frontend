<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chapter;
use App\Models\Bookmark;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\NovaPermissions\NovaPermissionPolicy;

class BookmarkPolicy extends NovaPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'bookmarks';

    /**
     * Determine whether the user can delete the bookmark.
     *
     * @param App\Models\User $user
     * @param App\Models\Bookmark $bookmark
     * @return mixed
     */
    public function destroy(User $user, Bookmark $bookmark)
    {
        return $user->id === $bookmark->user_id;
    }

    /**
     * Determine whether the user can update the bookmark.
     *
     * @param App\Models\User $user
     * @param App\Models\Bookmark $bookmark
     * @return mixed
     */
    public function edit(User $user, Bookmark $bookmark)
    {
        return $user->id === $bookmark->user_id;
    }
}
