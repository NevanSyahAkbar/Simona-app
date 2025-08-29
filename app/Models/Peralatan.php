<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    protected $table = 'peralatans';

    protected $guarded = ['id']; // Asumsi Anda menggunakan ini


    use HasFactory, LogsActivity;
     protected $fillable = [
        'kode_peralatan',
        'tahun',
        'pekerjaan',
        'nd_ijin',
        'date_pr',
        'pr_number',
        'po_number',
        'gr_string', // WAJIB DITAMBAHKAN
        'nd_pembayaran',
        'dpp',
        'mitra',
        'status',
        'keterangan',
        'user_id',
    ];
    protected $casts = [
        'sync' => 'boolean',
    ];
}
