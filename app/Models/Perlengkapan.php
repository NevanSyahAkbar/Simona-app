<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perlengkapan extends Model
{
    use HasFactory;

    use HasFactory, LogsActivity;
    protected $fillable = [
    'tahun', 'sub_bagian', 'pekerjaan', 'date_nd_user', 'date_survey', 'date_nd_ijin',
    'date_pr', 'pr_number', 'po_number', 'gr_number', 'order_padi', 'bast_user',
    'nd_pembayaran', 'dpp', 'mitra', 'status', 'keterangan', 'user_id'
    ];

}
