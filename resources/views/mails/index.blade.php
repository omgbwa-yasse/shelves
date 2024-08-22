@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Liste des courriers</h1>

        <div class="row mb-3">
            <div class="col-md-8">
                <a href="{{ route('mails.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Nouveau courrier
                </a>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Rechercher un courrier...">
                </div>
            </div>
        </div>

        <div id="mailList">
            @foreach ($mails as $mail)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <h5 class="card-title mb-2">
                                  <b>{{ $mail->code }} </b> - {{ $mail->name }}
                                    <span class="badge bg-{{ $mail->priority->color ?? 'secondary' }}">
                                        {{ $mail->priority->name ?? '' }}
                                    </span>
                                </h5>
                                <p class="card-text mb-1">

                                    <strong> Description:</strong> {{ $mail->description }}<br>
                                   <strong>|  Auteur:</strong><i> {{ $mail->author }}</i>  <strong>|    Date :</strong> {{ $mail->date }}
                                    <strong>|  Type:</strong> {{ $mail->type->name ?? '' }}
                                </p>
                            </div>
                            <div class="col-md-3 text-md-end text-center">
                                <div class="d-flex justify-content-md-end justify-content-center align-items-center">
                                    @if($mail->attachments->count() > 0)
                                        <button class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#attachmentsModal{{ $mail->id }}">
                                            {{ $mail->attachments->count() }} <i class="bi bi-paperclip"></i>
                                        </button>
                                    @else
                                        <span class="text-muted me-2"><i class="bi bi-paperclip"></i> 0</span>
                                    @endif
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('mails.show', $mail->id) }}" class="btn btn-sm btn-outline-secondary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('mails.edit', $mail->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $mail->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
{{--            {{ $mails->links() }}--}}
        </div>

        <!-- Attachments Modals -->
        @foreach ($mails as $mail)
            @if($mail->attachments->count() > 0)
                <div class="modal fade" id="attachmentsModal{{ $mail->id }}" tabindex="-1" aria-labelledby="attachmentsModalLabel{{ $mail->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="attachmentsModalLabel{{ $mail->id }}">Pièces jointes - {{ $mail->code }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    @foreach($mail->attachments as $attachment)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $attachment->name }}
                                            <a href="{{ route('mail-attachment.show', [$mail->id, $attachment->id]) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(mailId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?')) {
                document.getElementById('delete-form-' + mailId).submit();
            }
        }

        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input, filter, cards, card, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            cards = document.getElementById('mailList').getElementsByClassName('card');

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
