@extends('layouts.main')

@section('page_title', 'Ticket details')

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Ticket details
                    </h3>
                    <button class="btn btn-primary btn-sm float-right">
                        Take over request
                    </button>
                    <button class="btn btn-primary btn-sm float-right">Mark finished</button>

                </div><!-- /.card-header -->


                <div class="card-body table-responsive">

                    <div class="row">
                        <div class="col-5 table-responsive">
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
                                    <td>Admin:</td>
                                    @if ($ticket->officer != null)
                                        <td>{{ $ticket->officer->name }}</td>
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
                                <tr>
                                    <td>Finished:</td>
                                    <td> @if($ticket->is_done)
                                        <i class="fa fa-check-circle"></i>
                                    @else
                                        <i class="fa fa-times-circle"></i>
                                    @endif</td>
                                </tr>
                            </table>
                        </div>

                    <div class="col-7 table-responsive">
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
                                            @if ($ticket->equipment != null)
                                                <td>{{ $ticket->equipment->serial_number()->serial_number }}</td>
                                            @else <td>/</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Remarks:</td>
                                        @if ($ticket->equipment->description_malfunction != '')
                                                <td>{{ $ticket->description_malfunction }}</td>
                                        @else <td>/</td>
                                        @endif 
                                        </tr>
                                    @endif
                                @endif
                        </table>
                        <div class="float-right">
                            <button class="btn btn-primary mr-2 btn-sm">Approve request</button>
                            <button class="btn btn-danger btn-sm">Reject request</button>
                        </div>
                    </div>

                </div><!-- /.card-body -->
            </div>
         
        </div>
    </div>


@section('additional_scripts')
    <script src="{{ asset('js/equipment/show.js') }}"></script>
@endsection

@endsection
