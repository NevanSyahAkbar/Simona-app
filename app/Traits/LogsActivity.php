<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'dibuat');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'diperbarui');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'dihapus');
        });
    }

    protected static function logActivity($model, $action)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => sprintf(
                "User '%s' telah %s data %s dengan ID #%d",
                Auth::user()->name,
                $action,
                class_basename($model), // Mendapatkan nama model (e.g., 'Perlengkapan')
                $model->id
            )
        ]);
    }
}
