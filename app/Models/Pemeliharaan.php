<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
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
}
