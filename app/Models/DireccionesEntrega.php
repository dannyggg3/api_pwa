<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionesEntrega extends Model
{
    
    protected $table = 'direccionesentrega';
    public $timestamps = false;
    protected $fillable = [
        'cliente_id',
        'cedula',
        'direccion',
        'estado',
        'ciudades_id',
        'comentarios',
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudades::class, 'ciudades_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }
}
