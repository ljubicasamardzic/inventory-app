<div class="row mt-4">
    <div class="col-12 table-responsive">
        <h6 class="my-3">
            Document Items
            @can('create', \App\Models\Document::class)
                <button type="button"
                class="btn btn-sm btn-flat btn-primary float-right"
                data-toggle="modal"
                data-target="#new_item_modal"
                >
                    Add items
                </button>
            @endcan
        </h6>

        <table class="table table-sm table-hover table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Equipment</th>
                <th>Serial number</th>
                <th>Returned</th>
                <th>Returned date</th>
                <th>Return</th>
                <th>Edit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->equipment->full_name }}</td>
                    @if ($item->serial_number != null)
                        <td>{{ $item->serial_number->serial_number }}</td>
                    @else
                        <td>/</td> 
                    @endif
                    <td>
                        @if($item->returned)
                            <i class="fa fa-check-circle"></i>
                        @else
                            <i class="fa fa-times-circle"></i>
                        @endif
                    </td>
                    <td>{{ $item->returned_date_formated }}</td>
                    <td>
                        @if(!$item->returned)
                            <form action="/document-item/return/{{ $item->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" id="btn-return-item" class="btn btn-warning btn-flat btn-sm @cannot('create', App\Models\Document::class) disabled @endcannot">
                                    Return equipment
                                </button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <button type="button"
                                class="btn btn-sm btn-flat btn-primary"
                                id="edit_btn_{{ $item->id }}"
                                data-val = "{{ $item->serial_number->serial_number }}"
                                data-equipment-id = "{{ $item->equipment_id }}"
                                data-serial-number-id = "{{ $item->serial_number_id }}"
                                data-toggle="modal"
                                data-target="#edit_item_modal"
                                @if ($item->returned) disabled @endif
                                onclick="findId({{ $item->id }})"
                        >
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
