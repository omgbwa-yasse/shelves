@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('bulletin-boards.index') }}">Tableaux d'affichage</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bulletin-boards.show', $bulletinBoard['id']) }}">{{ $bulletinBoard->name }}</a></li>
                    <li class="breadcrumb-item active">Publications</li>
                </ol>
            </nav>
            <h1>Liste des publications</h1>
            <p class="text-muted">{{ $bulletinBoard->name }}</p>
        </div>
        <div class="col-lg-4 text-lg-end d-flex justify-content-lg-end align-items-center">
            @if($bulletinBoard->hasWritePermission(Auth::id()))
                <a href="{{ route('bulletin-boards.posts.create', $bulletinBoard['id']) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Créer une publication
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <ul class="nav nav-tabs card-header-tabs" id="postTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="active-tab" data-bs-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">
                            <i class="fas fa-thumbtack me-1"></i> Actives
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="scheduled-tab" data-bs-toggle="tab" href="#scheduled" role="tab" aria-controls="scheduled" aria-selected="false">
                            <i class="fas fa-clock me-1"></i> Planifiées
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="expired-tab" data-bs-toggle="tab" href="#expired" role="tab" aria-controls="expired" aria-selected="false">
                            <i class="fas fa-history me-1"></i> Expirées
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">
                            <i class="fas fa-list me-1"></i> Toutes
                        </a>
                    </li>
                </ul>

                <div class="dropdown mt-2 mt-md-0">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item {{ request('status') == 'all' || !request('status') ? 'active' : '' }}" href="{{ route('bulletin-boards.posts.index', [$bulletinBoard['id']]) }}">Tous les statuts</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'published' ? 'active' : '' }}" href="{{ route('bulletin-boards.posts.index', [$bulletinBoard['id'], 'status' => 'published']) }}">Publiés</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'draft' ? 'active' : '' }}" href="{{ route('bulletin-boards.posts.index', [$bulletinBoard['id'], 'status' => 'draft']) }}">Brouillons</a></li>
                        <li><a class="dropdown-item {{ request('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('bulletin-boards.posts.index', [$bulletinBoard['id'], 'status' => 'cancelled']) }}">Annulés</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="tab-content" id="postTabsContent">
                <!-- Publications actives -->
                <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Créé par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $activePosts = $posts->filter(function($post) {
                                        return $post->isActive() && $post->status != 'draft';
                                    });
                                @endphp

                                @forelse($activePosts as $post)
                                    <tr>
                                        <td>
                                            <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="fw-bold text-decoration-none">
                                                {{ $post->name }}
                                            </a>
                                            @if($post->attachments && $post->attachments->isNotEmpty())
                                                <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i> {{ $post->attachments->count() }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>Depuis {{ $post->start_date->format('d/m/Y') }}</div>
                                            @if($post->end_date)
                                                <div class="text-muted small">jusqu'au {{ $post->end_date->format('d/m/Y') }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $post->creator->name }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($post->canBeEditedBy(Auth::user()))
                                                    <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-thumbtack fa-2x mb-3"></i>
                                                <p>Aucune publication active n'est disponible.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vue mobile actives -->
                    <div class="d-md-none">
                        @forelse($activePosts as $post)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="text-decoration-none">
                                                {{ $post->name }}
                                            </a>
                                            @if($post->attachments && $post->attachments->isNotEmpty())
                                                <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i></span>
                                            @endif
                                        </h5>
                                        <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </div>
                                    <p class="card-text small">
                                        <i class="fas fa-calendar-alt text-muted me-1"></i> Depuis {{ $post->start_date->format('d/m/Y') }}
                                        @if($post->end_date)
                                            <br><i class="fas fa-calendar-check text-muted me-1"></i> Jusqu'au {{ $post->end_date->format('d/m/Y') }}
                                        @endif
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Par {{ $post->creator->name }}</small>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($post->canBeEditedBy(Auth::user()))
                                                <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-thumbtack fa-2x mb-3"></i>
                                    <p>Aucune publication active n'est disponible.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Publications planifiées -->
                <div class="tab-pane fade" id="scheduled" role="tabpanel" aria-labelledby="scheduled-tab">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Créé par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $scheduledPosts = $posts->filter(function($post) {
                                        return $post->isScheduled();
                                    });
                                @endphp

                                @forelse($scheduledPosts as $post)
                                    <tr>
                                        <td>
                                            <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="fw-bold text-decoration-none">
                                                {{ $post->name }}
                                            </a>
                                            @if($post->attachments && $post->attachments->isNotEmpty())
                                                <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i> {{ $post->attachments->count() }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>Début le {{ $post->start_date->format('d/m/Y') }}</div>
                                            @if($post->end_date)
                                                <div class="text-muted small">jusqu'au {{ $post->end_date->format('d/m/Y') }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $post->creator->name }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($post->canBeEditedBy(Auth::user()))
                                                    <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-clock fa-2x mb-3"></i>
                                                <p>Aucune publication planifiée n'est disponible.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vue mobile planifiées -->
                    <div class="d-md-none">
                        @forelse($scheduledPosts as $post)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="text-decoration-none">
                                                {{ $post->name }}
                                            </a>
                                            @if($post->attachments && $post->attachments->isNotEmpty())
                                                <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i></span>
                                            @endif
                                        </h5>
                                        <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </div>
                                    <p class="card-text small">
                                        <p class="card-text small">
                                            <i class="fas fa-calendar-alt text-muted me-1"></i> Début le {{ $post->start_date->format('d/m/Y') }}
                                            @if($post->end_date)
                                                <br><i class="fas fa-calendar-check text-muted me-1"></i> Jusqu'au {{ $post->end_date->format('d/m/Y') }}
                                            @endif
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Par {{ $post->creator->name }}</small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($post->canBeEditedBy(Auth::user()))
                                                    <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-clock fa-2x mb-3"></i>
                                        <p>Aucune publication planifiée n'est disponible.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Publications expirées -->
                    <div class="tab-pane fade" id="expired" role="tabpanel" aria-labelledby="expired-tab">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Créé par</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $expiredPosts = $posts->filter(function($post) {
                                            return $post->isExpired() && $post->status != 'draft';
                                        });
                                    @endphp

                                    @forelse($expiredPosts as $post)
                                        <tr>
                                            <td>
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="fw-bold text-decoration-none">
                                                    {{ $post->name }}
                                                </a>
                                                @if($post->attachments && $post->attachments->isNotEmpty())
                                                    <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i> {{ $post->attachments->count() }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>Du {{ $post->start_date->format('d/m/Y') }}</div>
                                                <div class="text-muted small">au {{ $post->end_date->format('d/m/Y') }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $post->creator->name }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($post->canBeEditedBy(Auth::user()))
                                                        <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-history fa-2x mb-3"></i>
                                                    <p>Aucune publication expirée n'est disponible.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Vue mobile expirées -->
                        <div class="d-md-none">
                            @forelse($expiredPosts as $post)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="text-decoration-none">
                                                    {{ $post->name }}
                                                </a>
                                                @if($post->attachments && $post->attachments->isNotEmpty())
                                                    <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i></span>
                                                @endif
                                            </h5>
                                            <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </div>
                                        <p class="card-text small">
                                            <i class="fas fa-calendar-alt text-muted me-1"></i> Du {{ $post->start_date->format('d/m/Y') }}
                                            <br><i class="fas fa-calendar-check text-muted me-1"></i> Au {{ $post->end_date->format('d/m/Y') }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Par {{ $post->creator->name }}</small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($post->canBeEditedBy(Auth::user()))
                                                    <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-history fa-2x mb-3"></i>
                                        <p>Aucune publication expirée n'est disponible.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Toutes les publications -->
                    <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Créé par</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($posts as $post)
                                        <tr>
                                            <td>
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="fw-bold text-decoration-none">
                                                    {{ $post->name }}
                                                </a>
                                                @if($post->attachments && $post->attachments->isNotEmpty())
                                                    <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i> {{ $post->attachments->count() }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $post->start_date->format('d/m/Y') }}</div>
                                                @if($post->end_date)
                                                    <div class="text-muted small">jusqu'au {{ $post->end_date->format('d/m/Y') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $post->creator->name }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($post->canBeEditedBy(Auth::user()))
                                                        <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-list fa-2x mb-3"></i>
                                                    <p>Aucune publication n'est disponible.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Vue mobile toutes -->
                        <div class="d-md-none">
                            @forelse($posts as $post)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="text-decoration-none">
                                                    {{ $post->name }}
                                                </a>
                                                @if($post->attachments && $post->attachments->isNotEmpty())
                                                    <span class="badge bg-info ms-1"><i class="fas fa-paperclip"></i></span>
                                                @endif
                                            </h5>
                                            <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </div>
                                        <p class="card-text small">
                                            <i class="fas fa-calendar-alt text-muted me-1"></i> {{ $post->start_date->format('d/m/Y') }}
                                            @if($post->end_date)
                                                <br><i class="fas fa-calendar-check text-muted me-1"></i> Jusqu'au {{ $post->end_date->format('d/m/Y') }}
                                            @endif
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Par {{ $post->creator->name }}</small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('bulletin-boards.posts.show', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($post->canBeEditedBy(Auth::user()))
                                                    <a href="{{ route('bulletin-boards.posts.edit', [$bulletinBoard['id'], $post->id]) }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-list fa-2x mb-3"></i>
                                        <p>Aucune publication n'est disponible.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>

        <div class="mt-4">
            <a href="{{ route('bulletin-boards.show', $bulletinBoard['id']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour au tableau d'affichage
            </a>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restore active tab from localStorage if available
            const activeTabId = localStorage.getItem('activePostTab');
            if (activeTabId) {
                const tab = document.querySelector(activeTabId);
                if (tab) {
                    const tabInstance = new bootstrap.Tab(tab);
                    tabInstance.show();
                }
            }

            // Save active tab to localStorage when clicked
            const tabs = document.querySelectorAll('a[data-bs-toggle="tab"]');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    localStorage.setItem('activePostTab', '#' + event.target.id);
                });
            });
        });
    </script>
    @endsection
