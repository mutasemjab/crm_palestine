@extends('layouts.admin')
@section('title')
{{ __('messages.jobOrderTypes') }}
@endsection



@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }}  {{ __('messages.jobOrderTypes') }} </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('jobOrderTypes.store') }}" method="post" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Name') }} </label>
                            <input name="name" id="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Inside and Rellocate Home Box --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Mwaseer') }} </label>
                            <input type="number" step="0.01" name="price_of_mwaseer" class="form-control" value="{{ old('price_of_mwaseer') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Trankat') }} </label>
                            <input type="number" step="0.01" name="price_of_trankat" class="form-control" value="{{ old('price_of_trankat') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Brabesh') }} </label>
                            <input type="number" step="0.01" name="price_of_brabesh" class="form-control" value="{{ old('price_of_brabesh') }}">
                        </div>
                    </div>

                    {{-- Additional Fixed Amount Fields --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Tadkek') }} </label>
                            <input type="number" step="0.01" name="price_of_tadkek" class="form-control" value="{{ old('price_of_tadkek') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Tadkek_Msar_Close') }} </label>
                            <input type="number" step="0.01" name="price_of_tadkek_msar_close" class="form-control" value="{{ old('price_of_tadkek_msar_close') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Tarkeeb_Router') }} </label>
                            <input type="number" step="0.01" name="price_of_tarkeeb_router" class="form-control" value="{{ old('price_of_tarkeeb_router') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Mada_TV') }} </label>
                            <input type="number" step="0.01" name="price_of_mada_tv" class="form-control" value="{{ old('price_of_mada_tv') }}">
                        </div>
                    </div>

                    {{-- Entrance Section --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Type_of_Enter') }} </label>
                            <input type="number" step="0.01" name="type_of_enter" class="form-control" value="{{ old('type_of_enter') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Rabt_After_120m') }} </label>
                            <input type="number" step="0.01" name="rabt_after_120m" class="form-control" value="{{ old('rabt_after_120m') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Mwaseer_After_5m') }} </label>
                            <input type="number" step="0.01" name="mwaseer_after_5m" class="form-control" value="{{ old('mwaseer_after_5m') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_from_Engineer') }} </label>
                            <input type="number" step="0.01" name="price_from_engineer" class="form-control" value="{{ old('price_from_engineer') }}">
                        </div>
                    </div>

                    {{-- SSPL Table 1 --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_1m_Per_Length') }} </label>
                            <input type="number" step="0.01" name="price_of_1m_per_length" class="form-control" value="{{ old('price_of_1m_per_length') }}">
                        </div>
                    </div>

                    {{-- SSPL Table 2 --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_Tarkeb_Marwaha') }} </label>
                            <input type="number" step="0.01" name="price_of_tarkeb_marwaha" class="form-control" value="{{ old('price_of_tarkeb_marwaha') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_One_Shara') }} </label>
                            <input type="number" step="0.01" name="price_of_one_shara" class="form-control" value="{{ old('price_of_one_shara') }}">
                        </div>
                    </div>

                    {{-- Rollout Table 3 --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_8_12_24') }} </label>
                            <input type="number" step="0.01" name="price_of_8_12_24" class="form-control" value="{{ old('price_of_8_12_24') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Price_of_48_72_96_144') }} </label>
                            <input type="number" step="0.01" name="price_of_48_72_96_144" class="form-control" value="{{ old('price_of_48_72_96_144') }}">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Submit') }}</button>
                        <a href="{{ route('jobOrderTypes.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection



