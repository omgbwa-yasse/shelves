<!-- resources/views/bulletin-boards/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un Babillard</h1>
        <form action="{{ route('bulletin-boards.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="organisations">Organisations</label>
                <select name="organisations[]" class="form-control" multiple>
                    @foreach($organisations as $organisation)
                        <option value="{{ $organisation->id }}">{{ $organisation->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Créer</button>
        </form>
    </div>
@endsection
