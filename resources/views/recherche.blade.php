@extends('layouts.master')

@section('content')

    <h1><i class="bi bi-search"></i> Recherche visiteur</h1>

    <hr>
    <h3>Rechercher des visiteurs par nom, secteur ou labo</h3>
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

    <hr>
    <h3>Ou rechercher des visiteurs par région</h3>
    <form method="GET" action="{{ url('/region') }}">
        <div class="mb-3">
            <label class="form-label">Choisir une région</label>

            @php
                $regions = DB::table('region')->get();
            @endphp

            <select name="idRegion" class="form-control" required>
                <option value="">-- Sélectionner une région --</option>
                @foreach($regions as $r)
                    <option value="{{ $r->id_region }}">{{ $r->nom_region }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-search"></i> Rechercher
        </button>
    </form>


    @if(isset($erreur))
        <div class="alert alert-danger mt-3" role="alert">
            {{ $erreur }}
        </div>
    @endif

@endsection
