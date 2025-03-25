@extends('layouts.admin')
@section('title')
    edit Setting
@endsection



@section('contentheaderlink')
    <a href="{{ route('settings.index') }}"> Setting </a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> edit Setting </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('settings.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')




                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name of Company') }}</label>
                            <input name="name" id="name" class="form-control"
                                value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.link_google') }}</label>
                            <input name="link_google" id="link_google" class="form-control"
                                value="{{ old('link_google', $data['link_google']) }}">
                            @error('link_google')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group col-md-6">
                        <label for="logo">{{ __('messages.Logo') }} </label>
                        <input type="file" name="logo" id="logo" class="form-control-file">
                        @if ($data->logo)
                            <img src="{{ asset('assets/admin/uploads').'/'.$data->logo }}" id="image-preview" alt="Selected Image" height="50px" width="50px">
                        @else
                            <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                        @endif
                        @error('logo')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> update</button>
                            <a href="{{ route('settings.index') }}" class="btn btn-sm btn-danger">cancel</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection
