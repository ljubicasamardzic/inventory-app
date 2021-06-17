@extends('layouts.main')

@section('page_title', 'Request details')

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                @include('tickets.ticket_header')

                <div class="card-body table-responsive">
                    @include('tickets.ticket_body')
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

@section('additional_scripts')
    <script src="{{ asset('js/tickets/show.js') }}"></script>
@endsection

@endsection
