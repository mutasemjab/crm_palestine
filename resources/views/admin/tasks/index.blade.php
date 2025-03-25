@extends('layouts.admin')

@section('title', __('messages.tasks'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.tasks') }} </h3>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-start">
                    <!-- Import Form -->
                    <form action="{{ route('tasks.import') }}" method="POST" enctype="multipart/form-data" class="w-50">
                        @csrf
                        <div class="form-group">
                            <label for="file" class="form-label">Choose Excel File</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                            <button type="submit" class="btn btn-primary mt-2">Import</button>

                        </div>
                    </form>

                    <!-- Create Button -->
                    <div>
                        <a href="{{ route('tasks.create') }}" class="btn btn-success">
                            {{ __('messages.New') }} {{ __('messages.tasks') }}
                        </a>
                    </div>
                </div>
            </div>

    </div>
    <div class="card-body">
        @if($tasks->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('messages.customer_name') }}</th>
                            <th>{{ __('messages.assigned_time') }}</th>
                            <th>{{ __('messages.job_order_type') }}</th>
                            <th>{{ __('messages.job_order_status') }}</th>
                            <th>{{ __('messages.contractor_status') }}</th>
                            <th>{{ __('messages.Employee') }}</th>
                            <th>{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->customer_name ?? __('messages.Not Available') }}</td>
                                <td>{{ $task->assigned_time }}</td>
                                <td>{{ $task->jobOrderType->name ?? __('messages.Not Available') }}</td>
                                <td>
                                    <span class="badge
                                        @if($task->job_order_status == 'pending') badge-warning
                                        @elseif($task->job_order_status == 'in_progress') badge-info
                                        @elseif($task->job_order_status == 'completed') badge-success
                                        @else badge-secondary
                                        @endif">
                                        {{ ucfirst($task->job_order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge
                                        @if($task->contractor_status == 'pending') badge-warning
                                        @elseif($task->contractor_status == 'in_progress') badge-info
                                        @elseif($task->contractor_status == 'completed') badge-success
                                        @else badge-secondary
                                        @endif">
                                        {{ ucfirst($task->contractor_status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.tasks.updateAdmin', $task->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <select name="admin_id" id="admin_id_{{ $task->id }}" class="form-control" onchange="this.form.submit()">
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}" {{ $task->admin_id == $admin->id ? 'selected' : '' }}>
                                                        {{ $admin->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary btn-sm">
                                        {{ __('messages.Edit') }}
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.Are you sure?') }}')">
                                            {{ __('messages.Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="alert alert-warning text-center">
                {{ __('messages.No tasks found') }}
            </div>
        @endif
    </div>

</div>
@endsection
