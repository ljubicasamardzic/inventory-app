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
                                    <a class="btn btn-danger btn-sm btn-flat @cannot('delete', $doc) disabled @endcannot" onclick="confirmDocumentDelete({{ $doc->id }}, event)">
                                        <i class="fa fa-times"></i>
                                        DELETE
                                    </a>
                                    <form action="/document/{{ $doc->id }}" method="POST" id="delete_form_{{ $doc->id }}">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

@endsection

@section('additional_scripts')
    <script src="{{ asset('/js/documents/index.js') }}"></script>
@endsection
