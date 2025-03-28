<div class="container" style="background-color: #f1f1f1;">
    <div class="row">
        <!-- Recherche -->
        <a class="nav-link active bg-primary text-white" data-toggle="collapse" href="#bulletinboardsearch" aria-expanded="true" aria-controls="bulletinboardsearch" style="padding: 10px;">
            <i class="bi bi-search"></i> Recherche
        </a>
        <div class="collapse show" id="bulletinboardsearch">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('bulletin-boards.index') }}">
                        <i class="bi bi-grid"></i> Babillards
                    </a>
                </li>
            </ul>
        </div>

        <!-- Création -->
        <a class="nav-link active bg-primary text-white" data-toggle="collapse" href="#bulletinboardcreate" aria-expanded="true" aria-controls="bulletinboardcreate" style="padding: 10px;">
            <i class="bi bi-plus-circle"></i> Création
        </a>
        <div class="collapse show" id="bulletinboardcreate">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('bulletin-boards.create') }}">
                        <i class="bi bi-pin-angle"></i> Nouveau babillard
                    </a>
                </li>
            </ul>
        </div>


            <!-- Administration -->
            <a class="nav-link active bg-primary text-white" data-toggle="collapse" href="#bulletinboardadmin" aria-expanded="true" aria-controls="bulletinboardadmin" style="padding: 10px;">
                <i class="bi bi-gear"></i> Administration
            </a>
            <div class="collapse show" id="bulletinboardadmin">
                <ul class="list-unstyled pl-3">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('bulletin-boards.admin.index') }}">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('bulletin-boards.admin.settings') }}">
                            <i class="bi bi-sliders"></i> Paramètres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('bulletin-boards.admin.users') }}">
                            <i class="bi bi-people"></i> Utilisateurs
                        </a>
                    </li>
                </ul>
            </div>

    </div>
</div>
