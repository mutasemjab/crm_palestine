@extends('layouts.admin')

@section('title', __('messages.Role'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.Role') }}</h3>
        <a href="{{ route('admin.role.create') }}" class="btn btn-sm btn-primary">{{ __('messages.New Role') }}</a>
    </div>
    <div class="card-body">
        @can('role-table')
            @if($data->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.Permission') }}</th>
                                <th class="text-center">{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @foreach ($value->permissions as $permission)
                                            {{ $permission->name }}<br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.role.edit', $value->id) }}" class="btn btn-primary btn-sm">{{ __('messages.Edit') }}</a>
                                        <form action="{{ route('admin.role.destroy', $value->id) }}" method="POST" style="display:inline-block;">
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
                    {{ __('messages.No_data') }}
                </div>
            @endif
        @endcan
    </div>
</div>
@endsection


