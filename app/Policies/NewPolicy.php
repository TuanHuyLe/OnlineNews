<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->checkPermissionAccess(config('permission.view_new'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->checkPermissionAccess(config('permission.add_new'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permission.edit_new'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permission.delete_new'));
    }
}
