@extends('layouts.app')


    <style>
        .form-title {
            color: #333;
            /*font-weight: 600;*/
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        .send-btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .send-btn:hover {
            background-color: #0056b3;
        }
        .modal-content {
            border: none;
            border-radius: 8px;
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
        .list-group-item {
            cursor: pointer;
        }
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="">
                <div class="batch-send-container">
                    <h2 class="form-title">Envoi de Parapheur</h2>
                    <div class="">
                        <form action="{{ route('batch-send.store') }}" method="POST" id="batchSendForm">
                            @csrf
                            <div class="mb-3">
                                <label for="batchInput" class="form-label">Parapheur</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-folder2-open"></i></span>
                                    <input type="text" class="form-control" id="batchInput" readonly required>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#batchModal">
                                        Sélectionner
                                    </button>
                                </div>
                                <input type="hidden" name="batch_id" id="batch_id">
                            </div>
                            <div class="mb-3">
                                <label for="organisationInput" class="form-label">Organisation de réception</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control" id="organisationInput" readonly required>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#organisationModal">
                                        Sélectionner
                                    </button>
                                </div>
                                <input type="hidden" name="organisation_received_id" id="organisation_received_id">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="send-btn">
                                    <i class="bi bi-send-fill"></i> Envoyer le parapheur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la sélection du parapheur -->
    <div class="modal fade" id="batchModal" tabindex="-1" aria-labelledby="batchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchModalLabel">Sélectionner un parapheur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="batchSearch" class="form-control mb-3" placeholder="Rechercher un parapheur...">
                    <div id="batchList" class="list-group">
                        @foreach ($batches as $batch)
                            <a href="#" class="list-group-item list-group-item-action" data-id="{{ $batch->id }}" data-code="{{ $batch->code }}" data-name="{{ $batch->name }}">
                                {{ $batch->code }} - {{ $batch->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la sélection de l'organisation -->
    <div class="modal fade" id="organisationModal" tabindex="-1" aria-labelledby="organisationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="organisationModalLabel">Sélectionner une organisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="organisationSearch" class="form-control mb-3" placeholder="Rechercher une organisation...">
                    <div id="organisationList" class="list-group">
                        @foreach ($organisations as $organisation)
                            <a href="#" class="list-group-item list-group-item-action" data-id="{{ $organisation->id }}">
                                {{ $organisation->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('batchSendForm');
            const batchInput = document.getElementById('batchInput');
            const batchId = document.getElementById('batch_id');
            const organisationInput = document.getElementById('organisationInput');
            const organisationId = document.getElementById('organisation_received_id');

            function setupModal(modalId, searchId, listId, input, hiddenInput) {
                const modal = document.getElementById(modalId);
                const search = document.getElementById(searchId);
                const list = document.getElementById(listId);
                const items = list.querySelectorAll('.list-group-item');

                search.addEventListener('input', function() {
                    const filter = this.value.toLowerCase();
                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(filter) ? '' : 'none';
                    });
                });

                items.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        input.value = this.textContent.trim();
                        hiddenInput.value = this.dataset.id;
                        bootstrap.Modal.getInstance(modal).hide();
                    });
                });
            }

            setupModal('batchModal', 'batchSearch', 'batchList', batchInput, batchId);
            setupModal('organisationModal', 'organisationSearch', 'organisationList', organisationInput, organisationId);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (batchId.value && organisationId.value) {
                    Swal.fire({
                        title: 'Confirmer l\'envoi',
                        text: `Voulez-vous envoyer le parapheur "${batchInput.value}" à "${organisationInput.value}" ?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, envoyer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Veuillez sélectionner un parapheur et une organisation de réception.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection
