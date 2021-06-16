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
                            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Report by department</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Report by positions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Report by categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill" href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">Report by employees</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body mt-2">
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
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
                        <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
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
                        <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
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
                        <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
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
