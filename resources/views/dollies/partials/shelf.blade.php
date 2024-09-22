<div class="row g-4">
    @foreach ([
        ['icon' => 'bi-trash', 'text' => 'Vider le chariot', 'action' => 'clean', 'color' => 'danger'],
        ['icon' => 'bi-house-door', 'text' => 'Changer de salle d\'archives', 'action' => 'room', 'color' => 'primary'],
        ['icon' => 'bi-x-circle', 'text' => 'Supprimer de la base', 'action' => 'delete', 'color' => 'danger'],
    ] as $item)
        <div class="col-md-4 col-sm-6">
            <div class="card action-card h-100">
                <div class="card-body text-center">
                    <i class="bi {{ $item['icon'] }} action-icon text-{{ $item['color'] }}"></i>
                    <h5 class="card-title">{{ $item['text'] }}</h5>
                </div>
                <a href="{{ route('dollies.action', ['categ' => $dolly->type->name, 'action' => $item['action'], 'id' => $dolly->id]) }}" class="btn btn-{{ $item['color'] }} btn-action">Exécuter</a>
            </div>
        </div>
    @endforeach
</div>