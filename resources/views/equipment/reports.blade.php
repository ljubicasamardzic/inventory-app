@extends('layouts.main')

@section('page_title', 'Reports')

@section('additional_styles')
    <style>
        .clickable-row{ cursor: pointer; }
        .select2-results__option.select2-results__option--selectable.select2-results__option--highlighted  {
            background-color: #007bff
        }
        .tab-content .tab-pane {
            height: 15vh;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">Report by department</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">Report by positions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-tab" data-toggle="pill" href="#custom-tabs-three" role="tab" aria-controls="custom-tabs-three" aria-selected="false">Report by categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-tab" data-toggle="pill" href="#custom-tabs-four" role="tab" aria-controls="custom-tabs-four" aria-selected="false">Report by employees</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-five-tab" data-toggle="pill" href="#custom-tabs-five" role="tab" aria-controls="custom-tabs-five" aria-selected="false">Available equipment</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body mt-2">
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                            <div class="row">
                                <div class="col-9">
                                        <form action="/reports/department" method="POST">
                                        @csrf
                                        <select class="department_multiple_slt m-0" name="department_ids[]" multiple="multiple" style="width: 100%">
                                            <optgroup label="Departments">
                                                @foreach ($departments as $department)
                                                    <option value="{{$department->id}}">{{ $department->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-primary" type="submit" style="width: 100%">
                                                Get report
                                                <i class="fas fa-download ml-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two" role="tabpanel" aria-labelledby="custom-tabs-two-tab">
                            <div class="row">
                                <div class="col-9">
                                    <form action="/reports/position" method="POST">
                                        @csrf
                                        <select class="positions_multiple_slt m-0" name="position_ids[]" multiple="multiple" style="width: 100%">
                                            @foreach ($departments as $department)
                                                <optgroup label="{{ $department->name }}">
                                                    @foreach ($department->positions as $position)
                                                        <option value="{{$position->id}}">{{ $position->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                                @endforeach
                                        </select>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-primary" type="submit" style="width: 100%">
                                                Get report
                                                <i class="fas fa-download ml-1"></i>
                                            </button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three" role="tabpanel" aria-labelledby="custom-tabs-three-tab">
                            <div class="row">
                                <div class="col-9">
                                    <form action="/reports/category" method="POST">
                                        @csrf
                                        <select class="categories_multiple_slt m-0" name="category_ids[]" multiple="multiple" style="width: 100%">
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-primary" type="submit" style="width: 100%">
                                                Get report
                                                <i class="fas fa-download ml-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>        
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four" role="tabpanel" aria-labelledby="custom-tabs-four-tab">
                            <div class="row">
                                <div class="col-9">
                                    <form action="/reports/employee" method="POST">
                                        @csrf
                                        <select class="employees_multiple_slt m-0" name="employee_ids[]" multiple="multiple" style="width: 100%">
                                            @foreach ($departments as $department)
                                                <optgroup label="{{ $department->name }}">
                                                    @foreach ($department->users as $user)
                                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-primary" type="submit" style="width: 100%">
                                                Get report
                                                <i class="fas fa-download ml-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>        
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-five" role="tabpanel" aria-labelledby="custom-tabs-five-tab">
                            <div class="row">
                                <div class="col-9">
                                    <form action="/reports/available-equipment" method="POST">
                                        @csrf
                                        <select class="equipment_multiple_slt m-0" name="equipment_ids[]" multiple="multiple" style="width: 100%">
                                            @foreach ($categories as $category)
                                                <optgroup label="{{ $category->name }}">
                                                    @foreach ($category->equipment as $e)
                                                        <option value="{{$e->id}}">{{ $e->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-primary" type="submit" style="width: 100%">
                                                Get report
                                                <i class="fas fa-download ml-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>        
                        </div>
                    </div>
                </div>
              <!-- /.card -->
            </div>
        </div>
    </div>

@endsection

@section('additional_scripts')
    <script src="{{ asset('/js/equipment/reports.js') }}"></script>
@endsection
