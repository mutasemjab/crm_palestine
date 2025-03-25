@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.products') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('products.index') }}"> {{ __('messages.products') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection



@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.products') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('products.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')



                    <div class="form-group col-md-6">
                        <label for="unit_id">{{ __('messages.Unit') }}</label>
                        <select class="form-control" name="unit" id="unit_id">
                            <option value="">Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ $unit->id == $data->unit_id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Name') }}</label>
                            <input name="name" id="name" class="form-control"
                                value="{{ old('name', $data->name) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select</option>
                                <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $data->status == 2 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-12 text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">Update</button>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    </div>
@endsection
