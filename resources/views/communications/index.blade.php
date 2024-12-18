@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        <h1><i class="bi bi-file-earmark-spreadsheet"></i> {{ __('Communications') }} {{ $title ?? ''}}</h1>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> {{ __('Fill a form') }}
        </a>

        <div class="d-flex justify-content-between align-items-center bg-light p-3 mb-3">
            <div class="d-flex align-items-center">
                <a href="#" id="cartBtn" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-cart me-1"></i>
                    {{ __('Cart') }}
                </a>
                <a href="#" id="exportBtn" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-download me-1"></i>
                    {{ __('Export') }}
                </a>
                <a href="#" id="printBtn" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-printer me-1"></i>
                    {{ __('Print') }}
                </a>
            </div>
            <div class="d-flex align-items-center">
                <a href="#" id="checkAllBtn" class="btn btn-light btn-sm">
                    <i class="bi bi-check-square me-1"></i>
                    {{ __('Check all') }}
                </a>
            </div>
        </div>
        <div class="row">
            <div id="communicationsList" class="mb-4">
                @foreach ($communications as $communication)
                    <div class="mb-3" style="transition: all 0.3s ease; transform: translateZ(0);">
                        <div class="card-header bg-light d-flex align-items-center py-2" style="border-bottom: 1px solid rgba(0,0,0,0.125);">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="{{ $communication->id }}" id="communication-{{ $communication->id }}" />
                            </div>
                            <button class="btn btn-link btn-sm text-secondary text-decoration-none p-0 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $communication->id }}" aria-expanded="false" aria-controls="details-{{ $communication->id }}">
                                <i class="bi bi-chevron-down fs-5"></i>
                            </button>
                            <h4 class="card-title flex-grow-1 m-0 text-primary" for="communication-{{ $communication->id }}">
                                <a href="{{ route('transactions.show', $communication->id ?? '') }}" class="text-decoration-none text-dark">
                                    <span class="fs-5 fw-semibold">{{ $communication->code ?? 'N/A' }}</span>
                                    <span class="fs-5"> : {{ $communication->name ?? 'N/A' }}</span>
                                </a>
                            </h4>
                        </div>
                        <div class="collapse" id="details-{{ $communication->id }}">
                            <div class="card-body bg-white">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <p class="mb-2"><i class="bi bi-card-text me-2 text-primary"></i><strong>{{ __('Content') }} :</strong> {{ $communication->content ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="bi bi-person-fill me-2 text-primary"></i><strong>{{ __('Requester') }} :</strong>
                                            <a href="{{ route('communications-sort') }}?user={{ $communication->user->id }}">{{ $communication->user->name ?? 'N/A' }}</a>
                                            (<a href="{{ route('communications-sort') }}?user_organisation={{ $communication->userOrganisation->id ?? '' }}">{{ $communication->userOrganisation->name ?? 'N/A' }}</a>)
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-person-badge-fill me-2 text-primary"></i><strong>{{ __('Operator') }} :</strong>
                                            <a href="{{ route('communications-sort') }}?operator={{ $communication->operator->id }}">{{ $communication->operator->name ?? 'N/A' }}</a>
                                            (<a href="{{ route('communications-sort') }}?operator_organisation={{ $communication->operatorOrganisation->id ?? '' }}">{{ $communication->operatorOrganisation->name ?? 'N/A' }}</a>)
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="bi bi-calendar-event me-2 text-primary"></i><strong>{{ __('Return date') }} :</strong> {{ $communication->return_date ?? 'N/A' }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-calendar-check me-2 text-primary"></i><strong>{{ __('Effective return date') }} :</strong> {{ $communication->return_effective ?? 'N/A' }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-info-circle-fill me-2 text-primary"></i><strong>{{ __('Status') }} :</strong>
                                            <a href="{{ route('communications-sort') }}?status={{ $communication->status->id }}">{{ $communication->status->name ?? 'N/A' }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <footer class="bg-light py-3">
        <div class="container">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $communications->currentPage() == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $communications->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @for ($i = 1; $i <= $communications->lastPage(); $i++)
                        <li class="page-item {{ $communications->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $communications->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $communications->currentPage() == $communications->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $communications->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </footer>
@endsection
@push('scripts')
    <script>
        document.getElementById('cartBtn').addEventListener('click', function(e) {
            e.preventDefault();
            let checkedCommunications = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                .map(checkbox => checkbox.value);

            if (checkedCommunications.length === 0) {
                alert("{{ __('Please select at least one communication to add to the cart.') }}");
                return;
            }

            fetch('{{ route('dolly.createWithCommunications') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ communications: checkedCommunications })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Optionnel : rediriger vers la page du chariot nouvellement créé
                        // window.location.href = '/dolly/' + data.dolly_id;
                    } else {
                        alert("{{ __('An error occurred while creating the cart.') }}");
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert("{{ __('An error occurred while creating the cart.') }}");
                });
        });
        document.getElementById('exportBtn').addEventListener('click', function(e) {
            e.preventDefault();
            let checkedCommunications = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                .map(checkbox => checkbox.value);

            if (checkedCommunications.length === 0) {
                alert("{{ __('Please select at least one communication to export.') }}");
                return;
            }

            window.location.href = `{{ route('communications.export') }}?communications=${checkedCommunications.join(',')}`;
        });

        document.getElementById('printBtn').addEventListener('click', function(e) {
            e.preventDefault();
            let checkedCommunications = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                .map(checkbox => checkbox.value);

            if (checkedCommunications.length === 0) {
                alert("{{ __('Please select at least one communication to print.') }}");
                return;
            }

            // Rediriger vers la route d'impression avec les IDs des communications sélectionnées
            window.location.href = `{{ route('communications.print') }}?communications=${checkedCommunications.join(',')}`;
        });
        document.getElementById('checkAllBtn').addEventListener('click', function(e) {
            e.preventDefault();
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            let allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = !allChecked;
            });

            this.innerHTML = allChecked ? '<i class="bi bi-check-square me-1"></i>{{ __("Check all") }}' : '<i class="bi bi-square me-1"></i>{{ __("Uncheck all") }}';
        });

        document.addEventListener('DOMContentLoaded', function() {
            const collapseElements = document.querySelectorAll('.collapse');
            collapseElements.forEach(collapse => {
                collapse.addEventListener('show.bs.collapse', function () {
                    const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
                    button.querySelector('i').classList.replace('bi-chevron-down', 'bi-chevron-up');
                });
                collapse.addEventListener('hide.bs.collapse', function () {
                    const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
                    button.querySelector('i').classList.replace('bi-chevron-up', 'bi-chevron-down');
                });
            });

            // Gestion du "voir plus / voir moins" pour le contenu
            document.querySelectorAll('.content-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const targetElement = document.getElementById(targetId);
                    const fullText = this.getAttribute('data-full-text');

                    if (this.textContent === "{{ __('See more') }}") {
                        targetElement.textContent = fullText;
                        this.textContent = "{{ __('See less') }}";
                    } else {
                        targetElement.textContent = fullText.substr(0, 200) + '...';
                        this.textContent = "{{ __('See more') }}";
                    }
                });
            });
        });
    </script>
@endpush
