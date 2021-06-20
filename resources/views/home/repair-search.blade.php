<form action="/" method="GET">
    <div class="row d-flex flex-row justify-content-end mb-3 mx-2">
        <div class="col-3">
            <input type="text" name="equipment_search" placeholder="Search by equipment name" class="form-control" value="{{ request('equipment_search') }}">
        </div>      
        <div class="col-2">
            <button class="btn btn-dark w-100">
                Search
                <i class="fas fa-search ml-1"></i>
            </button>
        </div>
    </div>
</form>