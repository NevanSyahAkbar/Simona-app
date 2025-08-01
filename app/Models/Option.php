<?php
// FILE: app/Models/Option.php
// ============================
// Ini adalah bagian yang SANGAT PENTING.
// Pastikan file model Anda sama persis seperti ini.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'value',
    ];
}
