<div class="modal fade show" id="modal-report-malfunction" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets">
            {{-- @csrf --}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Report equipment malfunction</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" id="token_repair_request" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="ticket_type" value="2" id="ticket_type_repair">
                    <input type="hidden" name="ticket_request_type" value="1" id="ticket_request_type_repair">
                    <div class="col-12">
                        <select name="document_item_id" class="form-control" id="document_item_id">
                            <option value="">-- select equipment --</option>
                            @foreach ($equipment as $item)
                            {{-- sending document id so that we can access both the equipment id and serial num in the ticket --}}
                                <option value="{{ $item->id }}">
                                    {{ $item->equipment->full_name }} 
                                    @if ($item->serial_number != null)
                                        ({{ $item->serial_number->serial_number }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <textarea name="description_malfunction" id="description_malfunction" placeholder="Explain equipment malfunction" cols="30" class="form-control mt-3" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary" id="submit_btn_repair_equipment">Send request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

