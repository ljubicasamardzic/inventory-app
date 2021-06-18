<div class="row">
    <div class="col-6 table-responsive">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ticket details</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm">
                    @if (auth()->user()->isAdmin())
                        <tr>
                            <td>ID</td>
                            <td>{{ $ticket->id }}</td>
                        </tr>  
                    @endif
                    <tr>
                        <td>Request Type:</td>
                        <td>
                            @if ($ticket->isNewItemsRequest()) New Equipment
                            @elseif ($ticket->isSuppliesRequest()) Office supplies
                            @elseif ($ticket->isRepairRequest()) Repair request
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Employee:</td>
                        <td>
                            @if (auth()->user()->isAdmin())  
                                <a href="/users/{{ $ticket->user->id }}">
                                    {{ $ticket->user->name }}
                                </a>
                            @else  {{ $ticket->user->name }}
                            @endif
                        </td>
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
                                    @if ($ticket->serial_number != null)
                                        <td>{{ $ticket->serial_number->serial_number }}</td>
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
            <div class="card-header">
                <h3 class="card-title">Actions taken</h3>
            </div>
            @if ($ticket->status_id == App\Models\Ticket::PROCESSED)
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <tr>
                            <td>Final status:</td>
                            <td>
                                <span class="badge {{ $ticket->HRApproval->icon }}">{{ $ticket->HRApproval->name }}</span>
                                <span class="badge {{ $ticket->status->icon }}">{{ $ticket->status->name }}</span>
                            </td>
                        </tr>
                        @if ($ticket->document_id != null)
                            <tr>
                                <td>Document ID:</td>
                                <td>{{ $ticket->document_id }}</td>
                            </tr>
                            <tr>
                                <td>Assigned Equipment:</td>
                                <td>
                                    @if (auth()->user()->isAdmin())
                                        <a href="/documents/{{ $ticket->document_id }}">
                                            {{ $ticket->equipment->full_name }}
                                        </a>
                                    @elseif (auth()->user()->isEmployee())
                                        {{ $ticket->equipment->full_name }}
                                    @endif
                                </td>
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
                        @if ($ticket->final_remarks != null)
                        <tr>
                            <td>Final remarks:</td>
                            <td>{{ $ticket->final_remarks }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            @endif
        </div>
    </div>
    
</div>