@extends('layouts.admin')
@section('title')
    {{ __('messages.products') }}
@endsection

@section('contentheaderactive')
    {{ __('messages.show') }}
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.products') }}</h3>

        {{-- <a href="{{ route('products.import.show') }}" class="btn btn-sm btn-success">
            {{ __('messages.productsImport') }}
        </a> --}}
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }}
            {{ __('messages.products') }}
        </a>

        <!-- Search Form -->
        <form action="{{ route('products.index') }}" method="GET" class="form-inline float-right">
            <input type="text" name="search" class="form-control mr-sm-2" placeholder="{{ __('messages.Search') }}">
            <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
        </form>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"></div>
        </div>
        <div class="clearfix"></div>
        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @if (isset($data) && !$data->isEmpty())
                @can('product-table')
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>{{ __('messages.Name') }}</th>
                            <th>{{ __('messages.Unit') }}</th>
                            <th>{{ __('messages.Status') }}</th>
                            <th>{{ __('messages.Action') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>
                                    <td>{{ $info->name }}</td>
                                    <td>
                                        @if ($info->unit)
                                            <a href="{{ route('units.index', ['id' => $info->unit->id]) }}">{{ $info->unit->name }}</a>
                                        @else
                                            No unit
                                        @endif
                                    </td>
                                    <td>{{ $info->status == 1 ? 'Active' : 'Not Active' }}</td>
                                    <td>
                                        @can('product-edit')
                                            <a href="{{ route('products.edit', $info->id) }}" class="btn btn-sm btn-primary">{{ __('messages.Edit') }}</a>
                                        @endcan
                                        @can('product-delete')
                                            <form action="{{ route('products.destroy', $info->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endcan
                <br>
                {{ $data->links() }}
            @else
                <div class="alert alert-danger">{{ __('messages.No_data') }}</div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
@endsection
