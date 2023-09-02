<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoAdicional extends Model
{
     use HasFactory;

    protected $table = 'info_adicional';
    protected $fillable = [
        'nombre',
        'descripcion',
        'ordenes_id',
    ];

    public $timestamps = false;

}
