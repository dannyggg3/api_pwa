<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
     use HasFactory;

    protected $table = 'empresa';
    public $timestamps = false;
    protected $fillable = [
        'ruc',
        'razon_social',
        'direccion',
        'obligado_contabilidad',
        'regimen',
        'logo',
        'ambiente',
        'establecimiento',
        'punto_emision',
        'secuencial',
        'archivop12',
        'usuario',
        'clave',
    ];
}
