<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaans';

    protected $guarded = ['id']; // Asumsi Anda menggunakan ini

    use HasFactory, LogsActivity;
    protected $fillable = [
            'kode_pemeliharaan',
            'pekerjaan',
            'laporan_bulanan',
            'bast',
            'bapf',
            'bap',
            'dok_tagihan',
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
