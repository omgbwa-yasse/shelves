@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __("Edit Building") }}</h1>
        <form action="{{ route('buildings.update', $building->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">{{ __("Name") }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $building->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __("Description") }}</label>
                <textarea class="form-control" id="description" name="description">{{ $building->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ __("Update") }}</button>
        </form>
    </div>
@endsection
