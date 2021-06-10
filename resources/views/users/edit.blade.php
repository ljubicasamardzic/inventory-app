@extends('layouts.main')

@section('page_title', 'Edit Employee details')

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Edit Employee details
                    </h3>

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <form action="/users/{{ $user->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-4">
                                <label for="name_input">Employee name:</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control  @error('name') is-invalid @endif " placeholder="Enter employee name" id="name_input">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label for="email_input">Employee email:</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @endif" placeholder="Enter employee email address" id="email_input">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label for="password_input">Password:</label>
                                <input type="text" name="password" disabled class="form-control @error('password') is-invalid @endif" placeholder="Enter employee password" id="password_input">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <label for="department_select">Department:</label>
                                <select name="department_id" id="department_select" class="form-control @error('department_id') is-invalid @endif">
                                    <option value="">- select a department -</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }} >{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                            <div class="col-4">
                                <label for="position_select">Position:</label>
                                <select name="position_id" id="position_select" class="form-control @error('position_id') is-invalid @endif">
                                    {{-- populated by AJAX function --}}
                                </select>
                                @error('position_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat mt-4">
                                    SAVE EMPLOYEE DETAILS
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
    <script src="{{ asset('js/users/create.js') }}"></script>
    <script>
        $(document).ready(() => {
            fillPositions({{ $user->position_id }});
        });
    </script>
@endsection
