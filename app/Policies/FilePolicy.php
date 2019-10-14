<?php

namespace App\Policies;

use App\User;
use App\File;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine whether the user can view file
     *
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function view(User $user, File $file)
    {
        return $file->user_id == $user->id;
    }

    /**
     * Determine whether the user can create file
     *
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function create(User $user, File $file)
    {
        return $file->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete file
     *
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function delete(User $user, File $file)
    {
        return $file->user_id == $user->id;
    }
}
