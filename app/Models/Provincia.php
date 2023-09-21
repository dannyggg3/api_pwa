<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $table = 'provincias';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'provincia',
    ];

    //relacion a ciudad
    public function ciudad()
    {
        return $this->hasMany(Ciudad::class, 'provincia_id');
    }
}
