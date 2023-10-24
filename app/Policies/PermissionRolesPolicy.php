<?php

namespace App\Policies;

use App\User;
use App\PermissionRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionRolesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any permissionRoles.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the permissionRoles.
     *
     * @param  App\User  $user
     * @param  App\PermissionRoles  $permissionRoles
     * @return bool
     */
    public function view(User $user, PermissionRole $permissionRoles)
    {
        return false;
    }

    /**
     * Determine whether the user can create a permissionRoles.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the permissionRoles.
     *
     * @param  App\User  $user
     * @param  App\PermissionRoles  $permissionRoles
     * @return bool
     */
    public function update(User $user, PermissionRole $permissionRoles)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the permissionRoles.
     *
     * @param  App\User  $user
     * @param  App\PermissionRoles  $permissionRoles
     * @return bool
     */
    public function delete(User $user, PermissionRole $permissionRoles)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the permissionRoles.
     *
     * @param  App\User  $user
     * @param  App\PermissionRoles  $permissionRoles
     * @return bool
     */
    public function restore(User $user, PermissionRole $permissionRoles)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the permissionRoles.
     *
     * @param  App\User  $user
     * @param  App\PermissionRoles  $permissionRoles
     * @return bool
     */
    public function forceDelete(User $user, PermissionRole $permissionRoles)
    {
        return false;
    }
}
