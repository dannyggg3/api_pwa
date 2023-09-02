<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaElectronica extends Model
{
    use HasFactory;
    

    protected $table = 'facturas_electronicas';
    public $timestamps = false;
    protected $fillable = [
        'factura',
        'estado',
        'numero_autorizacion',
        'clave_acceso',
        'fecha',
        'descargada',
        'ordenes_id',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'ordenes_id');
    }
}
