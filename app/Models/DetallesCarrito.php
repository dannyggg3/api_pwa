<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesCarrito extends Model
{
    protected $table = 'detallescarrito';
    public $timestamps = false;


    protected $fillable = [
        'cliente_id',
        'variante_id',
        'cantidad'
    ];


    public function variante()
    {
        return $this->belongsTo(Variante::class, 'variante_id');
    }
}
