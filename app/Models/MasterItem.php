<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MasterItem extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'uuid',
        $guarded = [];

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class);
    }

    public function officeStockHistory()
    {
        return $this->hasOne(StockHistory::class)
            ->whereNull('master_ship_uuid')
            ->latest();
    }

    public function stockHistory()
    {
        return $this->hasOne(StockHistory::class)
            ->latest();
    }

    public function requestItemDetails()
    {
        return $this->hasMany(RequestItemDetail::class);
    }

    public function getBalanceOfficeStockAttribute()
    {
        $stockHistory = StockHistory::where('master_item_uuid', $this->attributes['uuid'])
            ->whereNull('master_ship_uuid')
            ->latest()
            ->first();

        return $stockHistory ? $stockHistory->total : 0;
    }
}
