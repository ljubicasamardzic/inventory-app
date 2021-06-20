<form action="/" method="GET">
    <div class="row d-flex flex-row justify-content-end mb-3 mx-2">
        @if (auth()->user()->isAdmin())
            <div class="col-3">
                <input type="text" name="search_text" placeholder="Search by employee or officer" class="form-control" value="{{ request('search_text') }}">
            </div>      
        @endif
        <div class="col-3">
            <select name="search_status_id" class="form-control">
                <option value="">-- ticket status --</option>
                @foreach ($ticket_statuses as $status)
                    <option value="{{ $status->id }}" {{ request('search_status_id') == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        @if (auth()->user()->isAdmin())
            <div class="col-1 d-flex flex-row align-items-center">
                <div class="form-check">
                    <input class="form-check-input" 
                            name="search_checkbox" 
                            type="checkbox" 
                            value={{ auth()->user()->id }}
                            {{ request('search_checkbox') != null ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="defaultCheck1">
                        Mine
                    </label>
                </div>
            </div>            
        @endif
        <div class="col-2">
            <button class="btn btn-dark w-100">
                Search
                <i class="fas fa-search ml-1"></i>
            </button>
        </div>
    </div>
</form>