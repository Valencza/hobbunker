<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DropItemDetail extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function requestItemDetailStock()
    {
        return $this->belongsTo(RequestItemDetailStock::class);
    }

    public function dropItem()
    {
        return $this->hasOne(DropItem::class, 'uuid', 'drop_item_uuid');
    }
}
