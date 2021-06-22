@extends('layouts.main')

@section('page_title', 'Request details')

@section('content')
    @foreach ($errors as $item)
        {{ $item }}
    @endforeach
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                @include('tickets.request-details-header')

                <div class="card-body table-responsive">
                    @include('tickets.request-details-body')
                </div> <!-- /.card-body -->
            </div>
            @if (auth()->user()->isAdmin())
                @include('tickets.decision_details') 
            @endif 
        </div>
    </div>
@include('tickets/modals/reject_request_officer_modal')
@include('tickets/modals/approve_request_officer_modal')
@include('tickets/modals/reject_request_HR_modal')
@include('tickets/modals/approve_request_HR_modal')
@include('tickets/modals/mark_finished_modal')
@include('tickets/modals/edit_equipment_modal')
@include('tickets/modals/edit_malfunction_modal')
@include('tickets/modals/update_officer_decision_modal')
@include('tickets/modals/update_HR_decision_modal')

@section('additional_scripts')
    <script src="{{ asset('js/tickets/show.js') }}"></script>
    {{-- <script src="{{ asset('js/home/index.js') }}"></script> --}}
@endsection

@endsection
