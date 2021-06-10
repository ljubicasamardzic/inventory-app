<!-- Content Header (Page header) -->
@if(isset($content_header) && !empty($content_header))
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $content_header }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    @if(isset($breadcrumbs))
                        <ol class="breadcrumb float-sm-right">
                            @foreach($breadcrumbs as $key => $bc)
                                <li class="breadcrumb-item {{ $key == count($breadcrumbs)-1 ? 'active' : '' }} ">
                                    @if($key == count($breadcrumbs)-1 ? 'active' : '')
                                        {{ $bc['name'] }}
                                    @else
                                        <a href="{{ $bc['link'] }}">{{ $bc['name'] }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endif

<!-- /.content-header -->
