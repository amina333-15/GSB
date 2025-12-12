<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Travailler;
use App\Models\Laboratoire;
use App\Models\Realiser;

class Visiteur extends Authenticatable
{
    use HasApiTokens;

    protected $hidden = [
        'pwd_visiteur',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->pwd_visiteur;
    }

    protected $table = 'visiteur';
    protected $primaryKey = 'id_visiteur';
    public $timestamps = false;

    // 🔥 Relation vers le laboratoire
    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'id_laboratoire');
    }

    // 🔥 Relation vers les affectations (table travailler)
    public function affectations()
    {
        return $this->hasMany(Travailler::class, 'id_visiteur');
    }

    // 🔥 Relation vers les activités réalisées
    public function activites()
    {
        return $this->hasMany(Realiser::class, 'id_visiteur');
    }
}
