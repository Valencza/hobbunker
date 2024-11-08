<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestItemDetail extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function masterItem()
    {
        return $this->belongsTo(MasterItem::class);
    }

    public function requestItem()
    {
        return $this->hasOne(RequestItem::class, 'uuid', 'request_item_uuid');
    }
}
