<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

   
    public function view(User $user, User $model)
    {
        return $user->isAdmin();
    }

    
    public function create(User $user)
    {
        return $user->isHR();
    }

   
    public function update(User $user, User $model)
    {
        return $user->isHR();
    }

    public function delete(User $user, User $model)
    {
        return $user->isHR();
    }

    public function restore(User $user, User $model)
    {
        return $user->isHR();
    }

    public function forceDelete(User $user, User $model)
    {
        return $user->isHR();
    }
}
