<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DockingDetail extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid',
        $guarded = [];
}
