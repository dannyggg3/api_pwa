<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoCompra extends Model
{
    
    protected $table = 'carritocompras';
    protected $fillable = ['cliente_id', 'estado'];
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
