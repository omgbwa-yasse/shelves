@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/mail-actions.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <!-- En-tête avec navigation -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Détails du courrier sortant</h1>
            <div class="btn-group">
                <a href="{{ route('mails.outgoing.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- Actions rapides - Barre horizontale compacte -->
        <div class="mail-actions-bar">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Actions principales -->
                <div class="d-flex flex-wrap gap-2">
                    <span class="mail-actions-label">
                        <i class="bi bi-lightning-fill text-warning"></i> Actions :
                    </span>

                    <a href="{{ route('mails.outgoing.edit', $mail->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>

                    @if($mail->attachments->count() > 0)
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#attachmentsModal">
                            <i class="bi bi-paperclip"></i> Pièces jointes
                            <span class="badge bg-white text-info">{{ $mail->attachments->count() }}</span>
                        </button>
                    @endif

                    <button class="btn btn-success btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimer
                    </button>

                    <button class="btn btn-secondary btn-sm" onclick="downloadPDF()">
                        <i class="bi bi-file-pdf"></i> PDF
                    </button>

                    @if($mail->status && $mail->status->value === 'in_progress')
                        <button class="btn btn-warning btn-sm" onclick="markAsSent()">
                            <i class="bi bi-check-circle"></i> Marquer envoyé
                        </button>
                    @endif
                </div>

                <!-- Action de suppression alignée à droite -->
                <div class="ms-auto">
                    <form action="{{ route('mails.outgoing.destroy', $mail->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?')">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="row">
            <!-- Colonne de gauche - Informations générales -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Code :</strong>
                                <span class="badge bg-secondary ms-2">{{ $mail->code }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Date :</strong>
                                <span class="ms-2">{{ $mail->date->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Nom :</strong>
                            <div class="mt-1">{{ $mail->name }}</div>
                        </div>

                        @if($mail->description)
                            <div class="mb-3">
                                <strong>Description :</strong>
                                <div class="mt-1">{{ $mail->description }}</div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Type de document :</strong>
                                <div class="mt-1">
                                    <span class="badge bg-info">{{ ucfirst($mail->document_type) }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Typologie :</strong>
                                <div class="mt-1">
                                    @if($mail->typology)
                                        <span class="badge bg-primary">{{ $mail->typology->name }}</span>
                                    @else
                                        <span class="text-muted">Non définie</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Statut :</strong>
                                <div class="mt-1">
                                    @if($mail->status)
                                        <span class="badge bg-success">{{ ucfirst($mail->status->value) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($mail->priority || $mail->action)
                            <div class="row">
                                @if($mail->priority)
                                    <div class="col-md-6 mb-3">
                                        <strong>Priorité :</strong>
                                        <div class="mt-1">
                                            <span class="badge bg-warning">{{ $mail->priority->name }}</span>
                                            <small class="text-muted">({{ $mail->priority->duration }} jours)</small>
                                        </div>
                                    </div>
                                @endif
                                @if($mail->action)
                                    <div class="col-md-6 mb-3">
                                        <strong>Action :</strong>
                                        <div class="mt-1">
                                            <span class="badge bg-success">{{ $mail->action->name }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne de droite - Informations destinataire et envoi -->
            <div class="col-md-4">
                <!-- Destinataire -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-person-check"></i> Destinataire</h6>
                    </div>
                    <div class="card-body">
                        @if($mail->externalRecipient)
                            <div class="mb-2">
                                <strong>{{ $mail->externalRecipient->full_name }}</strong>
                            </div>
                            @if($mail->externalRecipient->position)
                                <div class="text-muted mb-1">{{ $mail->externalRecipient->position }}</div>
                            @endif
                            @if($mail->externalRecipient->email)
                                <div><i class="bi bi-envelope"></i> {{ $mail->externalRecipient->email }}</div>
                            @endif
                            @if($mail->externalRecipient->phone)
                                <div><i class="bi bi-telephone"></i> {{ $mail->externalRecipient->phone }}</div>
                            @endif
                            @if($mail->externalRecipient->organization)
                                <div class="mt-2">
                                    <small class="text-muted">Organisation :</small><br>
                                    <strong>{{ $mail->externalRecipient->organization->name }}</strong>
                                </div>
                            @endif
                        @elseif($mail->externalRecipientOrganization)
                            <div class="mb-2">
                                <strong>{{ $mail->externalRecipientOrganization->name }}</strong>
                            </div>
                            @if($mail->externalRecipientOrganization->email)
                                <div><i class="bi bi-envelope"></i> {{ $mail->externalRecipientOrganization->email }}</div>
                            @endif
                            @if($mail->externalRecipientOrganization->phone)
                                <div><i class="bi bi-telephone"></i> {{ $mail->externalRecipientOrganization->phone }}</div>
                            @endif
                            @if($mail->externalRecipientOrganization->city)
                                <div><i class="bi bi-geo-alt"></i> {{ $mail->externalRecipientOrganization->city }}</div>
                            @endif
                        @else
                            <div class="text-muted">Destinataire non défini</div>
                        @endif
                    </div>
                </div>

                <!-- Informations d'envoi -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bi bi-send"></i> Expéditeur</h6>
                    </div>
                    <div class="card-body">
                        @if($mail->sender)
                            <div class="mb-2">
                                <strong>{{ $mail->sender->name }}</strong>
                            </div>
                            <div class="text-muted">Utilisateur interne</div>
                        @elseif($mail->senderOrganisation)
                            <div class="mb-2">
                                <strong>{{ $mail->senderOrganisation->name }}</strong>
                            </div>
                            <div class="text-muted">Organisation interne</div>
                        @endif
                    </div>
                </div>

                <!-- Informations d'envoi -->
                @if($mail->delivery_method || $mail->tracking_number || $mail->sent_at)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="bi bi-truck"></i> Envoi</h6>
                        </div>
                        <div class="card-body">
                            @if($mail->delivery_method)
                                <div class="mb-2">
                                    <strong>Méthode :</strong> {{ $mail->delivery_method }}
                                </div>
                            @endif
                            @if($mail->tracking_number)
                                <div class="mb-2">
                                    <strong>N° de suivi :</strong> {{ $mail->tracking_number }}
                                </div>
                            @endif
                            @if($mail->sent_at)
                                <div class="mb-2">
                                    <strong>Envoyé le :</strong> {{ $mail->sent_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pièces jointes -->
        @if($mail->attachments->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-paperclip"></i> Pièces jointes ({{ $mail->attachments->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($mail->attachments as $attachment)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        @if($attachment->thumbnail_path)
                                            <img src="{{ Storage::url($attachment->thumbnail_path) }}" class="img-fluid mb-2" style="max-height: 100px;" alt="{{ $attachment->name }}">
                                        @else
                                            <i class="bi bi-file-earmark fs-1 text-muted"></i>
                                        @endif
                                        <h6 class="card-title">{{ $attachment->name }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">{{ number_format($attachment->size / 1024, 1) }} KB</small>
                                        </p>
                                        <a href="{{ Storage::url($attachment->path) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="bi bi-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal pour les pièces jointes -->
    @if($mail->attachments->count() > 0)
        <div class="modal fade" id="attachmentsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pièces jointes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            @foreach($mail->attachments as $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $attachment->name }}</h6>
                                        <small class="text-muted">{{ number_format($attachment->size / 1024, 1) }} KB - {{ $attachment->mime_type }}</small>
                                    </div>
                                    <a href="{{ Storage::url($attachment->path) }}" class="btn btn-sm btn-primary" target="_blank">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function downloadPDF() {
            // Fonction pour exporter en PDF (à implémenter selon vos besoins)
            alert('Fonctionnalité d\'export PDF à implémenter');
        }

        function markAsSent() {
            // Fonction pour marquer comme envoyé (à implémenter selon vos besoins)
            if (confirm('Marquer ce courrier comme envoyé ?')) {
                alert('Fonctionnalité à implémenter');
            }
        }
    </script>
@endsection
