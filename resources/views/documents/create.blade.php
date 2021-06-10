@extends('layouts.main')

@section('page_title', 'Add New Document')

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        New Document
                    </h3>

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <form action="/documents" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="user_select">Employee:</label>
                                <select name="user_id" id="user_select" class="form-control @error('user_id') is-invalid @endif">
                                    <option value="">- select an employee -</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            <div class="col-6">
                                <label for="date_input">Date:</label>
                                <input type="date" name="date" id="date_input" class="form-control @error('date') is-invalid @endif" />
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 offset-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat mt-4">
                                    SAVE DOCUMENT DETAILS
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
    <script src="{{ asset('js/documents/create.js') }}"></script>
@endsection
