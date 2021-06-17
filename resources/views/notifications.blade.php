@extends('layouts.main')

@section('page_title', 'Notifications')

@section('additional_styles')

@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
            </div>
            <div class="card-body table-responsive p-0">
                <ul class="list-group">
                    @foreach ($notifications as $notification)
                        <li class="list-group-item @if ($notification->read_at != null) text-muted @endif"</li>
                            @if ($notification->type == 'App\Notifications\TicketClosedNotification')
                                <form action="/notifications/{{$notification->id}}" method="POST">
                                    Request with ID {{$notification->data['ticket']['id']}} has been closed.
                                    @if ($notification->read_at == null)
                                    <small class="badge badge-danger"></i>new</small>
                                    @endif
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-primary btn-sm float-right" type="submit">View Request</button>
                                    <div>
                                        <span class="float-left text-muted text-sm mt-2">
                                            {{$notification->created_at->format('d.m.Y')}}
                                        </span>
                                    </div>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <div class="d-flex mt-2 flex-row justify-content-center">
                        {{ $notifications->links() }}
                </div>
            </div>
          </div>
    </div>
</div>

@endsection

@section('additional_scripts')
@endsection