@extends('layouts.main')

@section('page_title', 'Employees list')

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
                        <i class="fas fa-users mr-1"></i>
                        Employees
                    </h3>
                    @can('create', \App\Models\User::class)
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="btn btn-sm btn-flat btn-primary" href="/users/create">Add new employee</a>
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
                            <th>Name</th>
                            <th>E-mail</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class="clickable-row" data-href="/users/{{ $user->id }}" >
                                <td>{{ $user->id }}</td>
                                <td>
                                    {{ $user->name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="/users/{{ $user->id }}/edit" class="btn btn-primary btn-sm btn-flat @cannot('update', $user) disabled @endcannot">
                                        <i class="fa fa-edit"></i>
                                        EDIT
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-sm btn-flat @if($user->id == auth()->id()) disabled @endif @cannot('delete', $user) disabled @endcannot" onclick="confirmUserDelete({{ $user->id }}, event)">
                                        <i class="fa fa-times"></i>
                                        DELETE
                                    </a>
                                    <form action="/users/{{ $user->id }}" method="POST" id="delete_form_{{ $user->id }}">
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
    <script src="{{ asset('/js/users/index.js') }}"></script>
@endsection
