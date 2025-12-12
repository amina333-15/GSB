<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realiser extends Model
{
    protected $table = 'realiser';
    public $timestamps = false;

    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class, 'id_visiteur');
    }

    public function activite()
    {
        return $this->belongsTo(ActiviteCompl::class, 'id_activite');
    }
}
