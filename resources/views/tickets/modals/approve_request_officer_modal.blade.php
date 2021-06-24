<div class="modal fade show" id="approve_request_officer_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets/update2/{{ $ticket->id }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Approve request</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" id="ticket_id_approve_officer" value="{{ $ticket->id }}">
                    <input type="hidden" name="officer_approval" id="officer_approval_value" value="{{ App\Models\Ticket::APPROVED }}">
                    <input type="hidden" id="token_approve_officer" name="_token" value="{{ csrf_token() }}">

                    @if ($ticket->isNewItemsRequest() && $ticket->isEquipmentRequest())
                        <label for="">Assign equipment:</label>
                        <select class="form-control @error('equipment_id') is-invalid @enderror" 
                                name="equipment_id" 
                                id="equipment_select" 
                                {{-- onchange="availableSerialNums()" --}}
                        >
                            <option value="">-- Select available equipment --</option>
                            @if ($available_equipment != '[]')
                                @foreach($available_equipment as $e)
                                <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                                @endforeach
                            @else
                                <option value="0">Order new equipment</option>
                            @endif
                        </select>
                        @error('equipment_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>                        
                        @enderror
                    @endif
                    
                    <label for="deadline">Delivery deadline:</label>
                    <input type="date" name="deadline" id="deadline_approve_officer" class="form-control @error('deadline') is-invalid @enderror">
                    @error('deadline')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>                        
                    @enderror
                    <label for="price">Price (€):</label>
                    <input type="number" min=0 step=0.01 name="price" id="price_approve_officer" class="form-control @error('deadline') is-invalid @enderror">
                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>                        
                    @enderror
                    <label for="officer_remarks">Remarks:</label>
                    <textarea name="officer_remarks" id="officer_remarks_approve" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="button" class="btn btn-primary" id="approve_button_officer">Approve request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

