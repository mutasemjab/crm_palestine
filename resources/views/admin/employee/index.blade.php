@extends('layouts.admin')

@section('title', __('messages.employees'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.employees') }}</h3>
        <a href="{{ route('admin.employee.create') }}" class="btn btn-sm btn-success">{{ __('messages.New Employee') }}</a>
    </div>
    <div class="card-body">
        @if($data->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('messages.ID') }}</th>
                            <th>{{ __('messages.Name') }}</th>
                            <th>{{ __('messages.Phone') }}</th>
                            <th>{{ __('messages.Address') }}</th>
                            <th>{{ __('messages.Activate') }}</th>
                            <th>{{ __('messages.Country') }}</th>
                            <th>{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->phone ?? __('messages.Not Available') }}</td>
                                <td>{{ $employee->address ?? __('messages.Not Available') }}</td>
                                <td>
                                    <span class="badge {{ $employee->activate == 1 ? 'badge-success' : 'badge-danger' }}">
                                        {{ $employee->activate == 1 ? __('messages.Yes') : __('messages.No') }}
                                    </span>
                                </td>
                                <td>{{ $employee->country->name ?? __('messages.Not Available') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.employee.edit', $employee->id) }}" class="btn btn-primary btn-sm">{{ __('messages.Edit') }}</a>
                                    <form action="{{ route('admin.employee.destroy', $employee->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.Are you sure?') }}')">{{ __('messages.Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $data->links() }}
            </div>
        @else
            <div class="alert alert-warning text-center">
                {{ __('messages.No employees found') }}
            </div>
        @endif
    </div>
</div>
@endsection
