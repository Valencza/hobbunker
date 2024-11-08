<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'uuid',
        $guarded = [];

        public function getFormattedCreatedAtAttribute()
        {
            return Carbon::parse($this->attributes['created_at'])->format('d M Y');
        }
    
        public function getFormattedDateAttribute()
        {
            return Carbon::parse($this->attributes['date'])->format('d M Y');
        }

    public function masterUser(){
        return $this->belongsTo(User::class);
    }

    public function masterShip(){
        return $this->belongsTo(MasterShip::class);
    }

    public function inspectionCrews(){
        return $this->hasMany(InspectionCrew::class);
    }
}
