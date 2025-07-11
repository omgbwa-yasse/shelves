@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier Courrier sortant</h1>
    <form action="{{ route('mail-send.update', $mail->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom du courrier</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $mail->name) }}" required>
        </div>

        <div class="form-group">
            <label for="date">Date du courrier</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $mail->date->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $mail->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="document_type">Type de document</label>
            <select name="document_type" id="document_type" class="form-select" required>
                <option value="">Sélectionner un type</option>
                <option value="original" {{ old('document_type', $mail->document_type) == 'original' ? 'selected' : '' }}>Original</option>
                <option value="duplicate" {{ old('document_type', $mail->document_type) == 'duplicate' ? 'selected' : '' }}>Duplicata</option>
                <option value="copy" {{ old('document_type', $mail->document_type) == 'copy' ? 'selected' : '' }}>Copie</option>
            </select>
        </div>


        <div class="form-group">
            <label for="typology_id">Typologie</label>
            <select name="typology_id" id="typology_id" class="form-control">
                @foreach($typologies as $typology)
                    <option value="{{ $typology->id }}" {{ old('typology_id', $mail->typology_id) == $typology->id ? 'selected' : '' }}>
                        {{ $typology->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
</div>


<script>

    const recipientOrganisationSelect = document.getElementById('recipient_organisation_id');
    const recipientUserSelect = document.getElementById('recipient_user_id');

        recipientUserSelect.disabled = true;

        recipientOrganisationSelect.addEventListener('change', function() {
            const organisationId = this.value;

            if (!organisationId) {
                recipientUserSelect.disabled = true;
                recipientUserSelect.innerHTML = '<option value="">Select a user</option>';
                return;
            }

            recipientUserSelect.disabled = false;

            recipientUserSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/mails/organizations/${organisationId}/users`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error while retrieving users');
                    }
                    return response.json();
                })
                .then(users => {
                    recipientUserSelect.innerHTML = '<option value="">Sélectionner un utilisateur</option>';
                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        recipientUserSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    recipientUserSelect.innerHTML = '<option value="">Error loading</option>';
                });
        });

<script/>

@endsection
