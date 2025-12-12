@extends('layouts.master')

@section('content')

    <h1>Recherche d'un visiteur</h1>

    <form method="POST" action="{{ url('/rechercherVisiteur') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nom, secteur ou laboratoire</label>
            <input type="text" name="recherche" class="form-control" placeholder="Tapez un nom, un secteur ou un labo" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Rechercher
        </button>
    </form>

    @if(isset($erreur))
        <div class="alert alert-danger mt-3" role="alert">
            {{ $erreur }}
        </div>
    @endif

@endsection
