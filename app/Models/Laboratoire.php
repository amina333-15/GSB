<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    protected $table = 'travailler';
    public $timestamps = false;

    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class, 'id_visiteur');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }

    public function secteur()
    {
        return $this->belongsTo(Secteur::class, 'id_secteur');
    }
}
