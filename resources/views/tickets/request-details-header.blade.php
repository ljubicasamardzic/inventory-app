<div class="card-header">
    <h3 class="card-title">
        <i class="fas fa-clipboard-list mr-2"></i>                        
        Request details
    </h3>
    {{-- show only if the ticket is unprocessed, since once somebody assumes responsibility over it, 
        somebody else should not take it over --}}
    @if ($ticket->status_id == App\Models\Ticket::UNPROCESSED)
        @can('update1', $ticket)
            <form action="/tickets/update1/{{ $ticket->id }}" method="POST" id="update1-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="officer_id" value="{{ auth()->id() }}">
                <input type="hidden" name="id" value={{ $ticket->id }}>
            </form>
            <button class="btn btn-primary btn-sm float-right" 
                    type="submit"
                    id="take_over_button"
            >
                Take over request
            </button>
        @endcan
    @endif
    {{-- show only if the HR has made the final decision --}}
    @if ($ticket->HR_approval != App\Models\Ticket::PENDING && $ticket->status_id != App\Models\Ticket::PROCESSED)
        @can('update4', $ticket)
            <button class="btn btn-primary btn-sm float-right"
            data-toggle="modal"
            data-target="#mark_finished_modal" 
            >
                Mark finished
            </button>
        @endcan
    @endif

    {{-- if the request has been finished, make that obvious to the user --}}
    @if ($ticket->date_finished != null) 
        <span class="bg-blue float-right px-2 py-1" style="border-radius:5px">Finished on {{ $ticket->finished_date }}</span>
    @endif

    {{-- only show these to the officer or superadmin that should approve or reject the request --}}
        
    @if ($ticket->officer_id != null && $ticket->officer_approval == App\Models\Ticket::PENDING)
        @can('update2', $ticket)
            <div class="col-12">
                    <div class="float-right">
                        <button class="btn btn-sm btn-danger"
                                            data-toggle="modal"
                                            data-target="#reject_request_officer_modal"                
                        >
                            Reject request
                        </button>
                        <button class="btn btn-primary btn-sm ml-2"
                                data-toggle="modal"
                                data-target="#approve_request_officer_modal"
                        >
                            Approve request
                        </button>
                    </div>
                </div>
        @endcan
    @endif

    {{-- buttons are visible only to HR and superadmin after the officer has either approved or rejected the request --}}
    @if (in_array($ticket->officer_approval, [App\Models\Ticket::APPROVED, App\Models\Ticket::REJECTED]) && $ticket->HR_approval == App\Models\Ticket::PENDING)
        @can('update3', $ticket)
            <div class="col-12">
                <div class="float-right">
                    <button class="btn btn-danger btn-sm"
                            data-toggle="modal"
                            data-target="#reject_request_HR_modal" 
                    >
                        Reject request
                    </button>
                    <button class="btn btn-primary ml-2 btn-sm"
                            data-toggle="modal"
                            data-target="#approve_request_HR_modal" 
                    >
                        Approve request
                    </button>
                </div>
            </div>
        @endcan
    @endif

</div><!-- /.card-header -->