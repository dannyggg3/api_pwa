<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{

    use HasFactory;

    protected $table = 'usuarios'; // Nombre de la tabla
    public $timestamps = false; // No contiene los campos created_at ni updated_at en la tabla

    protected $fillable = [
        'nombre',
        'correo_electronico',
        'contrasena',
        'rol_id',
        'estado',
        'imagen',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
