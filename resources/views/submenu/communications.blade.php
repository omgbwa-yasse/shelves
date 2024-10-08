<div class="container">
    <div class="row">
        <!-- Recherche -->
        <a class="nav-link active bg-primary rounded-2 text-white" data-toggle="collapse" href="#rechercheMenu" aria-expanded="true"
            aria-controls="rechercheMenu" style="padding: 10px;">
            <i class="bi bi-search"></i> Communications
        </a>
        <div class="collapse show" id="rechercheMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('transactions.index')}}"><i class="bi bi-inbox"></i> Tout voir </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('communications-sort')}}?categ=return-effective"><i class="bi bi-inbox"></i> Returnés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('communications-sort')}}?categ=unreturn"><i class="bi bi-inbox"></i> Sans retour</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('communications-sort')}}?categ=not-return"><i class="bi bi-inbox"></i> Non returnés</a>
                </li>
                </ul>
        </div>



        <!-- Recherche -->
        <a class="nav-link active bg-primary rounded-2 text-white" data-toggle="collapse" href="#rechercheMenu" aria-expanded="true"
            aria-controls="rechercheMenu" style="padding: 10px;">
            <i class="bi bi-search"></i> Reservations
        </a>
        <div class="collapse show" id="rechercheMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('reservations.index')}}"><i class="bi bi-inbox"></i> Tout voir </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('reservations-sort')}}?categ=InProgess"><i class="bi bi-inbox"></i> En examen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('reservations-sort')}}?categ=approved"><i class="bi bi-inbox"></i> Approuvée</a>
                </li>

            </ul>
        </div>



        <!-- Communication -->
        <a class="nav-link active bg-primary rounded-2 text-white" data-toggle="collapse" href="#CommunicationMenu"
            aria-expanded="true" aria-controls="CommunicationMenu" style="padding: 10px;">
            <i class="bi bi-journal-plus"></i> Ajouter
        </a>
        <div class="collapse show" id="CommunicationMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('transactions.create')}}"><i class="bi bi-inbox"></i> Communication</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('reservations.create')}}"><i class="bi bi-envelope"></i> Réservation</a>
                </li>

            </ul>
        </div>
    </div>
</div>
