@extends('layouts.main')

@section('page_title', 'Employee details')

@section('content')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Employee details
                    </h3>

                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <div class="row">
                        <div class="col-5 table-responsive">
                            <table class="table table-striped table-sm">
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td>First and last name:</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Department:</td>
                                    <td>{{ $user->department_name }}</td>
                                </tr>
                                <tr>
                                    <td>Position:</td>
                                    <td>{{ $user->position->name }}</td>
                                </tr>
                                <tr>
                                    <td>Role:</td>
                                    <td>{{ $user->role->name }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-7">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Equipment</th>
                                        <th>Serial No.</th>
                                        <th>Date</th>
                                        <th>Administrator</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->equipment->name }}</td>
                                            @if ($item->serial_number != null)
                                                <td>{{ $item->serial_number->serial_number }}</td>
                                            @else <td></td>
                                            @endif
                                            <td>{{ $item->document->date_formated }}</td>
                                            <td>{{ $item->document->admin->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>

@endsection
