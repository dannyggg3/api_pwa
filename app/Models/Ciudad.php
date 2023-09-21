<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'ciudades';
     protected $primaryKey = 'id';
    protected $fillable = ['ciudad', 'provincias_id'];
    public $timestamps = false;

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincias_id','id');
    }

    public function parroquia()
    {
        return $this->hasMany(Parroquias::class, 'ciudad_id');
    }

}
