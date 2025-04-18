@extends('layouts.app')

@section('content')
<div id="mailList">
    <div class="container-fluid">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Courriers entrants</h1>

        <div class="d-flex justify-content-between align-items-center bg-light p-3 mb-3">
            <div class="d-flex align-items-center">
                <a href="#" id="cartBtn" class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#dolliesModal">
                    <i class="bi bi-cart me-1"></i>
                    Chariot
                </a>
                <a href="#" id="cartBtnWindow" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-cart me-1"></i>
                    Chariot
                </a>
                <a href="#" id="exportBtn" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-download me-1"></i>
                    Exporter
                </a>
                <a href="#" id="printBtn" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-printer me-1"></i>
                    Imprimer
                </a>
            </div>
            <div class="d-flex align-items-center">
                <a href="#" id="checkAllBtn" class="btn btn-light btn-sm">
                    <i class="bi bi-check-square me-1"></i>
                    Tout cocher
                </a>
            </div>
        </div>

        <div id="mailList" class="mb-4">
            @foreach ($mails as $mail)
                <div class="mb-3" style="transition: all 0.3s ease; transform: translateZ(0);">
                    <div class="card-header bg-light d-flex align-items-center py-2" style="border-bottom: 1px solid rgba(0,0,0,0.125);">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="{{ $mail->id }}" id="mail_{{ $mail->id }}" name="selected_mail[]" />
                        </div>

                        <button class="btn btn-link btn-sm text-secondary text-decoration-none p-0 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#mail-{{ $mail->id }}" aria-expanded="false" aria-controls="mail-{{ $mail->id }}">
                            <i class="bi bi-chevron-down fs-5"></i>
                        </button>

                        <h4 class="card-title flex-grow-1 m-0" for="mail_{{ $mail->id }}">
                            <a href="{{ route('mail-received.show', $mail) }}" class="text-decoration-none text-dark">
                                <span class="fs-5 fw-semibold">{{ $mail->code ?? 'N/A' }}</span>
                                <span class="fs-5"> - {{ $mail->name ?? 'N/A' }}</span>
                                <span class="badge bg-danger ms-2">{{ $mail->action->name ?? 'N/A' }}</span>
                            </a>
                        </h4>
                    </div>

                    <div class="collapse" id="mail-{{ $mail->id }}">
                        <div class="card-body bg-white">
                            @if($mail->description)
                                <div class="mb-3">
                                    <p class="mb-2">
                                        <i class="bi bi-card-text me-2 text-primary"></i>
                                        <strong>Description:</strong> {{ $mail->description }}
                                    </p>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-2">
                                        <i class="bi bi-person-fill me-2 text-primary"></i>
                                        <strong>Envoyé par:</strong>
                                        {{ $mail->sender->name ?? 'N/A' }} ({{ $mail->senderOrganisation->name ?? 'N/A' }})
                                        <br>

                                        <i class="bi bi-person-fill me-2 text-primary"></i>
                                        <strong>Reçu par:</strong>
                                        {{ $mail->recipient->name ?? 'N/A' }} ({{ $mail->recipientOrganisation->name ?? 'N/A' }})
                                        <br>

                                        <i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>
                                        <strong>Type de document:</strong>
                                        {{ $mail->document_type ?? 'N/A' }}
                                        <br>

                                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                                        <strong>Date:</strong>
                                        {{ date('d/m/Y', strtotime($mail->date)) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <div class="modal fade" id="dolliesModal" tabindex="-1" aria-labelledby="dolliesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dolliesModalLabel">Chariot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="dolliesList">
                        @foreach ($dollies as $dolly)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $dolly->name }}</h5>
                                    <p class="card-text">{{ $dolly->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="dollyForm" style="display: none;">
                        <form id="createDollyForm" action="{{ route('dolly.create') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="type_id" class="form-label">Type</label>
                                <select class="form-select" id="type_id" name="type_id" required>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}" {{ $type->name == 'mail' ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter au chariot</button>
                        </form>
                        <button type="button" class="btn btn-secondary mt-2" id="backToListBtn">Retour à la liste</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="addDollyBtn">Ajouter un Dolly</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-header {
            transition: background-color 0.2s ease;
        }

        .card-header:hover {
            background-color: #f8f9fa !important;
        }

        .bi {
            font-size: 0.9rem;
        }

        .badge {
            font-weight: 500;
        }

        .collapse {
            transition: all 0.3s ease-out;
        }

        .btn-link:focus {
            box-shadow: none;
        }

        .bi-chevron-down {
            transition: transform 0.3s ease;
        }

        [aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }
    </style>
    <script>

            document.addEventListener('DOMContentLoaded', function() {
                const addDollyBtn = document.getElementById('addDollyBtn');
                const dolliesList = document.getElementById('dolliesList');
                const dollyForm = document.getElementById('dollyForm');
                const dollyFormForm = dollyForm.querySelector('form');
                const backToListBtn = document.getElementById('backToListBtn');

                addDollyBtn.addEventListener('click', function() {
                    dolliesList.style.display = 'none';
                    dollyForm.style.display = 'block';
                });




                document.getElementById('cartBtnWindow').addEventListener('click', function(e) {
                    e.preventDefault();

                    // Collecte des IDs sélectionnés
                    const selectedIds = [];
                    document.querySelectorAll('input[name="selected_mail[]"]:checked').forEach(checkbox => {
                        selectedIds.push(checkbox.value);
                    });

                    // Créer une URL avec les IDs comme paramètres de requête
                    const ids = encodeURIComponent(JSON.stringify(selectedIds));
                    const url = `/mails/chart?ids=${ids}`;

                    window.open(url, 'Chariot', 'width=300,height=500,resizable=yes');
                });




                // Afficher le formulaire de création de dolly

                backToListBtn.addEventListener('click', function() {
                    dolliesList.style.display = 'block';
                    dollyForm.style.display = 'none';
                });

                dollyFormForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);
                    const selectedMailIds = [];
                    document.querySelectorAll('input[name="selected_mail[]"]:checked').forEach(checkbox => {
                        selectedMailIds.push(checkbox.value);
                    });
                    formData.append('selectedIds', JSON.stringify(selectedMailIds));

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Mettre à jour la liste des dollies dans la modale
                        const dolliesHTML = data.dollies.map(dolly => `
                        <div class="card mb-3">
                            <div class="card-body">
                            <h5 class="card-title">${dolly.name}</h5>
                            <p class="card-text">${dolly.description}</p>
                            </div>
                        </div>
                        `).join('');
                        dolliesList.innerHTML = dolliesHTML;

                        // Afficher la liste des dollies et masquer le formulaire
                        dolliesList.style.display = 'block';
                        dollyForm.style.display = 'none';

                        // Réinitialiser le formulaire
                        this.reset();
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
                });

                // Tout cocher / Décocher
                const checkAllBtn = document.getElementById('checkAllBtn');
                checkAllBtn.addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('input[name="selected_mail[]"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });




                // Exporter les courriers cochés avec POST et CSRF token
                const exportBtn = document.getElementById('exportBtn');
                exportBtn.addEventListener('click', function() {
                    const selectedIds = [];
                    document.querySelectorAll('input[name="selected_mail[]"]:checked').forEach(checkbox => {
                        selectedIds.push(checkbox.value);
                    });

                    if (selectedIds.length === 0) {
                        alert('Veuillez sélectionner au moins un courrier.');
                        return;
                    }

                    // Utiliser fetch avec méthode POST et CSRF token
                    fetch('{{ route("mail-transaction.export") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ selectedIds: selectedIds })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau: ' + response.status);
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Création du lien de téléchargement
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'export_courriers.csv'; // Ajustez le nom et l'extension selon vos besoins
                        document.body.appendChild(a);
                        a.click();

                        // Nettoyage
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'exportation:', error);
                        alert('Une erreur est survenue lors de l\'exportation.');
                    });
                });

                // Imprimer les courriers cochés
                const printBtn = document.getElementById('printBtn');
                printBtn.addEventListener('click', function() {
                    const selectedIds = [];
                    document.querySelectorAll('input[name="selected_mail[]"]:checked').forEach(checkbox => {
                        selectedIds.push(checkbox.value);
                    });

                    if (selectedIds.length === 0) {
                        alert('Veuillez sélectionner au moins un courrier.');
                        return;
                    }

                    // Version améliorée pour l'impression avec POST
                    fetch('{{ route("mail-transaction.print") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ selectedIds: selectedIds })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau: ' + response.status);
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Créer une URL pour le blob
                        const url = window.URL.createObjectURL(blob);
                        // Ouvrir dans une nouvelle fenêtre pour impression
                        const printWindow = window.open(url, '_blank');
                        // Déclencher l'impression automatiquement (facultatif)
                        if (printWindow) {
                            printWindow.addEventListener('load', function() {
                                printWindow.print();
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'impression:', error);
                        alert('Une erreur est survenue lors de la préparation de l\'impression.');
                    });
                });
            });


    </script>
@endsection
