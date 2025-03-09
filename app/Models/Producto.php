<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'fecha',
        'codigo',
        'descripcion',
        'udm',
        'id_categoria',
        'id_almacen',
        'stock_minimo',
        'inventario',
        'estatus'
    ];
}
