<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function before($user) {
        if ($user->isSuperAdmin()) {
            return true;
        } 
    }

    public function viewAny(User $user)
    {
        return $user->isHR() || $user->isSupportOfficer();
    }

    public function view(User $user, Document $document)
    {
        return $user->isHR() || $user->isSupportOfficer();
    }

    public function create(User $user)
    {
        return $user->isSupportOfficer();
    }

    public function update(User $user, Document $document)
    {
        return $user->isSupportOfficer();
    }

    public function delete(User $user, Document $document)
    {
        //
    }

    public function restore(User $user, Document $document)
    {
        //
    }

    public function forceDelete(User $user, Document $document)
    {
        //
    }
}
