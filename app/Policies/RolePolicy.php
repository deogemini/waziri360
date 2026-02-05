<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function view(AuthUser $authUser, Role $role): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function create(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function update(AuthUser $authUser, Role $role): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function delete(AuthUser $authUser, Role $role): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function restore(AuthUser $authUser, Role $role): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function forceDelete(AuthUser $authUser, Role $role): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function replicate(AuthUser $authUser, Role $role): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

}
