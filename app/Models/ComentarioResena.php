<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioResena extends Model
{
    protected $table = 'comentariosreseñas';
    protected $fillable = ['cliente_id', 'producto_id', 'comentario', 'puntuacion', 'fecha_comentario'];
    public $timestamps = false;

    // Relación con el cliente que hizo el comentario
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con el producto que se comentó
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
