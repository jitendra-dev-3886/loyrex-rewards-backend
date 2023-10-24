<?php

namespace App\Policies;

use App\User;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any roles.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the roles.
     *
     * @param  App\User  $user
     * @param  App\Roles  $roles
     * @return bool
     */
    public function view(User $user, Role $roles)
    {
        return false;
    }

    /**
     * Determine whether the user can create a roles.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the roles.
     *
     * @param  App\User  $user
     * @param  App\Roles  $roles
     * @return bool
     */
    public function update(User $user, Role $roles)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the roles.
     *
     * @param  App\User  $user
     * @param  App\Roles  $roles
     * @return bool
     */
    public function delete(User $user, Role $roles)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the roles.
     *
     * @param  App\User  $user
     * @param  App\Roles  $roles
     * @return bool
     */
    public function restore(User $user, Role $roles)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the roles.
     *
     * @param  App\User  $user
     * @param  App\Roles  $roles
     * @return bool
     */
    public function forceDelete(User $user, Role $roles)
    {
        return false;
    }
}
