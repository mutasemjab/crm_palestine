
@extends('layouts.admin')


@section('content')
<form action="{{ route('noteVouchers.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Upload Excel File</label>
        <input type="file" name="file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
</form>
<a href="{{ route('noteVouchers.import.sample') }}" class="btn btn-secondary">Download Sample File</a>

@endsection
