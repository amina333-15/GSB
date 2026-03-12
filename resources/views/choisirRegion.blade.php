@extends('layouts.master')

@section('content')

    <h1>Choisir une région</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Région</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($regions as $r)
            <tr>
                <td>{{ $r->nom_region }}</td>
                <td>
                    <a href="{{ url('/region/'.$r->id_region.'/visiteurs') }}"
                       class="btn btn-primary btn-sm">
                        Voir les visiteurs
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
