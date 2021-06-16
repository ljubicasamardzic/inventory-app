<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentPolicy
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

    public function view(User $user, Equipment $equipment)
    {
        return $user->isHR() || $user->isSupportOfficer();
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Equipment $equipment)
    {
        //
    }

    public function delete(User $user, Equipment $equipment)
    {
        //
    }

    public function restore(User $user, Equipment $equipment)
    {
        //
    }

    public function forceDelete(User $user, Equipment $equipment)
    {
        //
    }

    public function serial_numbers() {
        //
    }

    public function reports_index() {
        //
    }

    public function report_by_department() {
        //
    }

    public function report_by_position() {
        //
    }

    public function report_by_category() {
        //
    }

    public function report_by_employee() {
        //
    }
}
