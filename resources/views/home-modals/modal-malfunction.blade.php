<div class="modal fade show" id="modal-report-malfunction" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Report equipment malfunction</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="ticket_type" value="2">
                    <input type="hidden" name="ticket_request_type" value="1">
                    <div class="col-12">
                        <select name="equipment_id" class="form-control  @error('equipment_id') is-invalid @enderror" id="equipment_id">
                            <option value="">-- select equipment --</option>
                            @foreach ($equipment as $item)
                                <option value="{{ $item->equipment_id }}">{{ $item->equipment->full_name }}</option>
                            @endforeach
                        </select>
                        @error('equipment_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>                        
                        @enderror         
                        <textarea name="description_malfunction" placeholder="Explain equipment malfunction" cols="30" class="form-control mt-3" rows="3"></textarea>
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

