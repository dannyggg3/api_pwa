<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertasEspeciales extends Model
{
     protected $table = 'ofertasespeciales';
     public $timestamps = false;
    protected $fillable = [
        'producto_id',
        'descuento',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
