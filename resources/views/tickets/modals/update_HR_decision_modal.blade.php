<div class="modal fade show" id="update_HR_decision" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="/tickets/update_HR_decision/{{ $ticket->id }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Update request</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </a>
                </div>
                <div class="row modal-body" id="modal-body">
                    <select name="HR_approval" 
                        class="form-control" 
                >
                    <option value="{{App\Models\Ticket::APPROVED}}"
                        {{ ($ticket->HR_approval == App\Models\Ticket::APPROVED) ? 'selected' : ''}}
                    >
                        Approve
                    </option>
                    <option value="{{App\Models\Ticket::REJECTED}}"
                        {{ ($ticket->HR_approval == App\Models\Ticket::REJECTED) ? 'selected' : ''}}
                    >
                        Reject
                    </option>
                </select>
                    <label for="HR_remarks">Remarks:</label>
                    <textarea name="HR_remarks" cols="30" rows="5" class="form-control">{{ $ticket->HR_remarks }}</textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

