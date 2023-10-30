<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';
    public $timestamps = false;
    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado',
        'total',
        'estado_orden_id',
        'datosfacturacion_id',
        'direccionesentrega_id',
        'subtotal12',
        'subtotaliva0',
        'subtotal_sin_impuestos',
        'descuento',
        'iva',
        'envio'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function estadoOrden()
    {
        return $this->belongsTo(EstadoOrden::class, 'estado_orden_id');
    }

    public function datosFacturacion()
    {
        return $this->belongsTo(DatosFacturacion::class, 'datosfacturacion_id');
    }

    public function direccionEntrega()
    {
        return $this->belongsTo(DireccionEntrega::class, 'direccionesentrega_id');
    }

    public function detallesOrden()
    {
        return $this->hasMany(DetalleOrden::class, 'orden_id');
    }
}
