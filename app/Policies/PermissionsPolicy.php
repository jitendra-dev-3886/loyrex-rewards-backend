<?php

namespace App\Policies;

use App\User;
use App\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any permissions.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the permissions.
     *
     * @param  App\User  $user
     * @param  App\Permissions  $permissions
     * @return bool
     */
    public function view(User $user, Permission $permissions)
    {
        return false;
    }

    /**
     * Determine whether the user can create a permissions.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the permissions.
     *
     * @param  App\User  $user
     * @param  App\Permissions  $permissions
     * @return bool
     */
    public function update(User $user, Permission $permissions)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the permissions.
     *
     * @param  App\User  $user
     * @param  App\Permissions  $permissions
     * @return bool
     */
    public function delete(User $user, Permission $permissions)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the permissions.
     *
     * @param  App\User  $user
     * @param  App\Permissions  $permissions
     * @return bool
     */
    public function restore(User $user, Permission $permissions)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the permissions.
     *
     * @param  App\User  $user
     * @param  App\Permissions  $permissions
     * @return bool
     */
    public function forceDelete(User $user, Permission $permissions)
    {
        return false;
    }
}
