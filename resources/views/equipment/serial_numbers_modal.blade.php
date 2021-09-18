<div class="modal fade show" id="modal-serial-numbers" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/serial-numbers" id="serial_numbers_form">
            {{-- @csrf --}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Add serial numbers</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" id="token_serial_numbers" value="{{ csrf_token() }}">
                        <input type="hidden" id="equipment_id" name="equipment_id" value="{{ $equipment->id }}">
                        @for ($i = 0; $i < $equipment->required_input_fields; $i++)
                            <div class="col-1">
                                <td>{{ $i + 1 }}.</td>
                            </div>
                            <div class="col-11">
                                <input type="text" name="serial_numbers[]" class="form-control mb-1">
                            </div>
                        @endfor
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary" id="submit_btn">Save changes</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

