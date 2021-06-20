<div class="modal fade show" id="mark_finished_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets/update4/{{ $ticket->id }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                {{-- <h4 class="modal-title">Mark request as finished</h4> --}}
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" value="{{ $ticket->id }}">
                    <input type="hidden" name="status_id" value="{{ App\Models\Ticket::PROCESSED }}">

                    @if ($ticket->status_id == App\Models\Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == App\Models\Ticket::APPROVED || $ticket->isNewEquipmentRequest() && $ticket->officer_approval == App\Models\Ticket::REJECTED && $ticket->HR_approval == App\Models\Ticket::APPROVED)
                        @if ($available_equipment == '[]')
                        <div class="p-2">                            
                            <p>No equipment is yet available for this request so it cannot be marked as finished.</p>
                            <p>Please try again when the equipment arrives.</p>
                        </div>
                        @else 
                            <label for="">Assign equipment:</label>
                            <select class="form-control" name="equipment_id" id="equipment_select" onchange="availableSerialNums()">
                                <option value="">-- Select available equipment --</option>
                                @if ($available_equipment != '[]')
                                    @foreach($available_equipment as $e)
                                    <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="serial_number_select">Serial number:</label>
                            <select name="serial_number_id" id="serial_number_select" class="form-control">
                                {{-- populated by AJAX function --}}
                            </select>

                            <label for="date_finished">Date finished:</label>
                            <input type="date" name="date_finished" class="form-control">
                        @endif
                    @elseif ($ticket->isSuppliesRequest() || $ticket->isRepairRequest() || $ticket->status_id == App\Models\Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == App\Models\Ticket::REJECTED)
                        <label for="date_finished">Date finished:</label>
                        <input type="date" name="date_finished" class="form-control">
                        @if ($ticket->HR_approval == App\Models\Ticket::REJECTED)
                            <textarea name="final_remarks" class="form-control mt-3" placeholder="Explain to the employee why the request was denied" cols="30" rows="5"></textarea>       
                        @endif
                    @elseif ($ticket->isNewEquipmentRequest() && $ticket->status_id == App\Models\Ticket::IN_PROGRESS)
                    <label for="serial_number_select">Assign serial number:</label>
                    <select name="serial_number_id" id="serial_number_select" class="form-control">
                        @if ($ticket->equipment != null && $ticket->equipment->serial_numbers != null)
                            <option value="">-- available serial numbers --</option>
                            @foreach ($ticket->equipment->serial_numbers as $sn)
                                @if (!$sn->is_used)
                                    <option value="{{ $sn->id }}">{{ $sn->serial_number }}</option>   
                                @endif
                            @endforeach
                        @endif
                    </select>
                    <label for="date_finished">Date finished:</label>
                    <input type="date" name="date_finished" class="form-control">
                    @endif

                    
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="submit" 
                            class="btn btn-primary  
                            @if ($ticket->status_id == App\Models\Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == App\Models\Ticket::APPROVED && $available_equipment == '[]') disabled @endif"
                    >
                        Accept changes
                    </button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

