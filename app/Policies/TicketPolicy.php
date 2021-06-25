<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability) {
        $abilities = ['viewAny', 'view', "create", "delete", 'export_order', 'restore', 'forceDelete'];
        if ($user->isSuperAdmin() && in_array($ability, $abilities) ) {
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
        } else if ($user->isEmployee() && $ticket->user_id == $user->id) {
            return true;
        } else if ($user->id == $ticket->user_id) {
            return true;
        }

    }

    public function create(User $user)
    {
        return ($user->isEmployee() || $user->isSupportOfficer() || $user->isAdministrativeOfficer() || $user->isHR());
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->user_id;
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->id == $ticket->user_id;
    }

    public function restore(User $user, Ticket $ticket)
    {
        //
    }

    public function forceDelete(User $user, Ticket $ticket)
    {
        //
    }

    // admins as well as the superamin cannot review the ticket they themselves sent 
    public function update1(User $user, Ticket $ticket) {
        if ($ticket->isEquipmentRequest() && $user->isSupportOfficer() && $user->id != $ticket->user_id) {
            return true;
        } else if ($ticket->isSuppliesRequest() && $user->isAdministrativeOfficer() && $user->id != $ticket->user_id) {
            return true;
        } else if ($user->isSuperAdmin() && $user->id != $ticket->user_id) {
            return true;
        }
    }

    public function update2(User $user, Ticket $ticket) {
        // allow only the officer who worked on this case to update this step or the superadmin, as long as he/she did not sent the ticket
        return $user->id == $ticket->officer->id || $user->isSuperAdmin() && $ticket->user_id != $user->id;

    }
    public function update3(User $user, Ticket $ticket) {
        // allow only the officer who worked on this case to update this step or the superadmin, as long as he/she did not sent the ticket
        return $user->isHR() || $user->isSuperAdmin() && $ticket->user_id != $user->id;
    }

    public function update4(User $user, Ticket $ticket) {
        // allow only the officer who worked on this case to update this step or the superadmin, as long as he/she did not sent the ticket
        return $user->id == $ticket->officer->id || $user->isSuperAdmin() && $ticket->user_id != $user->id;
    }

    public function export_order(User $user, Ticket $ticket) {
        if ($ticket->isNewEquipmentRequest()) {
            return $user->isHR() || $user->isSupportOfficer();
        } else if ($ticket->isNewItemsRequest()) {
            return $user->isHR() || $user->isAdministrativeOfficer();
        }
    }

    // only the HR/admin who made the decision can change it 
    public function update_officer_decision(User $user, Ticket $ticket) {
        return $ticket->officer_id == $user->id;
    }

    public function update_HR_decision(User $user, Ticket $ticket) {
        return $ticket->HR_id == $user->id;
    } 
}
