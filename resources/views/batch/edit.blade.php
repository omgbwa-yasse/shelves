@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __("Edit the initializer") }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('batch.update', $mailBatch->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="code">Reference</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ $mailBatch->code }}">
            </div>

            <div class="form-group">
                <label for="name">Desigantion </label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $mailBatch->name }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ __("Update initializer") }}</button>
        </form>
    </div>
@endsection
