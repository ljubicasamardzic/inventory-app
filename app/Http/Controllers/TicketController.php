<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        
        Ticket::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        // dd($ticket);
        $content_header = "Ticket details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/#open_requests_id' ],
            // [ 'name' => 'Ticket list', 'link' => '/equipment' ],
            [ 'name' => 'Ticket details', 'link' => '/tickets/'.$ticket->id ],
        ];
        return view('tickets.show', compact(['content_header', 'breadcrumbs', 'ticket']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        // dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
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

        $ticket->update($request->only(['officer_approval']));
        return redirect()->back();
    }

    public function update_3(TicketRequest $request) {
        // dd($request);
        $ticket = Ticket::find($request->id);

        $ticket->update($request->only(['HR_approval', 'HR_id']));
        return redirect()->back();
    }

    public function update_4(TicketRequest $request) {
        $ticket = Ticket::find($request->id);

        $ticket->update($request->only(['status_id']));
        return redirect()->back();
    }
}
