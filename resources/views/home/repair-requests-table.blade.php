<div class="card mt-4" id="repair-requests-processed">
    <div class="card-header border-transparent">
        <h3 class="card-title">
            <i class="fas fa-clipboard-list mr-2"></i>
                Undertaken equipment repair        
        </h3>
    </div>
     <!-- /.card-header -->
    <div class="card-body p-0">
        <div class="table-responsive">
            @include('home.repair-search')
          <table class="table table-hover m-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Officer</th>
                        <th>Equipment name</th>
                        <th>Serial number</th>
                        <th>Price (€)</th>
                        <th>Officer remarks</th>
                        <th>Date finished</th>
                    </tr>
                </thead>
              <tbody>
              @if ($processed_repair_tickets->count() > 0)
                  @foreach ($processed_repair_tickets as $key => $ticket)
                      <tr class="clickable-row" data-href="/tickets/{{ $ticket->id }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>
                                @if ($ticket->officer != null)
                                    {{ $ticket->officer->name }}
                                @else /
                                @endif
                            </td>
                            <td>{{ $ticket->document_item->equipment->full_name }}</td>
                            <td>
                                @if ($ticket->document_item->serial_number != null)
                                    {{ $ticket->document_item->serial_number->serial_number }}
                                    @else /
                                @endif
                            </td>
                            <td>{{ $ticket->price }}</td>
                            <td>{{ Str::limit($ticket->officer_remarks, 50) }}</td>
                            <td>
                                @if ($ticket->date_finished != null)
                                    {{ $ticket->finished_date }}
                                @else /
                                @endif
                            </td>
                            <td></td>
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