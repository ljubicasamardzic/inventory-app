<div class="card mt-4" id="open_requests_id">
    <div class="card-header border-transparent">
        <h3 class="card-title">
            <i class="fas fa-clipboard-list mr-2"></i>
            @if (auth()->user()->isEmployee())
                My requests
            @else All requests
            @endif
        </h3>
    </div>
     <!-- /.card-header -->
    <div class="card-body p-0">
        <div class="table-responsive">
            @include('home.search-all')
          <table class="table table-hover m-0" id="requests_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Request type</th>
                        @if (auth()->user()->isAdmin())
                        <th>Employee</th>
                        <th>Officer</th>
                        @endif
                        <th>Date of request</th>
                        <th>Date finished</th>
                        @if (auth()->user()->isAdmin())
                        <th>Officer Approval</th>
                        <th>HR approval</th>
                        @endif
                        <th>Status</th>
                    </tr>
                </thead>
              <tbody>
              @if ($tickets->count() > 0)
                  @foreach ($tickets as $key => $ticket)
                      <tr class="clickable-row" data-href="/tickets/{{ $ticket->id }}">
                        <td>{{ (($tickets::resolveCurrentPage() - 1) * App\Models\Ticket::PER_PAGE)  + $key + 1 }}</td>
                        <td>
                              @if ($ticket->isNewEquipmentRequest()) New equipment 
                              @elseif ($ticket->isSuppliesRequest()) Office supplies 
                              @elseif ($ticket->isRepairRequest()) Repair equipment
                              @endif
                          </td>
                          @if (auth()->user()->isAdmin())
                            <td>{{ $ticket->user->name }}</td>
                            <td>
                                @if ($ticket->officer != null)
                                    {{ $ticket->officer->name }}
                                @else /
                                @endif
                            </td>
                          @endif
                          <td>
                              @if ($ticket->created_at != null)
                              {{ $ticket->date }}
                              @else /
                              @endif
                          </td>
                          <td>
                              @if ($ticket->date_finished != null)
                                  {{ $ticket->finished_date }}
                              @else /
                              @endif
                          </td>
                          @if (auth()->user()->isAdmin())
                            <td><span class="badge {{ $ticket->officerApproval->icon }}">{{ $ticket->officerApproval->name }}</span></td>
                            <td><span class="badge {{ $ticket->HRApproval->icon }}">{{ $ticket->HRApproval->name }}</span></td> 
                          @endif
                          <td><span class="badge {{ $ticket->status->icon }}">{{ $ticket->status->name }}</span></td>
                      </tr>
                  @endforeach
              @else <tr>
                        <td></td>
                        <td>There are no requests at this time.</td>
                    </tr> 
              @endif
            </tbody>
          </table>
          <div class="d-flex flex-row justify-content-center mt-3">
              {{ $tickets->appends(request()->except('page'))->links() }}
          </div>
        </div>
        <!-- /.table-responsive -->
    </div>
      <!-- /.card-footer -->
</div>