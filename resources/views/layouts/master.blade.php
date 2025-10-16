<!doctype html>
<html lang="fr">

<head>
    <title>GSB Frais</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/css/gsb.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body class="body">

<div>
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">GSBfrais</a>
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @if(session('id_visiteur'))
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/listerFrais') }}"><i class="bi bi-text-paragraph"></i> Lister</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/ajouterFrais') }}"><i class="bi bi-plus-circle-fill"></i> Ajouter</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/deconnecter') }}">( <i class="bi bi-person-fill-check"> {{session('visiteur')}} </i>)  <i class="bi bi-power"></i> Se déconnecter</a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/connecter') }}">Se connecter</a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>

</div>
<div class="container">
    @yield("content")
</div>
<script src="/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>
