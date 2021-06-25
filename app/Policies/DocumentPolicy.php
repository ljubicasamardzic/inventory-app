<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {
        
        $abilities = ['viewAny', 'view', 'create', 'restore', 'forceDelete'];
        if ($user->isSuperAdmin() && in_array($ability, $abilities) ) {
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
        return $user->isSupportOfficer() && $document->admin_id == $user->id || $user->isSuperAdmin() && $document->user_id != $user->id;
    }

    public function delete(User $user, Document $document)
    {
        return $user->isSupportOfficer() && $document->admin_id == $user->id || $user->isSuperAdmin() && $document->user_id != $user->id;
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
