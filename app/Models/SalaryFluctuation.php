<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryFluctuation extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid', $guarded = [];

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }
}
