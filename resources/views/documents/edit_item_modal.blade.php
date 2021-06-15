<div class="modal fade in" id="edit_item_modal">
    <div class="modal-dialog">
        <form method="POST" action="/document-item/change-serial-number">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <label for="serial_number_select">Serial number:</label>
                            <input type="hidden" name="id" id="relevant_document_item_id">
                            <input type="hidden" id="chosen_equipment_id">
                            <input type="hidden" id="chosen_serial_num_id">
                            <select name="serial_number_id" id="serial_number_select_2" class="form-control">
                                <option value="">-- Choose a serial number --</option>
                                {{-- populated by AJAX --}}
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

