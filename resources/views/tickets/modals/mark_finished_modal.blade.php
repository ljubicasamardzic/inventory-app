<div class="modal fade show" id="mark_finished_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST">
            {{-- submitted via ajax --}}
            <div class="modal-content">
                <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" id="id_mark_finished" value="{{ $ticket->id }}">
                    <input type="hidden" id="token_mark_finished" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="status_id" id="status_id_mark_finished" value="{{ App\Models\Ticket::PROCESSED }}">
                    @if ($ticket->status_id == App\Models\Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == App\Models\Ticket::APPROVED || $ticket->isNewEquipmentRequest() && $ticket->officer_approval == App\Models\Ticket::REJECTED && $ticket->HR_approval == App\Models\Ticket::APPROVED)
                        @if ($available_equipment == '[]')
                        <div class="p-2">                            
                            <p>No equipment is yet available for this request so it cannot be marked as finished.</p>
                            <p>Please try again when the equipment arrives.</p>
                        </div>
                        @else 
                            <label for="">Assign equipment:</label>
                            <select class="form-control" 
                                    name="equipment_id" 
                                    id="equipment_select1" 
                                    onchange="availableSerialNums('equipment_select1', 'serial_number_select1')"
                            >
                                <option value="">-- Select available equipment --</option>
                                @if ($available_equipment != '[]')
                                    @foreach($available_equipment as $e)
                                    <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="serial_number_select">Serial number:</label>
                            <select name="serial_number_id" id="serial_number_select1" class="form-control">
                                {{-- populated by AJAX function --}}
                            </select>
                        @endif

                    @elseif ($ticket->isSuppliesRequest() || $ticket->isRepairRequest() || $ticket->isNewEquipmentRequest())
                       
                        @if ($ticket->HR_approval == App\Models\Ticket::REJECTED)
                            <textarea name="final_remarks" id="final_remarks_mark_finished" class="form-control mt-3" placeholder="Explain to the employee why the request was denied" cols="30" rows="5"></textarea>       
                        @endif
                        
                    @elseif ($ticket->isNewEquipmentRequest() && $ticket->equipment_id != null && $ticket->HR_approval == App\Models\Ticket::APPROVED)
                        <label for="serial_number_select">Assign serial number:</label>
                        <select name="serial_number_id" id="serial_number_select2" class="form-control" @if($ticket->equipment->serial_numbers->count() == 0) disabled @endif>
                            @if ($ticket->equipment != null && $ticket->equipment->serial_numbers->count() > 0)
                                <option value="">-- available serial numbers --</option>
                                @foreach ($ticket->equipment->serial_numbers as $sn)
                                    @if (!$sn->is_used)
                                        <option value="{{ $sn->id }}">{{ $sn->serial_number }}</option>   
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    @endif
                    {{-- if waiting for equipment and no equipment to assign atm, then don't show the date --}}
                    @if ($ticket->status_id == App\Models\Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == App\Models\Ticket::APPROVED && $available_equipment == '[]')
                    @else 
                        <label for="date_finished">Date finished:</label>
                        <input type="date" 
                            name="date_finished" 
                            id="date_finished_mark_finished" 
                            class="form-control"
                        >
                    @endif
                    </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="submit" 
                            id="btn_submit_mark_finished"
                            class="btn btn-primary 
                            @if ($ticket->status_id == App\Models\Ticket::WAITING_FOR_EQUIPMENT && $ticket->HR_approval == App\Models\Ticket::APPROVED && $available_equipment->count() == 0) disabled @endif"
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

