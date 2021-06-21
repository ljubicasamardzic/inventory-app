<div class="row">
  <div class="col-12">
    @include('home.assigned_equipment')
  </div>
</div>  

<div class="row">
  <div class="col-12">
      @include('home.requests-table')
  </div>
</div>

@include('home/modals.modal-malfunction')
@include('home/modals.modal-equipment')

