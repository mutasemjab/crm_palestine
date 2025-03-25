@extends('layouts.admin')

@section('title', __('messages.Booking Report'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.Booking Report') }}</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.bookings') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('messages.Start Date') }}</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('messages.End Date') }}</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('messages.Employee') }}</label>
                        <select name="employee" class="form-control">
                            <option value="">{{ __('messages.All Employees') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.Generate Report') }}</button>
        </form>
        <hr>
        @if($bookings->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('messages.Booking ID') }}</th>
                            <th>{{ __('messages.Title') }}</th>
                            <th>{{ __('messages.Employee') }}</th>
                            <th>{{ __('messages.Start Date') }}</th>
                            <th>{{ __('messages.Due Date') }}</th>
                            <th>{{ __('messages.Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->title }}</td>
                                <td>{{ $booking->employee->name }}</td>
                                <td>{{ $booking->start_date }}</td>
                                <td>{{ $booking->due_date }}</td>
                                <td>{{ ucfirst($booking->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">{{ __('messages.No records found') }}</div>
        @endif
    </div>
</div>
@endsection
