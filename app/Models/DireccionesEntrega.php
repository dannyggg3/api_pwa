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
        'parroquia_id',
        'comentarios',
        'postal',
        'num_casa'
    ];

    public function parroquia()
    {
        return $this->belongsTo(Parroquias::class, 'parroquia_id', 'id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
