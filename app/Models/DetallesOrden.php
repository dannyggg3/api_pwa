<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesOrden extends Model
{
    protected $table = 'detallesorden';
    public $timestamps = false;

    protected $fillable = [
        'orden_id',
        'variante_id',
        'cantidad',
        'subtotal',
        'iva'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function variante()
    {
        return $this->belongsTo(Variante::class, 'variante_id');
    }
}
