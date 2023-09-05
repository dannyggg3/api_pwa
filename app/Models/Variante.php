<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variante extends Model
{
     use HasFactory;

    protected $table = 'variantes'; // Nombre de la tabla

    public $timestamps = false; // No contiene los campos created_at ni updated_at en la tabla

    protected $fillable = [
        'producto_id',
        'color',
        'talla',
        'stock',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
