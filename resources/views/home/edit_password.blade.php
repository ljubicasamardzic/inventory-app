@extends('layouts.main')
@section('page_title', 'Change Password')

@section('additional_styles')

@endsection

@section('content')
<form action="/update_password" method="POST">
    <div class="row">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ auth()->id() }}">
        <div class="col-3">
            <div class="input-group d-flex flex-row align-items-center">
                <input  id="old_password" 
                        name="old_password" 
                        type="​password" 
                        value="{{ old('old_password') }}"
                        style="-webkit-text-security: disc;" 
                        placeholder="Enter current password" 
                        class="form-control @error('old_password') is-invalid @enderror"
                >
                <i id="status_old" class="fa fa-eye ml-1" aria-hidden="true" onclick="viewPassword('old_password', 'status_old')"></i>
                @error('old_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-3">
            <div class="input-group d-flex flex-row align-items-center">
                <input  id="new_password" 
                    name="new_password" 
                    type="​password" 
                    value="{{ old('new_password') }}"
                    style="-webkit-text-security: disc;" 
                    placeholder="Enter new password" 
                    class="form-control @error('new_password') is-invalid @enderror"
                >
                <i id="status_new" class="fa fa-eye ml-1" aria-hidden="true" onclick="viewPassword('new_password', 'status_new')"></i>
                @error('new_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-3">
            <div class="input-group d-flex flex-row align-items-center">
                <input  id="repeated_password" 
                    name="repeated_password" 
                    type="​password" 
                    value="{{ old('repeated_password') }}"
                    style="-webkit-text-security: disc;" 
                    placeholder="Re-enter new password" 
                    class="form-control @error('repeated_password') is-invalid @enderror"
                >
                <i id="status_repeat_new" class="fa fa-eye ml-1" aria-hidden="true" onclick="viewPassword('repeated_password', 'status_repeat_new')"></i>
                @error('repeated_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div> 
        </div>
        <div class="col-3">
            <button class="btn btn-primary">Save changes</button>
        </div>   
    </div>
</form>


@endsection

@section('additional_scripts')
    <script src="{{ asset('/js/equipment/index.js') }}"></script>
    <script src="{{ asset('/js/home/index.js') }}"></script>
@endsection
