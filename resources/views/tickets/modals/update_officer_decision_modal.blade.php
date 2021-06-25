<div class="modal fade show" id="update_officer_decision" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets/update-officer-decision/{{ $ticket->id }}">
            {{-- called by ajax --}}
            {{-- @csrf
            @method('PUT') --}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit decision</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" id="id_edit_officer" value="{{ $ticket->id }}">
                    <input type="hidden" id="token_edit_officer" name="_token" value="{{ csrf_token() }}">
                    <div class="col-12">
                        <select name="officer_approval" 
                                class="form-control" 
                                id="officer_approval_select"
                                onchange="officerEditDisplay({{$ticket->ticket_type}}, {{$ticket->ticket_request_type}}, true)"
                        >
                            <option value="{{App\Models\Ticket::APPROVED}}"
                                {{ ($ticket->officer_approval == App\Models\Ticket::APPROVED) ? 'selected' : ''}}
                            >
                                Approve
                            </option>
                            <option value="{{App\Models\Ticket::REJECTED}}"
                                {{ ($ticket->officer_approval == App\Models\Ticket::REJECTED) ? 'selected' : ''}}
                            >
                                Reject
                            </option>
                        </select>
                    </div>

                    @if ($ticket->isNewEquipmentRequest())
                        {{-- for approving new equipment --}}
                        <div class="col-12 d-none" id="equipment-div-officer-edit">
                            <label for="">Assign equipment:</label>
                            <select class="form-control" name="equipment_id" id="equipment_select_update_officer">
                                {{-- <option value="">-- Select available equipment --</option> --}}
                                @if ($available_equipment != '[]')
                                    @foreach($available_equipment as $e)
                                    <option value="{{ $e->id }}"
                                        {{ ($ticket->equipment_id == $e->id) ? 'selected' : '' }}
                                    >
                                        {{ $e->full_name }}
                                    </option>
                                    @endforeach
                                @else
                                    <option value=""
                                    {{ ($ticket->equipment_id == null) ? 'selected' : ''}}
                                    >
                                        Order new equipment
                                    </option>
                                @endif
                            </select>
                        </div>
                    @endif
                    
                    {{-- for equipment, repair and new supplies approve --}}
                        <div class="col-12 d-none" id="details-div-officer-edit">
                            <label for="deadline">Delivery deadline:</label>
                            <input type="date" 
                                    name="deadline" 
                                    class="form-control" 
                                    id="deadline_edit_officer"
                                    @if ($ticket->deadline != null)
                                    value="{{ $ticket->deadline->format('Y-m-d') }}"
                                    @endif 
                            >
                            <label for="price">Price (€):</label>
                            <input type="number" name="price" id="price_edit_officer" class="form-control" value="{{ $ticket->price }}">
                        </div>

                    {{-- display for both approve and reject --}}
                    <div class="col-12">
                        <label for="officer_remarks">Remarks:</label>
                        <textarea name="officer_remarks" id="officer_remarks_edit" cols="30" rows="5" class="form-control">{{ $ticket->officer_remarks }}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submit_btn_update_officer">Save changes</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

