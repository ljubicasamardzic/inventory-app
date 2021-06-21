@extends('layouts.main')

@section('page_title', 'Notifications')

@section('additional_styles')

@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">All notifications</h3>
                <form action="/mark_all_notifications_read/{{ auth()->id() }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-sm btn-warning float-right">
                        Mark all as read                  
                    </button>
                </form>
              </div>
            <div class="card-body table-responsive p-0">
                <ul class="list-group">
                    @if ($notifications != null)
                        @foreach ($notifications as $notification)
                            <li class="list-group-item @if ($notification->read_at != null) text-muted @endif"</li>
                                @if ($notification->type == ($closed || $approved || $rejected || $HR_responded))
                                    <form action="/notifications/{{$notification->id}}" method="POST">
                                        @if ($notification->type == $closed)
                                        Request with ID {{$notification->data['ticket']['id']}} has been closed.
                                        @elseif ($notification->type == $approved)
                                        Your request has been approved.
                                        @elseif ($notification->type == $rejected)
                                        Your request has been rejected.
                                        @elseif ($notification->type == $HR_responded)
                                        Request with ID {{$notification->data['ticket']['id']}} has been reviewed by an HR.
                                        @elseif ($notification->type == $new_equipment)
                                        New equipment is available. 
                                        @elseif ($notification->type == $restocked)
                                        Stock replenished for item with ID {{$notification->data['equipment']['id']}}.
                                        @endif
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
                    @else <li class="list-group-item">You have 0 notifications.</li>
                    @endif
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