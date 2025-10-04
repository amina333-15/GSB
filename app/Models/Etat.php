<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Frais extends Model
{
    protected $table ='etat';
    protected $primaryKey = 'id_etat';
    public $timestamps = false;
}
