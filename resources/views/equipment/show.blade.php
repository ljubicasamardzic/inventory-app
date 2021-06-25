@extends('layouts.main')

@section('page_title', 'Equipment details')

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-laptop-code mr-1"></i>
                        Equipment details
                    </h3>
                    <button type="button" 
                            data-toggle="modal" 
                            data-target="#modal-serial-numbers" 
                            class="float-right btn btn-sm btn-primary" 
                            @if ($equipment->required_input_fields < 1) disabled @endif 
                            @cannot('update', $equipment) disabled @endcannot
                    >
                        Add serial numbers
                    </button>
                </div><!-- /.card-header -->

                <div class="card-body table-responsive">

                    <div class="row">
                        <div class="col-5 table-responsive">
                            <table class="table table-striped table-sm">
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $equipment->id }}</td>
                                </tr>
                                <tr>
                                    <td>Category:</td>
                                    <td>{{ $equipment->category->name }}</td>
                                </tr>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{ $equipment->name }}</td>
                                </tr>
                                <tr>
                                    <td>Available quantity:</td>
                                    <td>{{ $equipment->available_quantity }}</td>
                                </tr>
                                <tr>
                                    <td>Description:</td>
                                    <td>{{ $equipment->description }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-7">
                            <table class="table table-hover table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Serial No.</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipment->serial_numbers as $key => $sn)
                                        <tr style="@if($sn->is_used) text-decoration: line-through; @endif" >
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $sn->serial_number }}</td>
                                            <td>
                                                <a 
                                                    type="button"
                                                    class="btn btn-primary btn-sm btn-flat callModal"
                                                    @cannot('update', $equipment) disabled @endcannot  
                                                    data-toggle="modal" 
                                                    data-target="#modal-serial-numbers-edit"
                                                    data-id = {{ $sn->id }}
                                                    data-sn = {{ $sn->serial_number }} >
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="/serial-numbers/{{ $sn->id }}" method="POST" id="delete_form_{{ $sn->id }}">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                                <a class="btn btn-danger btn-sm btn-flat confirm-delete-btn"
                                                    @cannot('update', $equipment) disabled @endcannot 
                                                    data-id={{ $sn->id }}
                                                >
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
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

@include('equipment.serial_numbers_edit_modal')
@include('equipment.serial_numbers_modal')

@section('additional_scripts')
    <script src="{{ asset('js/equipment/show.js') }}"></script>
@endsection

@endsection
