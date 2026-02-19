<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ConstituencyProject;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstituencyProjectPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ConstituencyProject');
    }

    public function view(AuthUser $authUser, ConstituencyProject $constituencyProject): bool
    {
        return $authUser->can('View:ConstituencyProject');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ConstituencyProject');
    }

    public function update(AuthUser $authUser, ConstituencyProject $constituencyProject): bool
    {
        return $authUser->can('Update:ConstituencyProject');
    }

    public function delete(AuthUser $authUser, ConstituencyProject $constituencyProject): bool
    {
        return $authUser->can('Delete:ConstituencyProject');
    }

    public function restore(AuthUser $authUser, ConstituencyProject $constituencyProject): bool
    {
        return $authUser->can('Restore:ConstituencyProject');
    }

    public function forceDelete(AuthUser $authUser, ConstituencyProject $constituencyProject): bool
    {
        return $authUser->can('ForceDelete:ConstituencyProject');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ConstituencyProject');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ConstituencyProject');
    }

    public function replicate(AuthUser $authUser, ConstituencyProject $constituencyProject): bool
    {
        return $authUser->can('Replicate:ConstituencyProject');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ConstituencyProject');
    }

}