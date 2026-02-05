<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function view(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function create(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function update(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function delete(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function restore(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function forceDelete(AuthUser $authUser): bool
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

    public function replicate(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasRole') ? ($authUser->hasRole('super_admin') || ($authUser->role ?? null) === 'admin') : (($authUser->role ?? null) === 'admin');
    }

}
