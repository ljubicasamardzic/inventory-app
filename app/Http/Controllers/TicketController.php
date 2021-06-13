<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Equipment;
use App\Models\Reservation;

use Carbon\Carbon;


class TicketController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Ticket::class, 'ticket');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(TicketRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        
        Ticket::create($request->all());
        return redirect()->back();
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->equipment_category_id != null) {
            $available_equipment = $ticket->equipment_category->available_equipment;        
        } else {
            $available_equipment = [];
        }
        // dd($available_equipment);
        $content_header = "Request details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/#open_requests_id' ],
            // [ 'name' => 'Ticket list', 'link' => '/equipment' ],
            [ 'name' => 'Ticket details', 'link' => '/tickets/'.$ticket->id ],
        ];
        return view('tickets.show', compact(['content_header', 'breadcrumbs', 'ticket', 'available_equipment']));
    }

    public function edit(Ticket $ticket)
    {
        //
    }

    public function update(Request $request, Ticket $ticket)
    {
        // dd($request);
    }

    public function destroy(Ticket $ticket)
    {
        //
    }

    public function update_1(TicketRequest $request) {
        $request->merge(['status_id' => Ticket::IN_PROGRESS]);

        $ticket = Ticket::find($request->id);

        $ticket->update($request->only(['officer_id', 'status_id']));
        return redirect()->back();
    }

    public function update_2(TicketRequest $request) {
        $ticket = Ticket::find($request->id);

        if ($request->equipment_id == 0) {
            $request['equipment_id'] = null;
            $request['status_id'] = Ticket::WAITING_FOR_EQUIPMENT;
        } 

        $ticket->update($request->only(['officer_approval', 'price', 'deadline', 'officer_remarks', 'equipment_id', 'serial_number_id', 'status_id']));
        
        //  if equipment_id is set to 0, that means we want to order new equipment, 
        // but in the database we change the field value to null so as to avoid confusion

        if ($request->equipment_id != null) {
            // make a new reservation and amend the quantity of the request item
            $new_reservation = Reservation::create(['ticket_id' => $ticket->id]);
            // dd($new_reservation->ticket);
            $new_reservation->ticket->equipment->update(['available_quantity' => $new_reservation->ticket->equipment->available_quantity - 1]);
        }
        
        
        return redirect()->back();
    }

    public function update_3(TicketRequest $request) {
        // dd($request);
        $ticket = Ticket::find($request->id);

        if ($request->HR_approval == Ticket::REJECTED) {
            $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);
            $ticket->reservation->delete();
        }

        $ticket->update($request->only(['HR_approval', 'HR_id', 'HR_remarks']));
        return redirect()->back();
    }

    public function update_4(TicketRequest $request) {
        $ticket = Ticket::find($request->id);

        // dd($request);

        // if this a new equipment request and it has an equipment id, it means we only need 
        // to create a new document and add the reserved equipment
        if ($ticket->ticket_type == Ticket::NEW_EQUIPMENT) {
            if ($ticket->equipment_id != null && $ticket->HR_approval == Ticket::APPROVED) {
                $new_doc = Document::create([
                    'user_id' => $ticket->user_id,
                    'admin_id' => $ticket->officer_id,
                    'date' => Carbon::now()->timestamp
                ]);
        
                DocumentItem::create([
                    'document_id' => $new_doc->id,
                    'equipment_id' => $ticket->equipment_id,
                    'serial_number_id' => $ticket->serial_number_id            
                ]);
    
                // delete the reservation
                $ticket->reservation->delete();  
                // if we had waited for equipment to arrive and have HR's approval
                // list out the available equipment so that the officer can assign it to the user and close this request  
            } else if ($ticket->status_id == Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == Ticket::APPROVED) {

            } 
        }

        $ticket->update($request->only(['status_id', 'date_finished', 'serial_number_id', 'equipment_id']));
        return redirect()->back();
    }
}
