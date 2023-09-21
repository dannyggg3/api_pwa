<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquias extends Model
{
    use HasFactory;

    protected $table = 'parroquias';
     protected $primaryKey = 'id';
    protected $fillable = ['parroquia', 'ciudad_id'];
    public $timestamps = false;


    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id','id');
    }
}
