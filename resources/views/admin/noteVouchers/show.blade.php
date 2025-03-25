@extends('layouts.admin')



@section('content')
    <div class="container">
        <h2>{{ $noteVoucher->noteVoucherType->name }}</h2>

        <!-- Display the header -->
        @if($noteVoucher->noteVoucherType->header)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        {!! $noteVoucher->noteVoucherType->header !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>{{ __('messages.Date') }}:</strong> {{ $noteVoucher->date_note_voucher }}
            </div>
            <div class="col-md-6">
                <strong>{{ __('messages.number') }}:</strong> {{ $noteVoucher->number }}
            </div>
            <div class="col-md-6">
                <strong>{{ __('messages.fromWarehouse') }}:</strong> {{ $noteVoucher->fromWarehouse->name }}
            </div>
            @if ($noteVoucher->note_voucher_type_id == 3)
                <div class="col-md-6">
                    <strong>{{ __('messages.toWarehouse') }}:</strong> {{ $noteVoucher->toWarehouse->name }}
                </div>
            @endif
            <div class="col-md-6">
                <strong>{{ __('messages.shop') }}:</strong> {{ $noteVoucher->shop->name }}
            </div>

            <div class="col-md-12">
                <strong>{{ __('messages.Note') }}:</strong> {{ $noteVoucher->note }}
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('messages.product') }}</th>
                    <th>{{ __('messages.unit') }}</th>
                    <th>{{ __('messages.quantity') }}</th>
                    @if($noteVoucher->noteVoucherType->have_price == 1)
                    <th>{{ __('messages.purchasing_Price') }}</th>
                    @endif
                    <th>{{ __('messages.Note') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($noteVoucher->voucherProducts as $product)
                    <tr>
                        <td>{{ $product->name_ar }}</td>
                        <td>
                            @php
                                $unit = $product->units->where('id', $product->pivot->unit_id)->first();
                            @endphp
                            {{ $unit ? $unit->name_ar : $product->unit->name_ar }}
                        </td>
                        <td>{{ $product->pivot->quantity }}</td>
                        @if($noteVoucher->noteVoucherType->have_price == 1)
                            <td>{{ $product->pivot->purchasing_price }}</td>
                        @endif
                        <td>{{ $product->pivot->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Display the footer -->
        @if($noteVoucher->noteVoucherType->footer)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="alert alert-secondary">
                        {{ $noteVoucher->noteVoucherType->footer ?? null}}
                    </div>
                </div>
            </div>
        @endif

        <a href="{{ route('noteVouchers.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection
