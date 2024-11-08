<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingOffice extends Model
{
    use HasFactory;

    protected $table = 'setting_office',
        $primaryKey = 'uuid',
        $guarded = [];
}
