@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $post->name }}</h1>
            <p class="text-muted">
                Tableau d'affichage:
                <a href="{{ route('bulletin-boards.show', $bulletinBoard) }}">{{ $bulletinBoard->name }}</a>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard, $post]) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('bulletin-boards.posts.destroy', [$bulletinBoard, $post]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('bulletin-boards.posts.index', $bulletinBoard) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        Détails de la publication
                        @if($post->status == 'published')
                            <span class="badge bg-success ms-2">Publié</span>
                        @elseif($post->status == 'draft')
                            <span class="badge bg-warning ms-2">Brouillon</span>
                        @elseif($post->status == 'cancelled')
                            <span class="badge bg-danger ms-2">Annulé</span>
                        @endif
                    </div>
                    <div>
                        <form action="{{ route('bulletin-boards.posts.toggle-status', [$bulletinBoard, $post]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $post->status == 'published' ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                {{ $post->status == 'published' ? 'Passer en brouillon' : 'Publier' }}
                            </button>
                        </form>
                        @if($post->status != 'cancelled')
                            <form action="{{ route('bulletin-boards.posts.cancel', [$bulletinBoard, $post]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Date de début:</strong> {{ $post->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date de fin:</strong> {{ $post->end_date ? $post->end_date->format('d/m/Y') : 'Non définie' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Créé par:</strong> {{ $post->creator->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Créé le:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <div class="border rounded p-3 bg-light">
                                {!! $post->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Pièces jointes ({{ $post->attachments->count() }})</span>
                    <a href="{{ route('bulletin-boards.posts.attachments.create', [$bulletinBoard, $post]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
                <div class="card-body" id="attachments-container">
                    @if($post->attachments->count() > 0)
                        <ul class="list-group">
                            @foreach($post->attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($attachment->thumbnail_path)
                                            <img src="{{ asset('storage/' . $attachment->thumbnail_path) }}" alt="Vignette" width="40" class="me-2">
                                        @else
                                            <i class="fas fa-file me-2"></i>
                                        @endif
                                        <a href="{{ route('attachments.preview', $attachment) }}" target="_blank">{{ $attachment->name }}</a>
                                        <small class="text-muted d-block">{{ $attachment->size }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"> </i> Télécharger
                                        </a>
                                        <form action="{{ route('bulletin-boards.posts.attachments.destroy', [$bulletinBoard, $post, $attachment->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">Aucune pièce jointe</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function humanFilesize(bytes, si=false, dp=1) {
        const thresh = si ? 1000 : 1024;
        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }
        const units = si
            ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
            : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        let u = -1;
        const r = 10**dp;
        do {
            bytes /= thresh;
            ++u;
        } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);
        return bytes.toFixed(dp) + ' ' + units[u];
    }
</script>
@endsection