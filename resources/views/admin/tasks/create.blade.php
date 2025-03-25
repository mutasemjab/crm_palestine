@extends('layouts.admin')

@section('title', __('messages.create_task'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.create_task') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="core_color">{{ __('messages.date_of_task') }}</label>
                <input type="date" name="date_of_task" id="date_of_task" class="form-control" value="{{ old('date_of_task') }}">
            </div>

            <div class="form-group">
                <label for="building">{{ __('messages.building') }}</label>
                <input type="text" name="building" id="building" class="form-control" value="{{ old('building') }}">
            </div>

            <div class="form-group">
                <label for="district">{{ __('messages.district') }}</label>
                <input type="text" name="district" id="district" class="form-control" value="{{ old('district') }}">
            </div>

            <div class="form-group">
                <label for="area">{{ __('messages.area') }}</label>
                <input type="text" name="area" id="area" class="form-control" value="{{ old('area') }}">
            </div>

            <div class="form-group">
                <label for="assigned_time">{{ __('messages.assigned_time') }}</label>
                <input type="datetime-local" name="assigned_time" id="assigned_time" class="form-control" value="{{ old('assigned_time') }}">
            </div>

            <div class="form-group">
                <label for="customer_name">{{ __('messages.customer_name') }}</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}">
            </div>

            <div class="form-group">
                <label for="customer_username">{{ __('messages.customer_username') }}</label>
                <input type="text" name="customer_username" id="customer_username" class="form-control" value="{{ old('customer_username') }}">
            </div>

            <div class="form-group">
                <label for="customer_mobile">{{ __('messages.customer_mobile') }}</label>
                <input type="text" name="customer_mobile" id="customer_mobile" class="form-control" value="{{ old('customer_mobile') }}">
            </div>

            <div class="form-group">
                <label for="customer_address">{{ __('messages.customer_address') }}</label>
                <textarea name="customer_address" id="customer_address" class="form-control">{{ old('customer_address') }}</textarea>
            </div>

            <div class="form-group">
                <label for="splitting">{{ __('messages.splitting') }}</label>
                <textarea name="splitting" id="splitting" class="form-control">{{ old('splitting') }}</textarea>
            </div>

            <div class="form-group">
                <label for="sspl_no_planned">{{ __('messages.sspl_no_planned') }}</label>
                <input type="number" name="sspl_no_planned" id="sspl_no_planned" class="form-control" value="{{ old('sspl_no_planned') }}">
            </div>

            <div class="form-group">
                <label for="rspl_no">{{ __('messages.rspl_no') }}</label>
                <input type="number" name="rspl_no" id="rspl_no" class="form-control" value="{{ old('rspl_no') }}">
            </div>

            <div class="form-group">
                <label for="through">{{ __('messages.through') }}</label>
                <input type="text" name="through" id="through" class="form-control" value="{{ old('through') }}">
            </div>

            <div class="form-group">
                <label for="core_color">{{ __('messages.core_color') }}</label>
                <input type="text" name="core_color" id="core_color" class="form-control" value="{{ old('core_color') }}">
            </div>


            <div class="form-group">
                <label for="note">{{ __('messages.note') }}</label>
                <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
            </div>

            <div class="form-group">
                <label for="job_order_status">{{ __('messages.job_order_status') }}</label>
                <select name="job_order_status" id="job_order_status" class="form-control">
                    @foreach(['none', 'opened', 'pending', 'in_progress', 'completed'] as $status)
                        <option value="{{ $status }}" {{ old('job_order_status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="contractor_status">{{ __('messages.contractor_status') }}</label>
                <select name="contractor_status" id="contractor_status" class="form-control">
                    @foreach(['none', 'opened', 'pending', 'in_progress', 'completed'] as $status)
                        <option value="{{ $status }}" {{ old('contractor_status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="customer_service_status">{{ __('messages.customer_service_status') }}</label>
                <select name="customer_service_status" id="customer_service_status" class="form-control">
                    @foreach(['installation', 'availability'] as $status)
                        <option value="{{ $status }}" {{ old('customer_service_status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="postal_code_status">{{ __('messages.postal_code_status') }}</label>
                <select name="postal_code_status" id="postal_code_status" class="form-control">
                    @foreach(['active', 'planned'] as $status)
                        <option value="{{ $status }}" {{ old('postal_code_status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>



            <div class="form-group">
                <label for="admin_id">{{ __('messages.admin') }}</label>
                <select name="admin_id" id="admin_id" class="form-control">
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="job_order_type_id">{{ __('messages.job_order_type') }}</label>
                <select name="job_order_type_id" id="job_order_type_id" class="form-control">
                    @foreach($jobOrderTypes as $jobOrderType)
                        <option value="{{ $jobOrderType->id }}" {{ old('job_order_type_id') == $jobOrderType->id ? 'selected' : '' }}>{{ $jobOrderType->name }}</option>
                    @endforeach
                </select>
            </div>



            <div class="form-group">
                <label for="type_id">{{ __('messages.type') }}</label>
                <select name="type_id" id="type_id" class="form-control">
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('messages.Submit') }}</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">{{ __('messages.Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
