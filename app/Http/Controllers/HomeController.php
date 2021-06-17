<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Equipment;
use App\Models\User;
use App\Models\EquipmentCategory;
use App\Models\Ticket;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $TicketController;

    public function __construct(TicketController $TicketController)
    {
        $this->middleware('auth');
        $this->TicketController = $TicketController;
    }

    public function index()
    {
        $user = User::query()->find(auth()->id());
        $equipment = $user->items;
        $equipment_categories = EquipmentCategory::all();

        $categories = EquipmentCategory::query()
                                        ->whereHas('available_equipment')
                                        ->with('available_equipment')
                                        ->get();

        if ($user->isSuperAdmin()) {
            $tickets = Ticket::all();
            // $tickets = Ticket::query()->open();
        } else if ($user->isSupportOfficer()) {
            $tickets = Ticket::query()->equipmentRequests();
        } else if ($user->isAdministrativeOfficer()) {
            $tickets = Ticket::query()->suppliesRequests();
        } else if ($user->isHR()) {
            $tickets = Ticket::query()->readyForHR();
        } else if ($user->isEmployee()) {
            $tickets = Ticket::query()->openUserTickets();
        } else {
            $tickets = [];
        }

        return view('home', compact(['categories', 'equipment', 'equipment_categories', 'tickets']));
    }

    public function notifications() {
        $content_header = "Notifications";
        $notifications = auth()->user()->notifications()->paginate(10);

        return view('notifications', compact(['notifications', 'content_header']));
    }

    public function mark_read_notification($id) {
        dd($id, 'Home controller');
        $notification = auth()->user()->notifications->where('id', $id)->first();
        $notification->update(['read_at' => now()]);
        $not_type = $notification->type;

        if ($not_type == 'App\Notifications\TicketClosedNotification' || 'App\Notifications\HRResponseNotification') {
            // show the ticket
            $ticket = Ticket::find($notification->data['ticket']['id']);
            return $this->TicketController->show($ticket);
            // take the user to dashboard 
        } else if ($not_type == 'App\Notifications\TicketApprovedNotification' || $not_type == 'App\Notifications\TicketRejectedNotification' || $not_type == 'App\Notifications\EquipmentAssignedNotification') {
            // return redirect('/@');
        } else if ($notification->type == 'App\Notifications\NewEquipmentNotification') {
            dd('new equipment support');
        }

    }

}
