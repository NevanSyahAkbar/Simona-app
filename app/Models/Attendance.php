<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * Diasumsikan nama tabel adalah 'attendances'.
     * @var string
     */
    protected $table = 'tkbm_presensi';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'time_in',
        'time_out',
        'present_date',
        'machine_id',
        'shift_id',
        'spk',
        'sync',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'present_date' => 'date',
        'sync' => 'boolean', // tinyint(1) sering digunakan sebagai boolean
    ];

    /**
     * created_at dan updated_at akan diurus otomatis oleh Eloquent.
     * Tidak perlu dimasukkan ke $fillable.
     */
}
