<div class="modal fade show" id="edit_equipment_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form>
            {{-- @csrf
            @method('PUT') --}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Request equipment</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="ticket_type" value="1" id="ticket_type_edit_equipment">
                    <input type="hidden" id="token_edit_equipment" name="_token" value="{{ csrf_token() }}">
                    <div class="col-12">
                        <select name="ticket_request_type" class="form-control" id="ticket_request_type_id">
                            <option value="">-- select request type --</option>
                            <option value="1">Equipment request</option>
                            <option value="2">Office supplies request</option>
                        </select> 
                    </div>
                    <div class="col-12 mt-3 mb-3 d-none" id="supplies_div">
                            <input type="text" 
                                    class="form-control" 
                                    name="description_supplies" 
                                    id="supplies_desc_edit_equipment" 
                                    placeholder="Explain what you need" 
                                    @if ($ticket->description_supplies != null)
                                        value="{{ $ticket->description_supplies }}"
                                    @endif
                            >
                            <input type="number" 
                            @if ($ticket->quantity != null)
                                value="{{ $ticket->quantity }}"
                            @endif
                                    class="form-control mt-3" 
                                    name="quantity" 
                                    id="supplies_quantity_edit_equipment" 
                                    placeholder="Quantity"
                            >
                    </div>
                    <div class="col-12 mt-3 d-none" id="equipment_div">
                        <select name="equipment_category_id" id="equipment_category_id_edit" class="form-control">
                            <option value="">-- select equipment --</option>                            
                                @foreach ($equipment_categories as $category)
                                    <option {{ ($ticket->equipment_category_id != null && $ticket->equipment_category_id == $category->id) ? 'selected' : '' }} value={{ $category->id }}>{{$category->name}}</option>
                                @endforeach
                        </select> 
                        <textarea name="description_equipment" id="description_equipment_edit" placeholder="Additional remarks" cols="30" class="form-control mt-3" rows="3">{{ $ticket->description_equipment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                    <button type="submit"
                            class="btn btn-primary" 
                            id="submit_btn_edit_equipment"
                            data-id="{{ $ticket->id }}"
                    >
                        Send request
                    </button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

