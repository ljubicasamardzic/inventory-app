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

    public function update1(User $user, Ticket $ticket) {
        if ($ticket->isEquipmentRequest() && $user->isSupportOfficer()) {
            return true;
        } else if ($ticket->isSuppliesRequest() && $user->isAdministrativeOfficer()) {
            return true;
        }
    }

    public function update2(User $user, Ticket $ticket) {
        if ($ticket->isEquipmentRequest() && $user->isSupportOfficer()) {
            return true;
        } else if ($ticket->isSuppliesRequest() && $user->isAdministrativeOfficer()) {
            return true;
        }
    }

    public function update3(User $user, Ticket $ticket) {
        return $user->isHr();
    }

    public function update4(User $user, Ticket $ticket) {
        if ($ticket->isEquipmentRequest() && $user->isSupportOfficer()) {
            return true;
        } else if ($ticket->isSuppliesRequest() && $user->isAdministrativeOfficer()) {
            return true;
        }
    }
}
