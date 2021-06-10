<div class="row">
  <div class="col-12">    
    <div class="mt-3 float-right">
      <a type="button" 
          class="btn btn-sm btn-warning  @if ($equipment->count() < 1) disabled @endif"
          data-toggle="modal" 
          data-target="#modal-report-malfunction" 
      >
        Report equipment malfunction
      </a>
      <a type="button" 
          class="btn btn-sm btn-primary"
          data-toggle="modal" 
          data-target="#modal-equipment" 
      >
        Request equipment 
      </a>
    </div>  
  </div>

  <div class="col-12">
    <div class="card mt-4">
      <div class="card-header">
          <h3 class="card-title">
              <i class="fas fa-laptop-code mr-1"></i>
              Assigned Equipment
          </h3>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed text-nowrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Equipment</th>
              <th>Serial No.</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
                @if ($equipment->count() > 0)
                  @foreach ($equipment as $key => $val)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $val->equipment->full_name }}</td>
                        @if($val->serial_number)
                            <td>{{ $val->serial_number->serial_number }}</td>
                        @else <td></td>
                        @endif
                        <td>{{ $val->document->date_formated }}</td>
                      </tr>
                  @endforeach
                  @else 
                  <tr>
                    <td></td>
                    <td>No equipment has yet been assigned to you.</td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endif
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>

  @include('home-modals.modal-malfunction')
  @include('home-modals.modal-equipment')
</div>
