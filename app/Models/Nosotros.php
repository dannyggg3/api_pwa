<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nosotros extends Model
{
    use HasFactory;

    protected $table = 'nosotros';
    protected $fillable = [
        'mision',
        'vision',
        'historia'
    ];

    public $timestamps = false;
}
