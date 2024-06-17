@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un courrier</h1>
        <form action="{{ route('mails.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Intitulé du courrier</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Producteurs</label>
                <input type="text" class="form-control" id="author" name="author" required>
                <div id="suggestions"></div>
            </div>
            <div class="mb-3">
                <label for="mail_priority_id" class="form-label">Priorité</label>
                <select class="form-select" id="mail_priority_id" name="mail_priority_id" required>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="mail_type_id" class="form-label">Type de courrier</label>
                <select class="form-select" id="mail_type_id" name="mail_type_id" required>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="mail_typology_id" class="form-label">Typologie</label>
                <select class="form-select" id="mail_typology_id" name="mail_typology_id" required>
                    @foreach ($typologies as $typology)
                        <option value="{{ $typology->id }}">{{ $typology->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="document_type_id" class="form-label">Nature</label>
                <select class="form-select" id="document_type_id" name="document_type_id" required>
                    @foreach ($documentTypes as $documentType)
                        <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>

    <script>
        const authorInput = document.getElementById('author');
        const suggestionsDiv = document.getElementById('suggestions');
        const authors = {!! json_encode($authors) !!};

        authorInput.addEventListener('input', function() {
            const inputValue = this.value.toLowerCase();
            const filteredAuthors = authors.filter(author => author.toLowerCase().startsWith(inputValue));

            suggestionsDiv.innerHTML = '';

            filteredAuthors.forEach(author => {
                const suggestionDiv = document.createElement('div');
                suggestionDiv.textContent = author;
                suggestionDiv.addEventListener('click', function() {
                    authorInput.value = author;
                    suggestionsDiv.innerHTML = '';
                });
                suggestionsDiv.appendChild(suggestionDiv);
            });
        });
    </script>

    @endsection
