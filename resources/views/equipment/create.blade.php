@extends('layouts.main')

@section('page_title', 'Add New Equipment')

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        New Equipment
                    </h3>

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <form action="/equipment" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <label for="catogory_select">Equipment category:</label>
                                <select name="equipment_category_id" id="catogory_select" class="form-control @error('equipment_category_id') is-invalid @endif">
                                    <option value="">- select a category -</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('equipment_category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label for="position_select">Name:</label>
                                <input type="text" name="name" id="name_input" class="form-control @error('name') is-invalid @endif" placeholder="Enter a name" />
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label for="available_quantity_input">Available quantity:</label>
                                <input type="number" step="1" min="0" name="available_quantity" class="form-control  @error('available_quantity') is-invalid @endif " placeholder="Enter quantity" id="available_quantity_input">
                                @error('available_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="description_txt">Description:</label>
                                <textarea name="description" id="description_txt" class="form-control" placeholder="Enter equipment description" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 offset-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat mt-4">
                                    SAVE EQUIPMENT DETAILS
                                </button>
                            </div>
                        </div>
                    </form>

                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

@endsection
@section('additional_scripts')
    <script src="{{ asset('js/equipment/create.js') }}"></script>
@endsection
