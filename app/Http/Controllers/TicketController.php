<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Models\Reservation;

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

        $ticket = Ticket::find($request->id);
        $this->authorize('update1', $ticket);

        $request->merge(['status_id' => Ticket::IN_PROGRESS]);

        $ticket->update($request->only(['officer_id', 'status_id']));
        return redirect()->back();
    }

    public function update_2(TicketRequest $request) {

        $ticket = Ticket::find($request->id);
        $this->authorize('update2', $ticket);

        //  Case 1: no available equipment for the desired item so we need to order it
        // zero is used since ids of items start from 1
        if ($request->equipment_id == '0') {
            $request['equipment_id'] = null;
            $request['status_id'] = Ticket::WAITING_FOR_EQUIPMENT;
        } 

        $ticket->update($request->only(['officer_approval', 'price', 'deadline', 'officer_remarks', 'equipment_id', 'serial_number_id', 'status_id']));
        
        if ($request->equipment_id != null) {
            // make a new reservation and amend the quantity of the requested item
            $new_reservation = Reservation::create(['ticket_id' => $ticket->id]);
            $new_reservation->ticket->equipment->update(['available_quantity' => $new_reservation->ticket->equipment->available_quantity - 1]);
        }
        
        return redirect()->back();
    }

    public function update_3(TicketRequest $request) {

        $ticket = Ticket::find($request->id);
        $this->authorize('update3', Ticket::class);

        // if HR rejects the request where a reservation already exists, delete it and amend the item quantity
        if ($ticket->isNewEquipmentRequest() && $ticket->equipment_id != null && $request->HR_approval == Ticket::REJECTED) {
            $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);
            $ticket->reservation->delete();
        }

        $ticket->update($request->only(['HR_approval', 'HR_id', 'HR_remarks']));
        return redirect()->back();
    }

    public function update_4(TicketRequest $request) {

        $ticket = Ticket::find($request->id);

        $this->authorize('update4', $ticket);

        if ($ticket->isNewEquipmentRequest()) {
            
            // Case 1: New equipment request with reserved items
            // Case 2: Rejected by officer and then approved by HR in which case we still need to add equipment to the ticket 
            // Case 3: Waited for equipment to arrive and have HR's approval
            if ($ticket->equipment_id != null && $ticket->HR_approval == Ticket::APPROVED || $ticket->officer_approval == Ticket::REJECTED && $ticket->HR_approval == Ticket::APPROVED || $ticket->status_id == Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == Ticket::APPROVED) {
                $new_doc = $ticket->createDocument();
        
                // Case 2
                $ticket->equipment_id != null ? $equipment_id = $ticket->equipment_id : $equipment_id = $request['equipment_id'];
                $ticket->serial_number_id != null ? $serial_number_id = $ticket->serial_number_id : $serial_number_id = $request['serial_number_id'];

                $ticket->createDocumentItem($new_doc, $equipment_id, $serial_number_id);

                // delete the reservation and stop counting it
                if ($ticket->reservation != null) {
                    $ticket->reservation->delete(); 
                    $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);
                }

                $request['document_id'] = $new_doc->id; 
                $ticket->update($request->only(['status_id', 'date_finished', 'serial_number_id', 'equipment_id', 'document_id']));

                $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity - 1]);
            
            } else {
                $ticket->update($request->only(['status_id', 'date_finished', 'serial_number_id', 'equipment_id', 'document_id']));
            }
        } else {
            $ticket->update($request->only(['status_id', 'date_finished', 'serial_number_id', 'equipment_id', 'document_id']));
        }

        return redirect()->back();
    }

    public function export_order($id) {

        $ticket = Ticket::find($id);

        $data = [
            'key' => 1,
            'id' => $id, 
            'officer' => $ticket->officer ? $ticket->officer->name : '/',
            'equipment_category' => $ticket->equipment_category ? $ticket->equipment_category->name : '/', 
            'quantity' => $ticket->quantity ? $ticket->quantity : '/', 
            'price' => $ticket->price ? $ticket->price : '/', 
            'remarks' => $ticket->officer_remarks ? $ticket->officer_remarks : '/', 
            'deadline' => $ticket->deadline ? $ticket->formatted_deadline : '/'
        ];
        
        return Excel::download(new OrderExport($data), 'order.xlsx');
    }
}
