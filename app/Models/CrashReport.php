<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrashReport extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function masterShip()
    {
        return $this->belongsTo(MasterShip::class);
    }

    public function masterUser()
    {
        return $this->belongsTo(User::class);
    }

    public function crashReportDetails()
    {
        return $this->hasMany(CrashReportDetail::class);
    }
}
