<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Deliverable;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliverablePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Deliverable');
    }

    public function view(AuthUser $authUser, Deliverable $deliverable): bool
    {
        return $authUser->can('View:Deliverable');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Deliverable');
    }

    public function update(AuthUser $authUser, Deliverable $deliverable): bool
    {
        return $authUser->can('Update:Deliverable');
    }

    public function delete(AuthUser $authUser, Deliverable $deliverable): bool
    {
        return $authUser->can('Delete:Deliverable');
    }

    public function restore(AuthUser $authUser, Deliverable $deliverable): bool
    {
        return $authUser->can('Restore:Deliverable');
    }

    public function forceDelete(AuthUser $authUser, Deliverable $deliverable): bool
    {
        return $authUser->can('ForceDelete:Deliverable');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Deliverable');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Deliverable');
    }

    public function replicate(AuthUser $authUser, Deliverable $deliverable): bool
    {
        return $authUser->can('Replicate:Deliverable');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Deliverable');
    }

}