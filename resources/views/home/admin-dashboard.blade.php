<div class="row">
    <div class="col-12">
        @can('viewAny', \App\Models\Equipment::class)
            @include('home.available_items_search')
        @endcan
        
        @include('home.requests-table')
        
        @if (auth()->user()->isSuperAdmin() || auth()->user()->isSupportOfficer() || auth()->user()->isHR())
            @include('home.repair-requests-table')
        @endif
       
    </div>
</div>