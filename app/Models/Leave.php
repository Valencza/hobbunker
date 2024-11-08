<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function getFormattedStartDateAttribute()
    {
        return Carbon::parse($this->attributes['start_date'])->format('d M Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return Carbon::parse($this->attributes['end_date'])->format('d M Y');
    }

    public function getTotalDateAttribute()
    {
        $start = Carbon::parse($this->attributes['start_date']);
        $end = Carbon::parse($this->attributes['end_date']);

        return $start->diffInDays($end);;
    }

    public function masterUser()
    {
        return $this->belongsTo(User::class, 'master_user_uuid');
    }

    public function approverMasterUser()
    {
        return $this->belongsTo(User::class, 'approver_master_user_uuid');
    }
}
