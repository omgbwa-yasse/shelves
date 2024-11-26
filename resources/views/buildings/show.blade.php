@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __("Building details") }}</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $building->name }}</h5>
                <p class="card-text">{{ $building->description }}</p>
                <a href="{{ route('buildings.index') }}" class="btn btn-secondary btn-sm">{{ __("Back") }}</a>
                <a href="{{ route('buildings.edit', $building->id) }}" class="btn btn-warning btn-sm">{{ __("Edit") }}</a>
                <form action="{{ route('buildings.destroy', $building->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this building?')">{{ __("Delete") }}</button>
                </form>
                <a href="{{ route('floors.create', $building) }}" class="btn btn-warning btn-sm align-right">{{ __("Ajouter un niveau au bâtiment") }}</a>
            </div>
        </div>

        @if($building->floors->isEmpty())
            {{ __("This building is not on the first floor") }}
        @else
            <div class="list-group">
                @foreach($building->floors as $floor)
                    <a href="{{ route('floors.show',  [$building , $floor] ) }}" class="list-group-item list-group-item-action">{{ $floor->name }} : {{ $floor->description }} </a>
                @endforeach
            </div>
        @endif

    </div>
@endsection
