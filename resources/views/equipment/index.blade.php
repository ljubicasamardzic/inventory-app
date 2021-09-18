@extends('layouts.main')

@section('page_title', 'Equipment list')

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
                        <i class="fas fa-laptop-code mr-1"></i>
                        Equipment
                    </h3>
                    @can('create', \App\Models\Equipment::class)
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="btn btn-sm btn-flat btn-primary" href="/equipment/create">Add new equipment</a>
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
                            <th>Category</th>
                            <th>Name</th>
                            <th>Qty. available</th>
                            <th>Qty. reserved</th>
                            <th>Description</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($equipment as $e)
                            <tr class="clickable-row" data-href="/equipment/{{ $e->id }}" >
                                <td>{{ $e->id }}</td>
                                <td>
                                    {{ $e->category->name }}
                                </td>
                                <td>{{ $e->name }}</td>
                                <td>{{ $e->available_quantity - $e->number_of_reservations }}</td>
                                <td>{{ $e->number_of_reservations }}</td>
                                <td>{{ $e->short_description }}</td>
                                <td>
                                    <a href="/equipment/{{ $e->id }}/edit" class="btn btn-primary btn-sm btn-flat @cannot('update', $e) disabled @endcannot">
                                        <i class="fa fa-edit"></i>
                                        EDIT
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-sm btn-flat confirm-delete-btn 
                                                @cannot('delete', $e) disabled @endcannot"
                                                data-id={{ $e->id }}
                                    >
                                        <i class="fa fa-times"></i>
                                        DELETE
                                    </a>
                                    <form action="/equipment/{{ $e->id }}" method="POST" id="delete_form_{{ $e->id }}">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex flex-row justify-content-center mt-3">
                        {{ $equipment->links() }}
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

@endsection

@section('additional_scripts')
    <script src="{{ asset('/js/equipment/index.js') }}"></script>
@endsection
