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
    protected $EquipmentController;

    public function __construct(TicketController $TicketController, EquipmentController $EquipmentController)
    {
        $this->middleware('auth');
        // to be able to call a function from another controller
        $this->TicketController = $TicketController;
        $this->EquipmentController = $EquipmentController;
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
            $tickets = Ticket::query()->paginate(Ticket::PER_PAGE);
            // $tickets = Ticket::query()->open();
        } else if ($user->isSupportOfficer()) {
            $tickets = Ticket::query()->equipmentRequests()->paginate(Ticket::PER_PAGE);
        } else if ($user->isAdministrativeOfficer()) {
            $tickets = Ticket::query()->suppliesRequests()->paginate(Ticket::PER_PAGE);
        } else if ($user->isHR()) {
            $tickets = Ticket::query()->readyForHR()->paginate(Ticket::PER_PAGE);
        } else if ($user->isEmployee()) {
            $tickets = Ticket::query()->openUserTickets()->paginate(Ticket::PER_PAGE);
        } else {
            $tickets = [];
        }

        return view('home', compact(['categories', 'equipment', 'equipment_categories', 'tickets']));
    }

    public function notifications() {
        $content_header = "Notifications";

        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Notifications', 'link' => '/notifications' ],
        ];

        $notifications = auth()->user()->notifications()->paginate(Ticket::PER_PAGE);

        $rejected = 'App\Notifications\TicketRejectedNotification';
        $approved = 'App\Notifications\TicketApprovedNotification';
        $closed = 'App\Notifications\TicketClosedNotification';
        $HR_responded = 'App\Notifications\HRResponseNotification';
        $new_equipment = 'App\Notifications\NewEquipmentNotification';
        $new_equipment = 'App\Notifications\NewEquipmentNotification';
        $restocked = 'App\Notifications\RestockedNotification';

        return view('notifications', compact(['notifications', 'breadcrumbs', 'content_header', 'rejected', 'approved', 'closed', 'HR_responded', 'new_equipment', 'restocked']));
    }

    public function mark_read_notification($id) {
        $notification = auth()->user()->notifications->where('id', $id)->first();
        $notification->update(['read_at' => now()]);
        $not_type = $notification->type;

        $rejected = 'App\Notifications\TicketRejectedNotification';
        $approved = 'App\Notifications\TicketApprovedNotification';
        $closed = 'App\Notifications\TicketClosedNotification';
        $HR_responded = 'App\Notifications\HRResponseNotification';
        $new_equipment = 'App\Notifications\NewEquipmentNotification';
        $restocked = 'App\Notifications\RestockedNotification';

        if (in_array($not_type, [$closed, $approved, $rejected, $HR_responded])) {
            // show the ticket
            $ticket = Ticket::find($notification->data['ticket']['id']);
            return $this->TicketController->show($ticket);
        }  else if ($not_type == $new_equipment || $not_type == $restocked) {
            // show the newly arrived equipment
            $equipment = Equipment::find($notification->data['equipment']['id']);
            return $this->EquipmentController->show($equipment);
        }

    }

}
