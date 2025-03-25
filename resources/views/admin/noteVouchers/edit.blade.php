@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>{{ __('messages.Edit') }} {{ $noteVoucher->noteVoucherType->in_out_type == 1
            ? 'ادخال'
            : ($noteVoucher->noteVoucherType->in_out_type == 2
                ? 'اخراج'
                : 'نقل') }}
        </h2>
        <form action="{{ route('noteVouchers.update', $noteVoucher->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="redirect_to" id="redirect_to" value="index">

            <button type="submit" class="btn btn-primary" onclick="setRedirect('index')">{{ __('messages.Submit') }}</button>
            <button type="submit" class="btn btn-primary" onclick="setRedirect('show')">{{ __('messages.Save_Print') }}</button>

            <input type="hidden" name="note_voucher_type_id" value="{{ $noteVoucher->note_voucher_type_id }}" class="form-control" required>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_note_voucher"> {{ __('messages.Date') }}</label>
                    <input type="date" name="date_note_voucher" class="form-control" value="{{ $noteVoucher->date_note_voucher }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.fromWarehouse') }}</label>
                    <select name="fromWarehouse" class="form-control" required>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $noteVoucher->from_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($noteVoucher->noteVoucherType->in_out_type == 3)
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.toWarehouse') }}</label>
                    <select name="toWarehouse" class="form-control" required>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $noteVoucher->to_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            @if($noteVoucher->noteVoucherType->in_out_type == 3)
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.tasks') }}</label>
                    <select name="task" class="form-control" required>
                        @foreach ($tasks as $task)
                            <option value="{{ $task->id }}" {{ $noteVoucher->task_id == $task->id ? 'selected' : '' }}>{{ $task->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <div class="col-md-6">
                <div class="form-group">
                    <label for="note">{{ __('messages.Note') }}</label>
                    <textarea name="note" class="form-control">{{ $noteVoucher->note }}</textarea>
                </div>
            </div>

            <br>
            <table class="table table-bordered" id="products_table">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.unit') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        @if($noteVoucher->noteVoucherType->have_price == 1)
                            <th>{{ __('messages.purchasing_Price') }}</th>
                        @endif
                        <th>{{ __('messages.Note') }}</th>
                        <th>{{ __('messages.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($noteVoucher->voucherProducts as $key => $voucherProduct)
                        <tr>
                            <td><input type="text" class="form-control product-search" name="products[{{ $key }}][name]" value="{{ $voucherProduct->name }}" /></td>
                            <td>
                                <select class="form-control product-unit" name="products[{{ $key }}][unit]">
                                    <option value="">Select Unit</option>

                                    @if ($voucherProduct->unit)
                                        <option value="{{ $voucherProduct->unit->id }}" {{ $voucherProduct->pivot->unit_id == $voucherProduct->unit->id ? 'selected' : '' }}>{{ $voucherProduct->unit->name}}</option>
                                    @endif
                                </select>
                            </td>
                            <td><input type="number" class="form-control" name="products[{{ $key }}][quantity]" value="{{ $voucherProduct->pivot->quantity }}" /></td>
                            @if($noteVoucher->noteVoucherType->have_price == 1)
                                <td><input type="number" class="form-control" name="products[{{ $key }}][purchasing_price]" value="{{ $voucherProduct->pivot->purchasing_price }}" step="any"/></td>
                            @endif
                            <td><input type="text" class="form-control" name="products[{{ $key }}][note]" value="{{ $voucherProduct->pivot->note }}" /></td>
                            <td><button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button></td>
                        </tr>
                    @endforeach
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
        let rowIdx = {{ $noteVoucher->voucherProducts->count() }};

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
                    @if($noteVoucher->noteVoucherType->have_price == 1)
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
