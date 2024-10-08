@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <h1 class="mb-4"><i class="bi bi-list-ul me-2"></i>Inventaire des archives {{ $title ?? ''}}</h1>

        <div id="recordList">
            @foreach ($records as $record)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-9">

                                <h5 class="card-title mb-2">
                                    <b>{{ $record->code }}  - {{ $record->name }}</b>
                                    <span class="badge bg-{{ $record->level->color ?? 'secondary' }}">
                                        {{ $record->level->name ?? 'N/A' }}
                                    </span>
                                </h5>

                                <p class="card-text">
                                    <i class="bi bi-card-text me-2"></i><strong>Content:</strong> {{ $record->content }}<br>
                                    <i class="bi bi-bar-chart-fill me-2"></i><strong>Level:</strong> {{ $record->level->name ?? 'N/A' }}
                                    <i class="bi bi-flag-fill me-2"></i><strong>Status:</strong> {{ $record->status->name ?? 'N/A' }}
                                    <i class="bi bi-hdd-fill me-2"></i><strong>Support:</strong> {{ $record->support->name ?? 'N/A' }}
                                    <i class="bi bi-activity me-2"></i><strong>Activity:</strong> {{ $record->activity->name ?? 'N/A' }}
                                    <i class="bi bi-calendar-event me-2"></i><strong>Dates:</strong> {{ $record->date_start ?? 'N/A' }} - {{ $record->date_end ?? 'N/A' }}
                                    <i class="bi bi-geo-alt-fill me-2"></i><strong>Location:</strong> {{ $record->location_original ?? 'N/A' }}
                                    <i class="bi bi-people-fill me-2"></i><strong>Authors:</strong> {{ $record->authors->pluck('name')->join(', ') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-3 text-md-end text-center">

                                <div class="d-flex justify-content-md-end justify-content-center align-items-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('records.show', $record) }}" class="btn btn-sm btn-outline-secondary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('records.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('records.destroy', $record) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    @push('scripts')
        <script>
            document.getElementById('searchInput').addEventListener('keyup', function() {
                var input, filter, cards, card, i, txtValue;
                input = document.getElementById('searchInput');
                filter = input.value.toUpperCase();
                cards = document.getElementById('recordList').getElementsByClassName('card');

                for (i = 0; i < cards.length; i++) {
                    card = cards[i];
                    txtValue = card.textContent || card.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }
                }
            });
        </script>
    @endpush
@endsection
