@extends("layouts.admin")

@section('css')
    <style>
        .permission-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .permission-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 200px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .permission-card h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-check {
            margin-bottom: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __('messages.Edit Role') }}</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.role.update', $role->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="my-3">
                                <input type="text"
                                    class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                                    placeholder="{{ __('messages.Role Name') }}" value="{{ old('name', $role->name) }}" name="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <span class="emsg text-danger"></span>
                            </div>

                            <h1 class="mt-4" style="font-size: 20px;">{{ __('messages.Permission') }}</h1>
                            <div class="permission-container">
                                @foreach($permissions->groupBy(function ($item) {
                                    return explode('-', $item->name)[0]; // Group by module
                                }) as $module => $actions)
                                    <div class="permission-card">
                                        <h5>{{ __(ucfirst($module)) }}</h5>
                                        @foreach($actions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                name="permissions[]" 
                                                value="{{ $permission->id }}" 
                                                id="permission_{{ $permission->id }}" 
                                                {{ in_array($permission->id, old('permissions', $role_permissions)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ __(explode('-', $permission->name)[1]) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit"
                                    class="btn btn-success waves-effect waves-light">{{ __('messages.Update') }}</button>
                                <a href="{{ route('admin.role.index') }}"
                                    class="btn btn-danger waves-effect waves-light ml-2">{{ __('messages.Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
