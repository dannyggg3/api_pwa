<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
     use HasFactory;

    protected $fillable = [
        'categoria_id',
        'marca_id',
        'nombre',
        'descripcion',
        'precio',
        'estado',
        'imagen',
        'caracteristicas',
    ];

    public $timestamps = false;

    // Definir las relaciones con las tablas Categoria y Marca
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

}
