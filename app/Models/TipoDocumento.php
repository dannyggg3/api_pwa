<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipo_documento'; // Nombre de la tabla
    public $timestamps = false; // No contiene los campos created_at ni updated_at en la tabla

    protected $fillable = [
        'nombre',
        'valor',
    ];
}
