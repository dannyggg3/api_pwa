<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';
    protected $fillable = [
        'usuario_id',
        'mensaje',
        'leida',
        'fecha_envio',
    ];

    public $timestamps = false;


    public function usuario()
{
    return $this->belongsTo(Usuario::class, 'usuario_id');
}

   
}
