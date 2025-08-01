<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil semua log, urutkan dari yang terbaru, dan gunakan paginasi
        $logs = ActivityLog::with('user')->orderBy('id', 'desc')->paginate(20);

        return view('pages.activity_log.index', compact('logs'));
    }
}
