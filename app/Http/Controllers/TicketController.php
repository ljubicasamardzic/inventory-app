<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;

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
        // dd($ticket);
        $content_header = "Request details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/#open_requests_id' ],
            // [ 'name' => 'Ticket list', 'link' => '/equipment' ],
            [ 'name' => 'Ticket details', 'link' => '/tickets/'.$ticket->id ],
        ];
        return view('tickets.show', compact(['content_header', 'breadcrumbs', 'ticket']));
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

        $ticket->update($request->only(['officer_approval', 'price', 'deadline', 'officer_remarks']));
        return redirect()->back();
    }

    public function update_3(TicketRequest $request) {
        // dd($request);
        $ticket = Ticket::find($request->id);

        $ticket->update($request->only(['HR_approval', 'HR_id', 'HR_remarks']));
        return redirect()->back();
    }

    public function update_4(TicketRequest $request) {
        $ticket = Ticket::find($request->id);

        $ticket->update($request->only(['status_id', 'date_finished']));
        return redirect()->back();
    }
}
