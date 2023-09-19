<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $fillable = ['usuario_id', 'documento', 'nombre', 'direccion', 'telefono', 'estado', 'imagen', 'tipo_documento_id'];
    public $timestamps = false;
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }
}
