@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Financial Report - Tasks</h2>

    <form method="GET" action="{{ route('reports.financial') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label>End Date:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label>Employee:</label>
                <select name="admin" class="form-control">
                    <option value="">All Employees</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered" id="data-table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Task ID</th>
                    <th>Employee</th>
                    <th>Job Order Type</th>
                    <th>Cost</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $index => $task)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#taskDetailsModal{{ $task->id }}">
                                {{ __('Task') }} #{{ $task->id }}
                            </a>                             
                        </td>
                        <td>{{ $task->admin->name ?? 'N/A' }}</td>
                        <td>{{ $task->jobOrderType->name ?? 'N/A' }}</td>
                        <td>
                            @if($task->job_order_type_id == 1 || $task->job_order_type_id == 4) <!--Inside Building Installation and Reallocate Home Box-->

                            {{ $task->financial->total_of_inside ?? 'N/A' }}

                            @elseif($task->job_order_type_id == 2 || $task->job_order_type_id == 3) <!--Entrance & Entrance 2-->

                            {{ $task->financial->total_of_entrance ?? 'N/A' }}

                            @elseif($task->job_order_type_id == 5)

                            {{ $task->financial->total_of_entrance ?? 'N/A' }}

                            @elseif($task->job_order_type_id == 6) <!--Rollout-->

                            {{ $task->financial->total_of_rollout ?? 'N/A' }}

                            @else
                               'N/A'
                            @endif
                        </td>
                        <td>{{ $task->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@foreach ($tasks as $index =>  $task)

        <div class="modal fade" id="taskDetailsModal{{ $task->id }}" tabindex="-1" aria-labelledby="taskDetailsModalLabel{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskDetailsModalLabel{{ $task->id }}">{{ __('Task Details') }} #{{ $task->id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr><th>{{ __('Building') }}</th><td>{{ $task->building }}</td></tr>
                            <tr><th>{{ __('District') }}</th><td>{{ $task->district }}</td></tr>
                            <tr><th>{{ __('Area') }}</th><td>{{ $task->area }}</td></tr>
                            <tr><th>{{ __('Assigned Time') }}</th><td>{{ $task->assigned_time }}</td></tr>
                            <tr><th>{{ __('Customer Name') }}</th><td>{{ $task->customer_name }}</td></tr>
                            <tr><th>{{ __('Customer Username') }}</th><td>{{ $task->customer_username }}</td></tr>
                            <tr><th>{{ __('Customer Mobile') }}</th><td>{{ $task->customer_mobile }}</td></tr>
                            <tr><th>{{ __('Customer Address') }}</th><td>{{ $task->customer_address }}</td></tr>
                            <tr><th>{{ __('Splitting') }}</th><td>{{ $task->splitting }}</td></tr>
                            <tr><th>{{ __('SSPL No Planned') }}</th><td>{{ $task->sspl_no_planned }}</td></tr>
                            <tr><th>{{ __('RSPL No') }}</th><td>{{ $task->rspl_no }}</td></tr>
                            <tr><th>{{ __('Through') }}</th><td>{{ $task->through }}</td></tr>
                            <tr><th>{{ __('Core Color') }}</th><td>{{ $task->core_color }}</td></tr>
                            <tr><th>{{ __('Note') }}</th><td>{{ $task->note }}</td></tr>
                            <tr><th>{{ __('Job Order Status') }}</th><td>{{ ucfirst($task->job_order_status) }}</td></tr>
                            <tr><th>{{ __('Contractor Status') }}</th><td>{{ ucfirst($task->contractor_status) }}</td></tr>
                            <tr><th>{{ __('Customer Service Status') }}</th><td>{{ ucfirst($task->customer_service_status) }}</td></tr>
                            <tr><th>{{ __('Postal Code Status') }}</th><td>{{ ucfirst($task->postal_code_status) }}</td></tr>
                            <tr><th>{{ __('Assigned Admin ID') }}</th><td>{{ $task->admin_id }}</td></tr>
                            <tr><th>{{ __('Created By') }}</th><td>{{ $task->created_by }}</td></tr>
                            <tr><th>{{ __('Updated By') }}</th><td>{{ $task->updated_by }}</td></tr>
                            <tr><th>{{ __('Job Order Type') }}</th><td>{{ $task->jobOrderType->name ?? __('N/A') }}</td></tr>
                            <tr><th>{{ __('Type ID') }}</th><td>{{ $task->type_id }}</td></tr>
                            <tr><th>{{ __('Created At') }}</th><td>{{ $task->created_at }}</td></tr>
                            <tr><th>{{ __('Updated At') }}</th><td>{{ $task->updated_at }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endforeach        

@endsection
