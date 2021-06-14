@extends('layouts.main')

@section('page_title', 'Document details')

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Document details
                    </h3>

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Document date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $document->user->name }}</td>
                                <td>{{ $document->user->department_name }}</td>
                                <td>{{ $document->user->position->name }}</td>
                                <td>{{ $document->date_formated }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @include('documents.items_table')

                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>
    @include('documents/new_item_modal')
    @include('documents/edit_item_modal')

    @section('additional_scripts')
        <script src="{{ asset('js/documents/create.js') }}"></script>
    @endsection
@endsection

