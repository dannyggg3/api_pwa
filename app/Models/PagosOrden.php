<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagosOrden extends Model
{
     protected $table = 'pagos_orden';
        public $timestamps = false;

    protected $fillable = [
        'total',
        'plazo',
        'tiempo',
        'formas_pago_id',
        'ordenes_id',
    ];

    public function formaPago()
    {
        return $this->belongsTo(FormasPago::class, 'formas_pago_id');
    }

    public function orden()
    {
        return $this->belongsTo(Ordenes::class, 'ordenes_id');
    }
}
