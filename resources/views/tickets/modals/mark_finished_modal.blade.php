<div class="modal fade show" id="mark_finished_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets/update4/{{ $ticket->id }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Mark request as finished</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" value="{{ $ticket->id }}">
                    <input type="hidden" name="status_id" value="{{ App\Models\Ticket::PROCESSED }}">
                    <label for="date_finished">Date finished:</label>
                    <input type="date" name="date_finished" class="form-control">
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary">Accept changes</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

