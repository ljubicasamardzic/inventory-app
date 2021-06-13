<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Officer
                </h3>
                {{-- <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div> --}}
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <tr>
                            <td>Name:</td>
                            @if ($ticket->officer != null)
                                <td>{{ $ticket->officer->name }}</td>
                            @else <td>/</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Approval status:</td>
                            <td><span class="badge {{ $ticket->officerApproval->icon }}">{{ $ticket->officerApproval->name }}</span></td>
                        </tr>
                        @if ($ticket->officerApproval->id == App\Models\Ticket::APPROVED) 
                            <tr>
                                <td>Price:</td>
                                @if ($ticket->price)
                                    <td>{{ $ticket->price }}</td>
                                @else
                                    <td>/</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Deadline:</td>
                                @if ($ticket->deadline != null)
                                    <td>{{ $ticket->formatted_deadline }}</td>
                                @else <td>/</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Remarks:</td>
                                @if ($ticket->officer_remarks != null)
                                    <td>{{ $ticket->officer_remarks }}</td>
                                @else <td>/</td>
                            @endif
                            </tr>
                        @endif
                    </table>    
                </div>
            </div><!-- /.card-body -->
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    HR
                </h3>        
                {{-- <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div> --}}
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <tr>
                            <td>Name:</td>
                            @if ($ticket->HR != null)
                                <td>{{ $ticket->HR->name }}</td>
                            @else <td>/</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Approval status:</td>
                            <td><span class="badge {{ $ticket->HRApproval->icon }}">{{ $ticket->HRApproval->name }}</span></td>
                        </tr>
                        @if ($ticket->HR_approval != App\Models\Ticket::PENDING)
                            <tr>
                                <td>Remarks:</td>
                                @if ($ticket->HR_remarks != null)
                                    <td>{{ $ticket->HR_remarks }}</td>
                                    @else <td>/</td>
                                @endif
                            </tr>
                        @endif
                    </table>    
                </div> 
            </div><!-- /.card-body -->
        </div>
    </div>
</div>

