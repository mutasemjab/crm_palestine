@extends('layouts.admin')
@section('title')
{{ __('messages.products') }}
@endsection



@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.products') }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf


                <div class="form-group col-md-6">
                    <label for="unit_id"> {{ __('messages.Unit') }}</label>
                    <select class="form-control" name="unit" id="unit_id">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('unit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group col-md-6">
                    <label for="name_ar"> {{ __('messages.Name') }}</label>
                    <input name="name" id="name" class="form-control" value="{{ old('name') }}">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group col-md-6">
                    <label for="status"> {{ __('messages.Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Select</option>
                        <option @if(old('status')==1 || old('status')=="") selected="selected" @endif value="1">Active</option>
                        <option @if(old('status')==2 and old('status')!="") selected="selected" @endif value="2">Inactive</option>
                    </select>
                    @error('status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>



            <div class="form-group col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary">{{ __('messages.Submit') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-danger">{{ __('messages.Cancel') }}</a>
            </div>
        </form>
    </div>
</div>

@endsection


