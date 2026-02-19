<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MinisterProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class MinisterProfilePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MinisterProfile');
    }

    public function view(AuthUser $authUser, MinisterProfile $ministerProfile): bool
    {
        return $authUser->can('View:MinisterProfile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MinisterProfile');
    }

    public function update(AuthUser $authUser, MinisterProfile $ministerProfile): bool
    {
        return $authUser->can('Update:MinisterProfile');
    }

    public function delete(AuthUser $authUser, MinisterProfile $ministerProfile): bool
    {
        return $authUser->can('Delete:MinisterProfile');
    }

    public function restore(AuthUser $authUser, MinisterProfile $ministerProfile): bool
    {
        return $authUser->can('Restore:MinisterProfile');
    }

    public function forceDelete(AuthUser $authUser, MinisterProfile $ministerProfile): bool
    {
        return $authUser->can('ForceDelete:MinisterProfile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MinisterProfile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MinisterProfile');
    }

    public function replicate(AuthUser $authUser, MinisterProfile $ministerProfile): bool
    {
        return $authUser->can('Replicate:MinisterProfile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MinisterProfile');
    }

}