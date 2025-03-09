<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $fillable = [
        'producto',
        'unidad',
        'cantidad',
        'id_user',
        'usuario',
        'comentarios',
        'fecha',
        'estatus'
    ];
}
