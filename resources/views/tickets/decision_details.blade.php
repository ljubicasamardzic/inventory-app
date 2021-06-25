<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Officer
                </h3>
                {{-- only the person who took over the request can change it at this point, not even the superadmin is allowed to change somebody else's decision --}}
                @if ($ticket->officer_approval != App\Models\Ticket::PENDING && $ticket->HR_approval == App\Models\Ticket::PENDING && auth()->id() == $ticket->officer_id)
                    <button  
                            class="btn btn-primary btn-sm float-right ml-2"
                            data-toggle="modal"
                            data-target="#update_officer_decision"
                            onclick="officerEditDisplay({{$ticket->ticket_type}}, {{$ticket->ticket_request_type}}, false, {{$ticket->officer_approval}})"
                    >
                        <i class="fas fa-edit mr-1"></i>
                    </button>
                @endif
                @if ($ticket->isOfficerApprovedOrderNewRequest())
                    @can('view', $ticket)
                        <a href="/tickets/{{ $ticket->id }}/export" class="btn btn-dark btn-sm float-right">
                            <i class="fas fa-download mr-1"></i>
                            Download
                        </a>
                    @endcan
                @endif
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <tr>
                            <td>Name:</td>
                            @if ($ticket->officer != null)
                                <td>
                                    <a href="/users/{{ $ticket->officer->id }}">
                                        {{ $ticket->officer->name }}
                                    </a>
                                </td>
                            @else <td>/</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Approval status:</td>
                            <td><span class="badge {{ $ticket->officerApproval->icon }}">{{ $ticket->officerApproval->name }}</span></td>
                        </tr>
                        @if ($ticket->officerApproval->id == App\Models\Ticket::APPROVED) 
                            @if ($ticket->isNewEquipmentRequest())
                                <tr>
                                    <td>Proposed equipment:</td>
                                    @if ($ticket->equipment_id != null)
                                        <td>{{ $ticket->equipment->full_name }}</td>
                                    @else <td>Order new equipment</td>
                                    @endif
                                </tr>
                            @endif
                            <tr>
                                <td>Price (€):</td>
                                @if ($ticket->price)
                                    <td>{{ $ticket->price }}</td>
                                @else
                                    <td>/</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Deadline:</td>
                                @if ($ticket->deadline != null)
                                    <td>{{ $ticket->formatted_deadline }}</td>
                                @else <td>/</td>
                                @endif
                            </tr>
                        @endif
                        <tr>
                            <td>Remarks:</td>
                                    @if ($ticket->officer_remarks != null)
                                        <td>{{ $ticket->officer_remarks }}</td>
                                    @else <td>/</td>
                                @endif
                        </tr>
                    </table>    
                </div>
            </div><!-- /.card-body -->
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    HR
                </h3>
                 {{-- only the person who took over the request can change it at this point, not even the superadmin is allowed to change somebody else's decision --}}
                @if ($ticket->HR_approval != App\Models\Ticket::PENDING && $ticket->status_id != App\Models\Ticket::PROCESSED && auth()->id() == $ticket->HR_id)
                    <button  
                        class="btn btn-primary btn-sm float-right ml-2"
                        data-toggle="modal"
                        data-target="#update_HR_decision"
                    >
                        <i class="fas fa-edit mr-1"></i>
                    </button>
                @endif        
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <tr>
                            <td>Name:</td>
                            @if ($ticket->HR != null)
                                <td>
                                    <a href="/users/{{ $ticket->HR->id }}">
                                        {{ $ticket->HR->name }}
                                    </a>
                                </td>
                            @else <td>/</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Approval status:</td>
                            <td><span class="badge {{ $ticket->HRApproval->icon }}">{{ $ticket->HRApproval->name }}</span></td>
                        </tr>
                        @if ($ticket->HR_approval != App\Models\Ticket::PENDING)
                        @endif
                        <tr>
                            <td>Remarks:</td>
                            @if ($ticket->HR_remarks != null)
                                <td>{{ $ticket->HR_remarks }}</td>
                                @else <td>/</td>
                            @endif
                        </tr>
                    </table>    
                </div> 
            </div><!-- /.card-body -->
        </div>
    </div>
</div>

