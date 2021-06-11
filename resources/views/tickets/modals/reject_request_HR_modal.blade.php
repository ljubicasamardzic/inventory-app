<div class="modal fade show" id="reject_request_HR_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets/update3/{{ $ticket->id }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Reject request</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <input type="hidden" name="id" value="{{ $ticket->id }}">
                    <input type="hidden" name="HR_approval" value="{{ App\Models\Ticket::REJECTED }}">
                    <input type="hidden" name="HR_id" value="{{ auth()->id() }}">
                    <div>
                        <p>Are you sure you want to reject this request?</p>
                        <p>Please note that this decision is final and cannot be overturned.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary">Reject request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

