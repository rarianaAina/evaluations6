@extends('layouts.master')

@section('content')
    <div class="container">
        <h2>{{ __('Import Clients via CSV') }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">{{ __('Select CSV File') }}</label>
                <input type="file" name="csv_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
        </form>
    </div>
@stop

@push('scripts')
    <script>
        $(function () {
            $('#import-form').submit(function () {
                $(this).find('button').prop('disabled', true);
            });
        });
    </script>
@endpush
