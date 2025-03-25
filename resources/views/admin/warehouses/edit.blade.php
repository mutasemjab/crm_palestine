@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.warehouses') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('warehouses.index') }}"> {{ __('messages.warehouses') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.warehouses') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('warehouses.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}</label>
                            <input name="name" id="name" class=""
                                value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shop">{{ __('messages.admins') }}</label>
                            <select class="form-control" name="admin" id="admin_id">
                                <option value="">Select admins</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" @if($data->admin_id == $admin->id) selected @endif>{{ $admin->name }}</option>
                                @endforeach
                                <option value="0" @if($data->admin_id === null) selected @endif>No admin</option>
                            </select>
                            @error('admin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                                {{ __('messages.Update') }}</button>
                            <a href="{{ route('warehouses.index') }}"
                                class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection

@section('script')

@endsection
