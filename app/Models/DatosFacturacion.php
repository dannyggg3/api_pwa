<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosFacturacion extends Model
{
     protected $table = 'datosfacturacion';
    protected $fillable = [
        'cliente_id',
        'nombre',
        'ruc_cedula',
        'direccion',
        'ciudad',
        'telefono',
        'tipo_documento_id',
    ];

    public $timestamps = false;

    //funcion para relacionar con la tabla cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }


    //funcion para relacionar con la tabla tipo documento
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }
}
