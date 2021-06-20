<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-laptop-code mr-2"></i>
            Available equipment by category
        </h3>
    </div><!-- /.card-header -->
    <div class="card-body table-responsive">

        @foreach($categories as $category)
            <div class="card card-default collapsed-card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $category->name }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Qty. available</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($category->equipment as $e)
                            <tr class="clickable-row" data-href="/equipment/{{ $e->id }}" >
                                <td>{{ $e->id }}</td>
                                <td>{{ $e->name }}</td>
                                <td>{{ $e->available_quantity }}</td>
                                <td>{{ $e->short_description }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div><!-- /.card-body -->
</div>