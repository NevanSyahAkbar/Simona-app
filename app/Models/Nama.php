<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nama extends Model
{
    protected $table="nama";
    use HasFactory;
     protected $fillable = [
        'kelamin',
        'umur',

    ];
}
