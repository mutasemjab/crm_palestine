@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.rolloutSuperVisors') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('rolloutSuperVisors.index') }}"> {{ __('messages.rolloutSuperVisors') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.rolloutSuperVisors') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">




                <form action="{{ route('rolloutSuperVisors.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                    <div class="row">
                    @csrf
                    @method('PUT')


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Name of worker') }} </label>
                            <textarea name="name_of_worker" id="name_of_worker" class="form-control" value="{{ old('name_of_worker') }}" rows="12">{{$data['name_of_worker']}}</textarea>
                            @error('name_of_worker')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Expenses') }} </label>
                            <textarea name="expenses" id="expenses" class="form-control" value="{{ old('expenses') }}" rows="12">{{$data['expenses']}}</textarea>
                            @error('expenses')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Purchases') }} </label>
                            <textarea name="purchases" id="purchases" class="form-control" value="{{ old('purchases') }}" rows="12">{{$data['purchases']}}</textarea>
                            @error('purchases')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Note') }} </label>
                            <textarea name="note" id="note" class="form-control" value="{{ old('note') }}" rows="12">{{$data['note']}}</textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Google drive link') }} </label>
                            <input name="google_drive_link" id="google_drive_link" class="form-control" value="{{ old('google_drive_link',$data['google_drive_link']) }}">
                            @error('google_drive_link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('rolloutSuperVisors.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>


            </div>

            </form>

        </div>




    </div>
    </div>
@endsection

