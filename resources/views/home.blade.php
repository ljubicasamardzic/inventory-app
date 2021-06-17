@extends('layouts.main')
@section('page_title', 'Dashboard')

@section('additional_styles')
    <style>
        .clickable-row{ cursor: pointer; } 
    </style>

@endsection

@section('content')
    
    @if (in_array(auth()->user()->role_id, [User::ADMINISTRATOR, User::SUPPORT_OFFICER, User::ADMINISTRATIVE_OFFICER, User::HR]))
        @include('home.admin-dashboard')
    @else
        @include('home.user-dashboard')
    @endif 

@endsection

@section('additional_scripts')
    <script src="{{ asset('/js/equipment/index.js') }}"></script>
    <script src="{{ asset('/js/home/index.js') }}"></script>
@endsection

