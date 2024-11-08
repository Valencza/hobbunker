<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Absent extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid', $guarded = [];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function masterUser()
    {
        return $this->belongsTo(User::class);
    }
}
