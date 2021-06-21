@extends('layouts.main')

@section('page_title', 'Documents list')

@section('additional_styles')
    <style>
        .clickable-row{ cursor: pointer; }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-paperclip mr-1"></i>
                        Documents
                    </h3>
                    @can('create', \App\Models\Document::class)
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a class="btn btn-sm btn-flat btn-primary" href="/documents/create">Add new document</a>
                                </li>
                            </ul>
                        </div>
                    @endcan
                </div><!-- /.card-header -->
                <div class="card-body table-responsive">

                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Administrator</th>
                            <th>Date</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                            <tr class="clickable-row" data-href="/documents/{{ $doc->id }}" >
                                <td>{{ $doc->id }}</td>
                                <td>{{ $doc->user->name }}</td>
                                <td>{{ $doc->admin->name }}</td>
                                <td>{{ $doc->date_formated }}</td>
                                <td>
                                    <a href="/documents/{{ $doc->id }}/edit" class="btn btn-primary btn-sm btn-flat @cannot('update', $doc) disabled @endcannot">
                                        <i class="fa fa-edit"></i>
                                        EDIT
                                    </a>
                                </td>
                                <td>
                                    <form action="/documents/{{ $doc->id }}" method="POST" id="delete-form-{{$doc->id}}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                        <a class="btn btn-danger btn-sm btn-flat delete-confirm
                                                @cannot('delete', $doc) disabled @endcannot" 
                                                data-id = {{ $doc->id }}
                                        >
                                            <i class="fa fa-times"></i>
                                            DELETE
                                        </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex flex-row justify-content-center mt-3">
                        {{ $documents->links() }}
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

@endsection

@section('additional_scripts')
    <script src="{{ asset('/js/documents/index.js') }}"></script>
@endsection
