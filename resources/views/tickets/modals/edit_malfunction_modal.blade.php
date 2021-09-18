<div class="modal fade show" id="edit_malfunction_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Report equipment malfunction</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="ticket_type" value="2" id="ticket_type_edit_malfunction">
                    <input type="hidden" name="ticket_request_type" value="1" id="ticket_request_edit_malfunction">
                    <input type="hidden" id="token_edit_malfunction" name="_token" value="{{ csrf_token() }}">
                    <div class="col-12">
                        <select name="document_item_id" class="form-control" id="document_item_edit_malfunction">
                            @if ($equipment != null)
                                @foreach ($equipment as $item)
                                <option value="{{ $item->id }}" {{ ($ticket->document_item != null && $ticket->document_item->id == $item->id) ? 'selected' : '' }}>
                                    {{ $item->equipment->full_name }} 
                                    @if ($item->serial_number != null)
                                        ({{$item->serial_number->serial_number}})
                                    @endif
                                </option>
                                @endforeach
                            @endif
                        </select>
                        <textarea name="description_malfunction" 
                                    id="description_malfunction_edit"
                                    placeholder="Explain equipment malfunction" 
                                    cols="30" 
                                    class="form-control mt-3" 
                                    rows="3">{{$ticket->description_malfunction}}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                    <button type="submit" data-id="{{ $ticket->id }}" class="btn btn-primary" id="submit_btn_edit_malfunction">Send request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

