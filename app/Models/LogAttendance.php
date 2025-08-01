<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class LogAttendance extends Model
{
    //
    //protected $table = "log_attendance";
    protected $table = "logs";
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable = ['id', 'machine_id', 'employee_no', 'name','card_no', 'event_type', 'attendance_status', 'is_synced', 'created_at', 'updated_at'];


    public function scopeDoesntSync($query)
    {
        return $query->where('sync', 0);
    }
}
