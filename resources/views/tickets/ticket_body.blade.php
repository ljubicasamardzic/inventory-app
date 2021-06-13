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
                <td>Status:</td>
                <td><span class="badge {{ $ticket->status->icon }}">{{ $ticket->status->name }}</span></td>
            </tr>
        </table>
    </div>

    <div class="col-6 table-responsive">
        <table class="table table-striped table-sm">
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