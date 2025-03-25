@extends('layouts.admin')



@section('content')
    <div class="container">
        <h2> @if ($note_voucher_type->in_out_type == 1)
           سند ادخال
        @elseif ($note_voucher_type->in_out_type == 2)
            سند اخراج
        @elseif ($note_voucher_type->in_out_type == 3)
        سند تحويل
        @endif
        </h2>
        <form action="{{ route('noteVouchers.store') }}" method="POST">
            @csrf

            <input type="hidden" name="redirect_to" id="redirect_to" value="index">

            <button type="submit" class="btn btn-primary" onclick="setRedirect('index')">{{ __('messages.Submit') }}</button>
            <button type="submit" class="btn btn-primary" onclick="setRedirect('show')">{{ __('messages.Save_Print') }}</button>

            <input type="hidden" name="note_voucher_type_id" value="{{ $note_voucher_type_id }}" class="form-control" required>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_note_voucher"> {{ __('messages.Date') }}</label>
                    <input type="date" name="date_note_voucher" class="form-control" required>
                </div>
            </div>



            @if ($note_voucher_type->in_out_type == 2 && auth()->user()->is_super == 0)
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.fromWarehouse') }}</label>
                    <select name="fromWarehouse" class="form-control" required>
                        @foreach ($warehouses as $warehouse)
                            @if ($warehouse->admin_id == auth()->id())
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.fromWarehouse') }}</label>
                    <select name="fromWarehouse" class="form-control" required>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            @if ($note_voucher_type->in_out_type == 3)
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.toWarehouse') }}</label>
                    <select name="toWarehouse" class="form-control" required>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            @if ($note_voucher_type->in_out_type == 2)
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.tasks') }}</label>
                    <select name="task" class="form-control" required>
                        @foreach ($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif




            <div class="col-md-6">
                <div class="form-group">
                    <label for="note">{{ __('messages.Note') }}</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>
            </div>

<br>
            <table class="table table-bordered" id="products_table">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.unit') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        @if($note_voucher_type->have_price == 1)
                            <th>{{ __('messages.purchasing_Price') }}</th>
                        @endif
                        <th>{{ __('messages.Note') }}</th>
                        <th>{{ __('messages.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control product-search" name="products[0][name]"/></td>
                        <td>
                            <select class="form-control product-unit" name="products[0][unit]">
                                <option value="">Select Unit</option>
                            </select>
                        </td>
                        <td><input type="number" class="form-control" name="products[0][quantity]" /></td>
                        @if($note_voucher_type->have_price == 1)
                            <td><input type="number" class="form-control" name="products[0][purchasing_price]" step="any" /></td>
                        @endif
                        <td><input type="text" class="form-control" name="products[0][note]" /></td>
                        <td><button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button></td>
                    </tr>
                </tbody>
            </table>


            <button type="button" class="btn btn-primary" id="add_row">{{ __('messages.Add_Row') }}</button>


        </form>
    </div>
@endsection

@section('js')

<script type="text/javascript">

function setRedirect(value) {
        document.getElementById('redirect_to').value = value;
    }

    $(document).ready(function() {
    let rowIdx = 1;

    function initializeProductSearch() {
        $('.product-search').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("products.search") }}',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (data.length === 0) {
                            response([{ label: 'Not Found', value: '' }]);
                        } else {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name,
                                    value: item.name,
                                    units: item.units,
                                    unit: item.unit,
                                    id: item.id
                                };
                            }));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.value === '') {
                    event.preventDefault();
                } else {
                    const selectedRow = $(this).closest('tr');
                    const unitDropdown = selectedRow.find('.product-unit');
                    unitDropdown.empty();

                    // Add main unit as the first option
                    if (ui.item.unit) {
                        unitDropdown.append(`<option value="${ui.item.unit.id}">${ui.item.unit.name}</option>`);
                    }

                    // Add other units
                    if (ui.item.units) {
                        $.each(ui.item.units, function(index, unit) {
                            unitDropdown.append(`<option value="${unit.id}">${unit.name}</option>`);
                        });
                    }
                }
            }
        });
    }


    $('#add_row').on('click', function() {
        $('#products_table tbody').append(`
            <tr>
                <td><input type="text" class="form-control product-search" name="products[${rowIdx}][name]" /></td>
                <td>
                    <select class="form-control product-unit" name="products[${rowIdx}][unit]">
                        <option value="">Select Unit</option>
                    </select>
                </td>
                <td><input type="number" class="form-control" name="products[${rowIdx}][quantity]" /></td>
                @if($note_voucher_type->have_price == 1)
                <td><input type="number" class="form-control" name="products[${rowIdx}][purchasing_price]" step="any" /></td>
                @endif
                <td><input type="text" class="form-control" name="products[${rowIdx}][note]" /></td>
                <td><button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button></td>
            </tr>
        `);
        rowIdx++;
        initializeProductSearch();
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    initializeProductSearch();
    handleBarcodeInput();
});


</script>

@endsection
