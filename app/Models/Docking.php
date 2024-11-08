<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docking extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function getFormattedCostAttribute()
    {
        if ($this->attributes['cost'] >= 1000000000) {
            return $this->attributes['cost'] / 1000000000 . 'M';
        } else if ($this->attributes['cost'] >= 1000000) {
            return $this->attributes['cost'] / 1000000 . 'JT';
        } elseif ($this->attributes['cost'] >= 100000) {
            return $this->attributes['cost'] / 1000 . 'RB';
        } elseif ($this->attributes['cost'] >= 1000) {
            return number_format($this->attributes['cost'], 0, '', '.');
        } else {
            return $this->attributes['cost'];
        }
    }

    public function masterShip()
    {
        return $this->belongsTo(MasterShip::class);
    }
}
