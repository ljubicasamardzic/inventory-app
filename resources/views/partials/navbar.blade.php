<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a> --}}
            {{-- <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div> --}}
        {{-- </li> --}}
        
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        {{-- notifications --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ auth()->user()->unreadNotifications->count() }} 
                    @if (auth()->user()->unreadNotifications->count() == 1) new notification @else new notifications @endif
                </span>
                @if (auth()->user()->unreadNotifications->count() > -1)
                    @foreach (auth()->user()->unreadNotifications->take(5) as $notification)
                        <div class="dropdown-divider"></div>
                        <form action="/notifications/{{$notification->id}}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="dropdown-item" type="submit">
                                    <i class="fas fa-envelope mr-2"></i>
                                    @if ($notification->type == 'App\Notifications\TicketClosedNotification') 1 request closed
                                    @elseif ($notification->type == 'App\Notifications\HRResponseNotification') 1 HR request review
                                    @elseif($notification->type == 'App\Notifications\TicketApprovedNotification') 1 request approved
                                    @elseif($notification->type == 'App\Notifications\TicketRejectedNotification') 1 request rejected
                                    @elseif($notification->type == 'App\Notifications\NewEquipmentNotification') New equipment
                                    @elseif($notification->type == 'App\Notifications\RestockedNotification') Equipment restocked
                                @endif
                                <span class="float-right text-muted text-sm">
                                    {{$notification->created_at->format('d.m.Y')}}
                                </span>
                            </button>
                        </form>
                        
                    @endforeach
                @endif
                <div class="dropdown-divider"></div>
                <a href="/notifications" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>


        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                @if (Auth::user())
                    {{ Auth::user()->name }}
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a class="dropdown-item" href="{{ route('edit_password') }}">
                    Change password
                </a>
            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
