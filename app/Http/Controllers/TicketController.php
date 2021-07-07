<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Http\Requests\NewTicketRequest;
use App\Models\EquipmentCategory;
use App\Models\Reservation;
use App\Notifications\HRResponseNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketClosedNotification;
use App\Notifications\TicketApprovedNotification;
use App\Notifications\TicketRejectedNotification;
use Illuminate\Support\Facades\DB;

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

    public function store(NewTicketRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        
        $create = Ticket::create($request->all());

        if ($create) {
            alert()->success('Your request has been sent!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }
        // return redirect()->back();
    }

    public function show(Ticket $ticket)
    {

        // dd($ticket->equipment, $ticket->equipment->serial_numbers);
        $equipment = $ticket->user->current_items;
        // dd($equipment);
        $equipment_categories = EquipmentCategory::all();

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
        return view('tickets.show', compact(['content_header', 'breadcrumbs', 'ticket', 'available_equipment', 'equipment_categories', 'equipment']));
    }

    public function edit(Ticket $ticket)
    {
        //
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        // dd($request);
        if ($request->ticket_type == Ticket::NEW_EQUIPMENT && $request->ticket_request_type == Ticket::EQUIPMENT_REQUEST) {
            // important to specify what exactly we are sending since information from different request types could be saved in the request
            $request['description_supplies'] = null;
            $request['quantity'] = null;
            $request['serial_number_id'] = null;
            $request['equipment_id'] = null;
            $request['description_malfunction'] = null;

            if ($ticket->update($request->all())) {
                alert()->success('You have successfully updated your request!', 'Success!');
            } else {
                alert()->error('Something went wrong!', 'Oops..');
            }
            
        } else if ($request->ticket_type == Ticket::NEW_EQUIPMENT && $request->ticket_request_type == Ticket::OFFICE_SUPPLIES_REQUEST) {
            $request['equipment_category_id'] = null;
            $request['description_equipment'] = null;
            $request['serial_number_id'] = null;
            $request['equipment_id'] = null;
            $request['description_malfunction'] = null;

            if ($ticket->update($request->all())) {
                alert()->success('You have successfully updated your request!', 'Success!');
            } else {
                alert()->error('Something went wrong!', 'Oops..');
            } 
        } else if ($request->ticket_type == Ticket::REPAIR_EQUIPMENT) {
            $request['equipment_category_id'] = null;
            $request['description_equipment'] = null;
            $request['description_supplies'] = null;
            $request['quantity'] = null;

            if ($ticket->update($request->all())) {
                alert()->success('You have successfully updated your request!', 'Success!');
            } else {
                alert()->error('Something went wrong!', 'Oops..');
            }
        }

        return redirect()->back();
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->delete()) {
            alert()->success('You have successfully deleted your request!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }

        return redirect('/');
    }

    public function update_1(TicketRequest $request) {

        $ticket = Ticket::find($request->id);
        $this->authorize('update1', $ticket);
        
        $request->merge(['status_id' => Ticket::IN_PROGRESS]);
        
        $ticket->update($request->only(['officer_id', 'status_id']));
        
        alert()->info('You have taken over the handling of this request.');
        return redirect()->back();
    }
    
    public function update_2(TicketRequest $request) {
        // called bu ajax
        $ticket = Ticket::find($request->id);
        $this->authorize('update2', $ticket);

        //  Case 1: no available equipment for the desired item so we need to order it
        // zero is used since ids of items start from 1
        if ($request->equipment_id == '0') {
            $request['equipment_id'] = null;
            $request['status_id'] = Ticket::WAITING_FOR_EQUIPMENT;
        } 

        DB::beginTransaction();
        $update = $ticket->update($request->all());

        if ($update) {
            if ($ticket->isNewEquipmentRequest() && $ticket->officer_approval == Ticket::APPROVED && $ticket->status_id != Ticket::WAITING_FOR_EQUIPMENT) {
                // make a new reservation and amend the quantity of the requested item
                $new_reservation = Reservation::create(['ticket_id' => $ticket->id]);
                // dd($new_reservation->ticket->equipment);
                if ($new_reservation) {

                    $update_quantity = $new_reservation->ticket->equipment->update(['available_quantity' => $new_reservation->ticket->equipment->available_quantity - 1]);
                    if ($update_quantity) {
                        DB::commit();
                        if ($request->officer_approval == Ticket::APPROVED) {
                            alert()->success('You have successfully updated the ticket!', 'Success!');
                        } 
                    } else {
                        DB::rollBack();
                        alert()->error('Something went wrong!', 'Oops..');
                    }
                } else {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            } else {
                DB::commit();
                alert()->success('You have successfully updated the ticket!', 'Success!');
            }
        } else {
            DB::rollBack();
            alert()->error('Something went wrong!', 'Oops..');
        }
        
        // return redirect()->back();
    }

    public function update_3(TicketRequest $request) {

        $ticket = Ticket::find($request->id);
        $this->authorize('update3', $ticket);

        DB::beginTransaction();
        // if HR rejects the request where a reservation already exists, delete it and amend the item quantity
        if ($ticket->isNewEquipmentRequest() && $ticket->equipment_id != null && $request->HR_approval == Ticket::REJECTED) {
            $update = $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);
            $delete = $ticket->reservation->delete();
            if (!$update || !$delete) {
                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }
        }

        $ticket_update = $ticket->update($request->only(['HR_approval', 'HR_id', 'HR_remarks']));

        if ($ticket_update) {
            Notification::send($ticket->officer()->get(), new HRResponseNotification($ticket));
            DB::commit();
            alert()->success('You have successfully updated the ticket!', 'Success!');
        } else {
            DB::rollBack();
            alert()->error('Something went wrong!', 'Oops..');
        }

        return redirect()->back();
    }

    public function update_4(TicketRequest $request) {
        // called by ajax

        $ticket = Ticket::find($request->id);

        $this->authorize('update4', $ticket);

        DB::beginTransaction();
        if ($ticket->isNewEquipmentRequest()) {
            
            // Case 1: New equipment request with reserved items
            // Case 2: Rejected by officer and then approved by HR in which case we still need to add equipment to the ticket 
            // Case 3: Waited for equipment to arrive and have HR's approval
            if ($ticket->equipment_id != null && $ticket->isHRApproved() || !$ticket->isOfficerApproved() && $ticket->isHRApproved() || $ticket->isWaitingForEquipment() && $ticket->isHRApproved()) {
                $new_doc = $ticket->createDocument();
                    // Case 2

                $ticket->equipment_id != null ? $equipment_id = $ticket->equipment_id : $equipment_id = $request['equipment_id'];
                $ticket->serial_number_id != null ? $serial_number_id = $ticket->serial_number_id : $serial_number_id = $request['serial_number_id'];

                $create_doc_item = $ticket->createDocumentItem($new_doc, $equipment_id, $serial_number_id);
                // delete the reservation and stop counting it
                if ($ticket->reservation != null) {
                    $delete = $ticket->reservation->delete(); 
                    $update = $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);

                    if (!$delete || !$update) {
                        DB::rollBack();
                        alert()->error('Something went wrong!', 'Oops..');
                    }
                }

                $request['document_id'] = $new_doc->id; 
                $request['document_item_id'] = $create_doc_item->id;
                
                // we keep note of the doc id, instead of keeping the serial num directly on the ticket
                $update1 = $ticket->update($request->except('serial_number_id'));

                $quantity_update = $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity - 1]);

                if (!$new_doc || !$create_doc_item || !$update1 || !$quantity_update) {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                } else {
                    DB::commit();
                    alert()->success('You have successfully marked the ticket as finished!', 'Success!');
                    $user = $ticket->user()->get();
                    Notification::send($user, new TicketApprovedNotification($ticket));
                }
            } else {
                $update2 = $ticket->update($request->all());
                if ($update2) {
                    DB::commit();
                    Notification::send($ticket->user()->get(), new TicketRejectedNotification($ticket));
                    alert()->success('You have successfully marked the ticket as finished!', 'Success!');
                } else {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            }
        } else if ($ticket->isRepairRequest()) {
  
            $update3 = $ticket->update($request->all());

            if ($update3) {
                if ($ticket->isHRApproved()) {
                    Notification::send($ticket->user()->get(), new TicketApprovedNotification($ticket));
                } else {
                    Notification::send($ticket->user()->get(), new TicketRejectedNotification($ticket));
                }
                DB::commit();
                alert()->success('You have successfully marked the ticket as finished!', 'Success!');
            } else {
                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }

        } else if ($ticket->isSuppliesRequest()) {
            
            $update4 = $ticket->update($request->all());

            if ($update4) {
                if ($ticket->isHRApproved()) {
                    Notification::send($ticket->user()->get(), new TicketApprovedNotification($ticket));
                } else {
                    Notification::send($ticket->user()->get(), new TicketRejectedNotification($ticket));
                }
                DB::commit();
                alert()->success('You have successfully marked the ticket as finished!', 'Success!');
            } else {

                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }
        }
        
        Notification::send($ticket->HR()->get(), new TicketClosedNotification($ticket));
        // return redirect()->back();
    }

    // UPDATE OFFICER DECISION
    public function update_officer_decision($id, TicketRequest $request) {
        // called by ajax
        $ticket = Ticket::find($id);
        $this->authorize('update_officer_decision', $ticket);

        if ($ticket->isNewEquipmentRequest()) {
            if ($ticket->officer_approval == Ticket::APPROVED && $request->officer_approval == Ticket::APPROVED) {
                // make sure the status is right if the equipment is being waited for
                if ($request->equipment_id == null) {
                    $request['status_id'] = Ticket::WAITING_FOR_EQUIPMENT;
                } else if ($request->equipment_id != null) {
                    $request['status_id'] = Ticket::IN_PROGRESS;
                }

                DB::beginTransaction();
                // if it happens that the request was approved but that there was no available equipment 
                // and then the equipment arrives, we must make a new reservation 
                if ($ticket->equipment_id == null && $request->equipment_id != null) {
                    $new_reservation = Reservation::create(['ticket_id' => $ticket->id]);
                    
                    // firstly update the ticket 
                    $update_ticket = $ticket->update($request->all());

                    // then update the quantity
                    $update_quantity = $new_reservation->ticket->equipment->update(['available_quantity' => $new_reservation->ticket->equipment->available_quantity - 1]);
                    if ($new_reservation && $update_ticket && $update_quantity) {
                        DB::commit();
                        alert()->success('You have successfully updated the ticket!', 'Success!');
                    } else {
                        DB::rollBack();
                        alert()->error('Something went wrong!', 'Oops..');
                    }
                } else {
                    if ($ticket->update($request->all())) {
                        DB::commit();
                        alert()->success('You have successfully updated the ticket!', 'Success!');
                    } else {
                        DB::rollBack();
                        alert()->error('Something went wrong!', 'Oops..');
                    }
                }

                // if the ticket changes from approved to rejected
            } else if ($ticket->officer_approval == Ticket::APPROVED && $request->officer_approval == Ticket::REJECTED) {
                 // check if there is a reservation and delete it
                 DB::beginTransaction();
                 if ($ticket->reservation != null) {
                    
                    $update = $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);
                    
                    if ($update) {
                        $ticket->reservation->delete();
                    } else {
                        DB::rollBack();
                        alert()->error('Something went wrong!', 'Oops..');
                    }
                }
                // if the request used to wait for new equipment, change that status now that it is rejected
                if ($ticket->status_id == Ticket::WAITING_FOR_EQUIPMENT) {
                    $request['status_id'] = Ticket::IN_PROGRESS;
                }   

                $request['equipment_id'] = null;
                $request['deadline'] = null;
                $request['price'] = null;
                if ($ticket->update($request->all())) {
                    DB::commit();
                    alert()->success('You have successfully updated the ticket!', 'Success!');
                } else {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            } else if ($ticket->officer_approval == Ticket::REJECTED && $request->officer_approval == Ticket::REJECTED) {
                $request['equipment_id'] = null;
                $request['deadline'] = null;
                $request['price'] = null;
                if ($ticket->update($request->all())) {
                    DB::commit();
                    alert()->success('You have successfully updated the ticket!', 'Success!');
                } else {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            } else if ($ticket->officer_approval == Ticket::REJECTED && $request->officer_approval == Ticket::APPROVED) {
                DB::beginTransaction();

                if (!$ticket->update($request->all())) {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }

                if ($ticket->equipment_id != null) {
                    // make a reservation 
                    $new_reservation = Reservation::create(['ticket_id' => $ticket->id]);
                    // dd($new_reservation->ticket->equipment);
                    
                    $update_quantity = $new_reservation->ticket->equipment->update(['available_quantity' => $new_reservation->ticket->equipment->available_quantity - 1]);
                    
                    if (!$new_reservation || !$update_quantity) {
                        DB::rollBack();
                        alert()->error('Something went wrong!', 'Oops..');
                    } else {
                        DB::commit();
                        alert()->success('You have successfully updated the ticket!', 'Success!');
                    }
                } else if ($ticket->equipment_id == null) {
                    // no equipment id means that the equipment is being waited for 
                    $update_status = $ticket->update(['status_id' => Ticket::WAITING_FOR_EQUIPMENT]);
                    if ($update_status) {
                        DB::commit();
                        alert()->success('You have successfully updated the ticket!', 'Success!');
                    }
                }
            }

        } else if ($ticket->isRepairRequest() || $ticket->isSuppliesRequest()) {
            if ($request->officer_approval == Ticket::APPROVED) {
                DB::beginTransaction();
                if ($ticket->update($request->all())) {
                    DB::commit();
                    alert()->success('You have successfully updated the ticket!', 'Success!');
                } else {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            } else if ($request->officer_approval == Ticket::REJECTED) {
                // delete everything that could be filled if the request was changed from approved to rejected
                $request['deadline'] = null;
                $request['price'] = null;
                if ($ticket->update($request->all())) {
                    DB::commit();
                    alert()->success('You have successfully updated the ticket!', 'Success!');
                } else {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            }
        }
    }

    public function update_HR_decision($id, TicketRequest $request) {
        $ticket = Ticket::find($id);
        $this->authorize('update_HR_decision', $ticket);

        // Case 1: only the comment differs
        if ($ticket->HR_approval == Ticket::APPROVED && $request->HR_approval == Ticket::APPROVED) {
            $ticket->update($request->all());
            // Case 2: check if a reservation has been made and delete it
        } else if ($ticket->HR_approval == Ticket::APPROVED && $request->HR_approval == Ticket::REJECTED) {
            if ($ticket->reservation != null) {
                $update_quantity = $ticket->equipment->update(['available_quantity' => $ticket->equipment->available_quantity + 1]);
                if ($update_quantity) $ticket->reservation->delete();
            }
            
            $ticket->update($request->all());
            $ticket->update(['status_id' => Ticket::IN_PROGRESS]);

            // Case 3: make a reservation if necessary (perhaps one had been deleted if the ticket was firstly rejected by the HR)
        } else if ($ticket->HR_approval == Ticket::REJECTED && $request->HR_approval == Ticket::APPROVED) {
            if ($ticket->equipment_id != null && $ticket->reservation == null) {
                $new_reservation = Reservation::create(['ticket_id' => $ticket->id]);
                    
                $update_quantity = $new_reservation->ticket->equipment->update(['available_quantity' => $new_reservation->ticket->equipment->available_quantity - 1]);
            } 
            $ticket->update($request->all());
            // Case 4: probably only comment changed
        } else if ($ticket->HR_approval == Ticket::REJECTED && $request->HR_approval == Ticket::REJECTED) {
            $ticket->update($request->all());
        }

        return redirect()->back();
    }

    // export new order details 
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

    public function test(Request $request) {
        $result = $request->ticket_type;
        return response()->json($result);
    }
}
