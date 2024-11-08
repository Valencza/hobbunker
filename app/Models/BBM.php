<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BBM extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'bbm',
        $primaryKey = 'uuid',
        $guarded = [];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }


    public function getTotalHourAttribute()
    {
        $startTime = Carbon::createFromFormat('H:i', '07:00');
        $endTime = Carbon::createFromFormat('H:i', '09:00');

        return $startTime->diffInHours($endTime);
    }

    public function masterShip()
    {
        return $this->belongsTo(MasterShip::class);
    }

    public function masterUser()
    {
        return $this->belongsTo(User::class);
    }

    public function masterMeachine()
    {
        return $this->belongsTo(MasterMeachine::class);
    }
}
