<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    protected $table = 'secteur';
    protected $primaryKey = 'id_secteur';
    public $timestamps = false;

    public function affectations()
    {
        return $this->hasMany(Travailler::class, 'id_secteur');
    }
}
