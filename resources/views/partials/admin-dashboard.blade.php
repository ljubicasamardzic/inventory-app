<div class="row">
    <div class="col-12">
        @can('viewAny', \App\Models\Equipment::class)
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-laptop-code mr-2"></i>
                        Available equipment by category
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body table-responsive">

                    @foreach($categories as $category)
                        <div class="card card-default collapsed-card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ $category->name }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Qty. available</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($category->equipment as $e)
                                        <tr class="clickable-row" data-href="/equipment/{{ $e->id }}" >
                                            <td>{{ $e->id }}</td>
                                            <td>{{ $e->name }}</td>
                                            <td>{{ $e->available_quantity }}</td>
                                            <td>{{ $e->short_description }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                </div><!-- /.card-body -->
            </div>
        @endcan
        <!-- /.card -->
        <div class="card mt-4" id="open_requests_id">
            <div class="card-header border-transparent">
              <h3 class="card-title">
                <i class="fas fa-clipboard-list mr-2"></i>
                Open requests</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover m-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Request type</th>
                        <th >Employee</th>
                        <th>Admin</th>
                        <th>Date of request</th>
                        <th>Officer Approval</th>
                        <th>HR approval</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($tickets != null)
                        @foreach ($tickets as $key => $ticket)
                            <tr class="clickable-row" data-href="/tickets/{{ $ticket->id }}">
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if ($ticket->isNewEquipmentRequest()) New equipment 
                                    @elseif ($ticket->isSuppliesRequest()) Office supplies 
                                    @elseif ($ticket->isRepairRequest()) Repair equipment
                                    @endif
                                </td>
                                <td>{{ $ticket->user->name }}</td>
                                    @if ($ticket->officer != null)
                                        <td>{{ $ticket->officer->name }}</td>
                                    @else <td>/</td>
                                    @endif
                                <td>{{ $ticket->date }}</td>
                                <td><span class="badge {{ $ticket->officerApproval->icon }}">{{ $ticket->officerApproval->name }}</span></td>
                                <td><span class="badge {{ $ticket->HRApproval->icon }}">{{ $ticket->HRApproval->name }}</span></td>
                                <td><span class="badge {{ $ticket->status->icon }}">{{ $ticket->status->name }}</span></td>
                            </tr>
                        @endforeach
                    @endif
                  
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.card-footer -->
          </div>
    </div>
</div>