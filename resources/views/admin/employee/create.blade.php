@extends('layouts.admin')

@section('title', __('messages.create_employee'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.create_employee') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.employee.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">{{ __('messages.Name') }}</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">{{ __('messages.Phone') }}</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="password">{{ __('messages.Password') }}</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="address">{{ __('messages.Address') }}</label>
                <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
            </div>

            <div class="form-group">
                <label for="activate">{{ __('messages.Activate') }}</label>
                <select name="activate" id="activate" class="form-control">
                    <option value="1" {{ old('activate') == 1 ? 'selected' : '' }}>{{ __('messages.Yes') }}</option>
                    <option value="2" {{ old('activate') == 2 ? 'selected' : '' }}>{{ __('messages.No') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="country_id">{{ __('messages.Country') }}</label>
                <select name="country_id" id="country_id" class="form-control">
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="is_constructor">{{ __('Is Constructor') }}</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_constructor" name="is_constructor" value="1">
                    <label class="form-check-label" for="is_constructor">{{ __('Mark as Constructor') }}</label>
                </div>
            </div>

            <div class="my-3">
                @foreach ($roles as $role)
                     <br>
                     <input {{in_array( $role->id,old('roles')? old('roles'): []) ? 'checked':''}} class="ml-5" type="checkbox" name="roles[]" id="role_{{$role->id}}" value="{{ $role->id }}">
                     <label for="role_{{$role->id}}"> {{ $role->name }}. </label>
                     <br>
                 @endforeach
             </div>
             <div class="row" id="permissions">
                 @error('perms')
                     <span class="text-danger">{{ $message }}</span>
                 @enderror
                 <span class="emsg text-danger"></span>
             </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('messages.Submit') }}</button>
                <a href="{{ route('admin.employee.index') }}" class="btn btn-secondary">{{ __('messages.Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
