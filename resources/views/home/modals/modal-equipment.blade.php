<div class="modal fade show" id="modal-equipment" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Request equipment</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="ticket_type" value="1">
                    <div class="col-12">
                        <select name="ticket_request_type" class="form-control @error('ticket_request_type') is-invalid @enderror" id="ticket_request_type_id">
                            <option value="">-- select request type --</option>
                            <option value="1">Equipment request</option>
                            <option value="2">Office supplies request</option>
                        </select> 
                        @error('ticket_request_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mt-3 mb-3 d-none" id="supplies_div">
                            <input type="text" class="form-control" name="description_supplies" id="supplies_desc" placeholder="Explain what you need">
                            <input type="number" 
                                    class="form-control mt-3 @error('description_supplies') is-invalid @enderror" 
                                    name="quantity" 
                                    id="supplies_quantity" 
                                    placeholder="Quantity">
                            @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                    </div>
                    <div class="col-12 mt-3 d-none" id="equipment_div">
                        <select name="equipment_category_id" id="equipment_category_id" class="form-control @error('equipment_category_id') is-invalid @enderror">
                            <option value="">-- select equipment --</option>
                            @if ($equipment_categories)
                                @foreach ($equipment_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select> 
                        @error('equipment_category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        <textarea name="description_equipment" id="equipment_desc" placeholder="Additional remarks" cols="30" class="form-control mt-3" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary" id="submit_btn">Send request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

