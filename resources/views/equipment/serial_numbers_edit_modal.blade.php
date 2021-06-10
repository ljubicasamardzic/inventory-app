<div class="modal fade show" id="modal-serial-numbers-edit" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" id="serial_numbers_edit_form">
            @csrf
            @method('PUT');
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit a serial number</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" id="sn_id">
                    <input type="text" name="serial_number" id="sn_num" class="form-control">
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

