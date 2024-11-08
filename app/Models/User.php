<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected $table = 'master_users',
        $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAbsentTodayAttribute()
    {
        $absent = Absent::where('master_user_uuid', Auth::user()->uuid)
            ->whereDate('created_at', Carbon::today())
            ->first();

        return $absent ? true : false;
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birth'])->age;
    }

    public function getFormattedBirthAttribute()
    {
        return Carbon::parse($this->attributes['birth'])->format('d M Y');
    }

    public function getLeaveRequestAttribute()
    {
        $currentYear = Carbon::now()->year;

        return Leave::whereYear('created_at', $currentYear)
            ->where('master_user_uuid', $this->attributes['uuid'])
            ->count() ?? 0;
    }

    public function getLeaveAcceptedAttribute()
    {
        $currentYear = Carbon::now()->year;

        return Leave::whereYear('created_at', $currentYear)
            ->where('master_user_uuid', $this->attributes['uuid'])
            ->where('status', 'disetujui')
            ->count() ?? 0;
    }

    public function getLeaveTotalAttribute()
    {
        $currentYear = Carbon::now()->year;

        return Leave::whereYear('created_at', $currentYear)
            ->where('master_user_uuid', $this->attributes['uuid'])
            ->where('status', 'disetujui')
            ->get()
            ->sum(function ($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date));
            }) ?? 0;
    }


    public function getLeaveRejectedAttribute()
    {
        $currentYear = Carbon::now()->year;

        return Leave::whereYear('created_at', $currentYear)
            ->where('master_user_uuid', $this->attributes['uuid'])
            ->where('status', 'ditolak')
            ->count() ?? 0;
    }

    public function masterRole()
    {
        return $this->belongsTo(MasterRole::class);
    }

    public function masterDivision()
    {
        return $this->hasOne(MasterDivision::class, 'master_user_uuid');
    }

    public function masterShip()
    {
        return $this->belongsTo(MasterShip::class);
    }

    public function absents()
    {
        return $this->hasMany(Absent::class, 'master_user_uuid');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'master_user_uuid');
    }

    public function salaryFluctuation()
    {
        return $this->hasOne(SalaryFluctuation::class, 'master_user_uuid')->latest();
    }

    public function salaryFluctuations()
    {
        return $this->hasMany(SalaryFluctuation::class, 'master_user_uuid');
    }

    public function headDivision()
    {
        return $this->belongsTo(User::class, 'master_user_uuid', 'uuid');
    }
}
