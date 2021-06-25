<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\User;
use App\Models\EquipmentCategory;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use App\Http\Requests\PasswordRequest;

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

    public function index(Request $request)
    {
        $user = User::query()->find(auth()->id());
        $equipment = $user->current_items;
        $equipment_categories = EquipmentCategory::all();
        $ticket_statuses = TicketStatus::all();
        $processed_repair_tickets = Ticket::query()->processedRepairTickets($request);

        // dd($processed_repair_tickets);
        $categories = EquipmentCategory::query()
                                        ->whereHas('available_equipment')
                                        ->with('available_equipment')
                                        ->get();

        if ($user->isSuperAdmin()) {
            $tickets = Ticket::query()->search($request)->paginate(Ticket::PER_PAGE);
        } else if ($user->isSupportOfficer()) {
            $tickets = Ticket::query()->equipmentRequests()->search($request)->paginate(Ticket::PER_PAGE);
        } else if ($user->isAdministrativeOfficer()) {
            $tickets = Ticket::query()->suppliesRequests()->search($request)->paginate(Ticket::PER_PAGE);
        } else if ($user->isHR()) {
            $tickets = Ticket::query()->readyForHR()->search($request)->paginate(Ticket::PER_PAGE);
        } else if ($user->isEmployee()) {
            $tickets = Ticket::query()->openUserTickets()->search($request)->paginate(Ticket::PER_PAGE);
        } else {
            $tickets = [];
        }

        // if (count($request->all()) > 0) {
        //     return redirect()->route('home', 
        //     [   'categories' => $categories,
        //         'equipment' => $equipment,
        //         'equipment_categories' => $equipment_categories,
        //         'tickets' => $tickets,
        //         'ticket_statuses' => $ticket_statuses,
        //         'processed_repair_tickets' => $processed_repair_tickets
        //     ]);

        //     // return redirect('/#requests_table')->with([
        //     //     'categories' => $categories,
        //     //     'equipment' => $equipment,
        //     //     'equipment_categories' => $equipment_categories,
        //     //     'tickets' => $tickets,
        //     //     'ticket_statuses' => $ticket_statuses,
        //     //     'processed_repair_tickets' => $processed_repair_tickets
        //     // ]);
        //     //  compact(['categories', 'equipment', 'equipment_categories', 'tickets', 'ticket_statuses', 'processed_repair_tickets']));

        // } else {
            return view('home', compact(['categories', 'equipment', 'equipment_categories', 'tickets', 'ticket_statuses', 'processed_repair_tickets']));
        // }

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

    public function mark_notifications_read($user_id) {
        $user = User::find($user_id);
        $user->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function edit_password() {
        $content_header = "Change password";

        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Change password', 'link' => '/edit_password' ],
        ];
        return view('home.edit_password', compact(['content_header', 'breadcrumbs']));
    }

    public function update_password(PasswordRequest $request) {
        $validated = $request->validated();
        $update = User::find($request->validated())->first()->update(['password' => $validated['new_password']]);

        if ($update) {
            alert()->success('Password updated!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }

        return redirect('/');
    }

}
