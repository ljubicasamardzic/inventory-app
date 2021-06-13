<div class="row">
    <div class="col-6 table-responsive">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ticket details</h3>
            </div>
            <div class="card-body">
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
                        <td>Status:</td>
                        <td><span class="badge {{ $ticket->status->icon }}">{{ $ticket->status->name }}</span></td>
                    </tr>
                    <tr>
                        <td>Request date:</td>
                        <td>{{ $ticket->date }}</td>
                    </tr>
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
            
        </div>
       
    </div>

    <div class="col-6 table-responsive">
        <div class="card">
            @if ($ticket->status_id == App\Models\Ticket::PROCESSED)
                <div class="card-header">
                    <h3 class="card-title">Action details</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-sm">
                            <tr>
                                <td>Final status:</td>
                                <td><span class="badge {{ $ticket->HRApproval->icon }}">{{ $ticket->HRApproval->name }}</span></td>
                        @if ($ticket->document_id != null)
                            <tr>
                                <td>Document ID:</td>
                                <td>{{ $ticket->document_id }}</td>
                            </tr>
                            <tr>
                                <td>Assigned Equipment:</td>
                                <td>{{ $ticket->equipment->full_name }}</td>
                            </tr>
                            <tr>
                                <td>Serial number:</td>
                                @if ($ticket->serial_number != null)
                                    <td>{{ $ticket->serial_number->serial_number }}</td>
                                @else <td>/</td>
                                @endif
                            </tr>
                        @endif
                        <tr>
                            <td>Finished on:</td>
                            <td>{{ $ticket->finished_date }}</td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
</div>