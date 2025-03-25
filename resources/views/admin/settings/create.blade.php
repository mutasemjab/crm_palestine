@extends('layouts.admin')
@section('title')
    Setting
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> Add New Setting </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('settings.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf



                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Name of Company') }} </label>
                            <input name="name" id="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.link_google') }} </label>
                            <input name="link_google" id="name" class="form-control" value="{{ old('link_google') }}">
                            @error('link_google')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                          <button class="btn">{{ __('messages.Logo') }} </button>
                         <input  type="file" id="Item_img" name="logo" class="form-control" onchange="previewImage()">
                            @error('logo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                    </div>






                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> submit</button>
                            <a href="{{ route('settings.index') }}" class="btn btn-sm btn-danger">cancel</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection
