@extends('layouts.admin')
@section('title')
    Setting
@endsection


@section('contentheaderactive')
    show
@endsection



@section('content')



    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> Setting </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">

                    @if (count($data) > 0)
                        <div></div>
                    @else
                        <a href="{{ route('settings.create') }}" class="btn btn-sm btn-success"> New Setting</a>
                    @endif
                    @can('setting-table')
                        @if (@isset($data) && !@empty($data) && count($data) > 0)
                            <table style="width:100%" id="" class="table">
                                <thead class="custom_thead">
                                    <td>{{ __('messages.Name of Company') }}</td>
                                    <td>{{ __('messages.link_google') }}</td>
                                    <td>{{ __('messages.Logo') }}</td>
                                    <td>Action</td>

                                </thead>
                                <tbody>
                                    @foreach ($data as $info)
                                        <tr>
                                            <td>{{ $info->name }}</td>
                                            <td>{{ $info->link_google }}</td>
                                            <td>
                                                @if ($info->logo)
                    
                                                    <div class="image">
                                                       <img class="custom_img" src="{{ asset('assets/admin/uploads').'/'.$info->logo }}"  >
                    
                                                    </div>
                                                  @else
                                                    No Photo
                                                @endif
                                            </td>


                                            <td>
                                                @can('setting-edit')
                                                    <a href="{{ route('settings.edit', $info->id) }}"
                                                        class="btn btn-sm  btn-primary">edit</a>
                                                @endcan
                                            </td>



                                        </tr>
                                    @endforeach



                                </tbody>
                            </table>
                            <br>
                            {{ $data->links() }}
                        @else
                            <div class="alert alert-danger">
                                there is no data found !! </div>
                        @endif

                    </div>
                @endcan



            </div>

        </div>

    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/Settings.js') }}"></script>
@endsection
