@extends('layouts.main')

@section('page_title', 'Request details')

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-2"></i>                        
                        Request details
                    </h3>
                    {{-- show only if the ticket is unprocessed, since once somebody assumes responsibility over it, 
                        somebody else should not take it over --}}
                    @if ($ticket->status_id == App\Models\Ticket::UNPROCESSED)
                        @can('update1', $ticket)
                            <form action="/tickets/update1/{{ $ticket->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="officer_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="id" value={{ $ticket->id }}>
                                <button class="btn btn-primary btn-sm float-right" 
                                        type="submit"
                                        id="take_over_button"
                                >
                                    Take over request
                                </button>
                            </form>
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
                    @if ($ticket->finished_date != null) 
                        <span class="bg-blue float-right px-2 py-1" style="border-radius:5px">Finished on {{ $ticket->finished_date }}</span>
                    @endif

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-6 table-responsive">
                            <table class="table table-striped table-sm">
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $ticket->id }}</td>
                                </tr>
                                <tr>
                                    <td>Ticket Type:</td>
                                    <td>
                                        @if ($ticket->ticket_type == 1) New items request
                                        @elseif ($ticket->ticket_type == 2) Repair request
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Request Type:</td>
                                    <td>
                                        @if ($ticket->ticket_request_type == 1) Equipment
                                        @elseif ($ticket->ticket_request_type == 2) Office supplies
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Employee:</td>
                                    <td>{{ $ticket->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Officer:</td>
                                    @if ($ticket->officer != null)
                                        <td>{{ $ticket->officer->name }}</td>
                                    @else <td>N/A</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>HR:</td>
                                    @if ($ticket->admin != null)
                                        <td>{{ $ticket->admin->name }}</td>
                                    @else <td>N/A</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td><span class="badge {{ $ticket->status->icon }}">{{ $ticket->status->name }}</span></td>
                                </tr>
                                <tr>
                                    <td>Request date:</td>
                                    <td>{{ $ticket->date }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-6 table-responsive">
                            <table class="table table-striped table-sm">
                                @if ($ticket->isSuppliesRequest())
                                        <tr>
                                            <td>Required office supplies:</td>
                                            @if ($ticket->description_supplies != null)
                                                <td>{{ $ticket->description_supplies }}</td>
                                            @else <td>/</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Quantity:</td>
                                            @if ($ticket->description_supplies != null)
                                                <td>{{ $ticket->quantity }}</td>
                                            @else <td>/</td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if ($ticket->isEquipmentRequest())
                                        @if ($ticket->isNewItemsRequest())
                                            <tr>
                                                <td>Requested equipment:</td>
                                                @if ($ticket->equipment_category != null)
                                                    <td>{{ $ticket->equipment_category->name }}</td>
                                                @else <td>/</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>Remarks:</td>
                                                @if ($ticket->description_equipment != null)
                                                    <td>{{ $ticket->description_equipment }}</td>
                                                @else <td>/</td>
                                                @endif
                                            </tr>
                                        @elseif ($ticket->isRepairRequest())
                                            <tr>
                                                <td>Malfunctioning equipment:</td>
                                                @if ($ticket->equipment != null)
                                                    <td>{{ $ticket->equipment->full_name }}</td>
                                                @else <td>/</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>Serial number:</td>
                                                @if ($ticket->equipment->serial_number() != null)
                                                    <td>{{ $ticket->equipment->serial_number()->serial_number }}</td>
                                                @else <td>/</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>Remarks:</td>
                                            @if ($ticket->description_malfunction != '')
                                                    <td>{{ $ticket->description_malfunction }}</td>
                                            @else <td>/</td>
                                            @endif 
                                            </tr>
                                        @endif
                                    @endif
                            </table>
                        </div>
                        {{-- only show these to the officer or superadmin that should approve or reject the request --}}
                        
                        @if ($ticket->officer_id != null && $ticket->officer_approval == App\Models\Ticket::PENDING)
                            @can('update2', $ticket)
                                <div class="col-12">
                                    <div class="float-right mr-3">
                                        <button class="btn btn-danger"
                                                            data-toggle="modal"
                                                            data-target="#reject_request_officer_modal"                
                                        >
                                            Reject request
                                        </button>
                                        <button class="btn btn-primary ml-3"
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
                                    <div class="float-right mr-3">
                                        <button class="btn btn-danger"
                                                data-toggle="modal"
                                                data-target="#reject_request_HR_modal" 
                                        >
                                            Reject request
                                        </button>
                                        <button class="btn btn-primary ml-3"
                                                data-toggle="modal"
                                                data-target="#approve_request_HR_modal" 
                                        >
                                            Approve request
                                        </button>
                                    </div>
                                </div>
                            @endcan
                        @endif
                    </div>
                </div> <!-- /.card-body -->
            </div> 
            @include('tickets.decision_details') 
        </div>
    </div>
@include('tickets/modals/reject_request_officer_modal')
@include('tickets/modals/approve_request_officer_modal')
@include('tickets/modals/reject_request_HR_modal')
@include('tickets/modals/approve_request_HR_modal')
@include('tickets/modals/mark_finished_modal')

@section('additional_scripts')
    <script src="{{ asset('js/tickets/show.js') }}"></script>
@endsection

@endsection
