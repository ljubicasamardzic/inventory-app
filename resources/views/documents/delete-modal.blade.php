<div class="modal fade show" id="delete-doc-modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <p>By deleting a document, you also delete any document items and tickets related to it.</p>
            <p>Are you sure?</p>
        </div>
        <div class="modal-footer justify-content-between">
            <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
            <form method="POST" id="delete-doc-form">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-primary">Save changes</button>
              </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


