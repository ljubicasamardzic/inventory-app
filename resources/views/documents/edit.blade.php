@extends('layouts.main')

@section('page_title', 'Edit Document details')

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Edit Document details
                    </h3>

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <form action="/documents/{{ $document->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-4">
                                <label for="user_select">Employee:</label>
                                <select name="user_id" id="user_select" class="form-control @error('user_id') is-invalid @endif">
                                    <option value="">- select an employee -</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == $document->user_id ? 'selected' : '' }}
                                        >
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                            <div class="col-4">
                                <label for="date_input">Date:</label>
                                <input type="date" name="date" value="{{ $document->date->format('Y-m-d') }}" id="date_input" class="form-control @error('date') is-invalid @endif" />
                                @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-4 d-flex flex-column justify-content-end">
                                <button type="submit" class="btn btn-primary btn-block btn-flat mt-4">
                                    SAVE CHANGES
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- @include('documents.items_table') --}}

                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>
    {{-- @include('documents.new_item_modal') --}}
@endsection
@section('additional_scripts')
    <script src="{{ asset('js/documents/create.js') }}"></script>
@endsection
