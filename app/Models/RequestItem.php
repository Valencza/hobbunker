<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestItem extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function getTotalItemAttribute()
    {
        return RequestItemDetail::where('request_item_uuid', $this->attributes['uuid'])
            ->sum('qty');
    }

    public function requestItemDetails()
    {
        return $this->hasMany(RequestItemDetail::class);
    }

    public function requestItemDocumentsItem()
    {
        return $this->hasMany(RequestItemDocument::class)
            ->where('type', 'barang');
    }

    public function requestItemDocumentsRoadLetter()
    {
        return $this->hasOne(RequestItemDocument::class)
            ->where('type', 'surat jalan');
    }

    public function acceptepRequestItemDetails()
    {
        return $this->hasMany(RequestItemDetail::class)
            ->where('reject', 0);
    }

    public function masterUser()
    {
        return $this->belongsTo(User::class, 'master_user_uuid');
    }


    public function masterShip()
    {
        return $this->belongsTo(MasterShip::class);
    }

    public function roadLetter()
    {
        return $this->hasOne(RoadLetter::class);
    }
}
