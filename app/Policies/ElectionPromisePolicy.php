<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ElectionPromise;
use Illuminate\Auth\Access\HandlesAuthorization;

class ElectionPromisePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ElectionPromise');
    }

    public function view(AuthUser $authUser, ElectionPromise $electionPromise): bool
    {
        return $authUser->can('View:ElectionPromise');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ElectionPromise');
    }

    public function update(AuthUser $authUser, ElectionPromise $electionPromise): bool
    {
        return $authUser->can('Update:ElectionPromise');
    }

    public function delete(AuthUser $authUser, ElectionPromise $electionPromise): bool
    {
        return $authUser->can('Delete:ElectionPromise');
    }

    public function restore(AuthUser $authUser, ElectionPromise $electionPromise): bool
    {
        return $authUser->can('Restore:ElectionPromise');
    }

    public function forceDelete(AuthUser $authUser, ElectionPromise $electionPromise): bool
    {
        return $authUser->can('ForceDelete:ElectionPromise');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ElectionPromise');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ElectionPromise');
    }

    public function replicate(AuthUser $authUser, ElectionPromise $electionPromise): bool
    {
        return $authUser->can('Replicate:ElectionPromise');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ElectionPromise');
    }

}