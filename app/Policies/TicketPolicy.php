<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function before(User $user) {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Ticket $ticket)
    {
        if ($user->isSupportOfficer() && $ticket->isEquipmentRequest()) {
            return true;
        } else if ($user->isAdministrativeOfficer() && $ticket->isSuppliesRequest()) {
            return true;
        } else if ($user->isHR() && $ticket->officer_approval != Ticket::PENDING) {
            return true;
        }

    }


    public function create(User $user)
    {
        return $user->isEmployee(); 
    }

    public function update(User $user, Ticket $ticket)
    {
        //
    }

    public function delete(User $user, Ticket $ticket)
    {
        //
    }

    public function restore(User $user, Ticket $ticket)
    {
        //
    }

    public function forceDelete(User $user, Ticket $ticket)
    {
        //
    }

    public function update_1(User $user) {
        // if ($ticket->isEquipmentRequest() && $user->isSupportOfficer()) {
        //     return true;
        // } else if ($ticket->isSuppliesRequest() && $user->isAdministrativeOfficer()) {
        //     return true;
        // }

        return $user->isSupportOfficer() || $user->isAdministrativeOfficer();
    }

    public function update_2(User $user) {
        // allow only the officer who worked on this case to update this step
    //    return $user->id == $ticket->officer->id;
        return $user->isSupportOfficer() || $user->isAdministrativeOfficer();

    }

    public function update_3(User $user) {
        return $user->isHR();
    }

    public function update_4(User $user) {
        // allow only the officer who worked on this case to update this step
        // return $user->id == $ticket->officer->id;
        return $user->isSupportOfficer() || $user->isAdministrativeOfficer();
    }

    public function export_order(User $user) {
        // if ($ticket->isNewEquipmentRequest()) {
        //     return $user->isHR() || $user->isSupportOfficer();
        // } else if ($ticket->isNewItemsRequest()) {
        //     return $user->isHR() || $user->isAdministrativeOfficer();
        // }
        return $user->isHR() || $user->isAdministrativeOfficer() || $user->isSupportOfficer();
    }
}
