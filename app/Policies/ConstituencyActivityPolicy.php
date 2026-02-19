<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ConstituencyActivity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstituencyActivityPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ConstituencyActivity');
    }

    public function view(AuthUser $authUser, ConstituencyActivity $constituencyActivity): bool
    {
        return $authUser->can('View:ConstituencyActivity');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ConstituencyActivity');
    }

    public function update(AuthUser $authUser, ConstituencyActivity $constituencyActivity): bool
    {
        return $authUser->can('Update:ConstituencyActivity');
    }

    public function delete(AuthUser $authUser, ConstituencyActivity $constituencyActivity): bool
    {
        return $authUser->can('Delete:ConstituencyActivity');
    }

    public function restore(AuthUser $authUser, ConstituencyActivity $constituencyActivity): bool
    {
        return $authUser->can('Restore:ConstituencyActivity');
    }

    public function forceDelete(AuthUser $authUser, ConstituencyActivity $constituencyActivity): bool
    {
        return $authUser->can('ForceDelete:ConstituencyActivity');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ConstituencyActivity');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ConstituencyActivity');
    }

    public function replicate(AuthUser $authUser, ConstituencyActivity $constituencyActivity): bool
    {
        return $authUser->can('Replicate:ConstituencyActivity');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ConstituencyActivity');
    }

}