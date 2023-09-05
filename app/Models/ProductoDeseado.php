<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoDeseado extends Model
{
     use HasFactory;

    protected $table = 'productosdeseados';

    protected $fillable = [
        'cliente_id',
        'producto_id',
        'estado',
    ];

    public $timestamps = false;

    // Definir las relaciones con las tablas Cliente y Producto
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
