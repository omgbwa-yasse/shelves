<div class="container" style="background-color: #f1f1f1;"> <!-- Couleur de fond marron -->
    <div class="row">
        <a class="nav-link active bg-dark text-white" data-toggle="collapse" href="#rechercheMenu" aria-expanded="true"
            aria-controls="rechercheMenu" style="padding: 10px;"><i class="bi bi-search"></i> Recherche</a>

        <div class="collapse show" id="rechercheMenu">

            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mails.index') }}"><i class="bi bi-inbox"></i> Courrier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('batch.index') }}"><i class="bi bi-inbox"></i> Parapheur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mail-received.index') }}"><i class="bi bi-inbox"></i> Reçus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mail-send.index') }}"><i class="bi bi-envelope"></i> Envoyés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mail-author.index') }}"><i class="bi bi-envelope"></i> Producteurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('typologies.index') }}"><i class="bi bi-tags"></i> Typologies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#"><i class="bi bi-calendar"></i> Dates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mails.archived') }}"><i class="bi bi-inbox"></i> Courrier archivé</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mail-container.index') }}"><i class="bi bi-archive"></i> Boîtes d'archives</a>
                </li>

            </ul>
        </div>

        <a class="nav-link active bg-dark text-white" data-toggle="collapse" href="#enregistrementMenu"
            aria-expanded="true" aria-controls="enregistrementMenu" style="padding: 10px;">Créer</a>

            <div class="collapse show" id="enregistrementMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mails.create') }}"><i class="bi bi-inbox"></i> Courrier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('batch.create') }}"><i class="bi bi-bookmark-check"></i> Parapheur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mail-author.create') }}"><i class="bi bi-people"></i> Producteur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('mail-container.create') }}"><i class="bi bi-archive"></i> Boîte & Chrono</a>
                </li>
            </ul>
        </div>


        <a class="nav-link active bg-dark text-white" data-toggle="collapse" href="#enregistrementMenu"
        aria-expanded="true" aria-controls="enregistrementMenu" style="padding: 10px;">Courrier</a>

            <div class="collapse show" id="enregistrementMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('mail-received.create') }}"><i class="bi bi-inbox"></i> Reçu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('mail-send.create') }}"><i class="bi bi-envelope"></i> Envoyé</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('mail-archiving.create') }}"><i class="bi bi-archive"></i> Archiver </a>
            </li>
                </ul>
            </div>

            <a class="nav-link active bg-dark text-white" data-toggle="collapse" href="#enregistrementMenu"
            aria-expanded="true" aria-controls="enregistrementMenu" style="padding: 10px;">Parapheur</a>

            <div class="collapse show" id="enregistrementMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('batch_received.index') }}"><i class="bi bi-inbox"></i> Reçus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('batch_send.index') }}"><i class="bi bi-inbox"></i> Envoyés</a>
                </li>
                </ul>
            </div>
            <div>
            </ul>
        </div>
    </div>
</div>
