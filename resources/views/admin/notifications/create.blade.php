@extends('layouts.admin')
@section('title')
{{ __('messages.notifications') }}
@endsection


@section('content')

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> {{ __('messages.Add New notifications') }}  </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('notifications.send')}}" method="post">
                                @csrf
                                <div class="form-group mt-0">
                                    <label for="title">{{ __('messages.Title') }}</label>
                                    <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title"  name="title" value="{{old('title')}}">
                                    @if($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="body">{{ __('messages.Body') }}</label>
                                    <textarea name="body" id="body" class="form-control @if($errors->has('body')) is-invalid @endif"
                                              >{{old('body')}}</textarea>
                                    @if($errors->has('body'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('body') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('messages.Assign to Employee') }}</label>
                                    <select name="admin_id" class="form-control">
                                        @foreach($employees as $employee)
                                        <option value=""> Select</option>
                                            <option value="{{ $employee->id }}" {{ old('admin_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('admin_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('messages.Submit') }}</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>


            </div>




        </div>
      </div>

@endsection





