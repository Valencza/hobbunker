<?php

namespace App\Console;

use App\Models\Absent;
use App\Models\Leave;
use App\Models\SettingAbsent;
use App\Models\User;
use App\Services\FCMService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            Log::info('Cronjob berhasil dijalankan');

            $currentDate = Carbon::now();

            if (!$currentDate->isSunday()) {
                $users = User::all();

                $fcmService = new FCMService();
    
                $settingAbsent = SettingAbsent::first();
    
                $title = 'Absen Masuk';
                $body = 'Jangan lupa melakukan absen masuk sebelum pukul ' . $settingAbsent->clock_in_limit;
    
                $responses = [];
                foreach ($users as $user) {
                    $absent = Absent::whereDate('created_at', Carbon::today())
                        ->where('master_user_uuid', $user->uuid)
                        ->first();
    
                    $currentTime = Carbon::now();
                    $startTime = Carbon::createFromTimeString($settingAbsent->clock_in);
                    $endTime = Carbon::createFromTimeString($settingAbsent->clock_in_limit);
    
                    if (!$absent && $currentTime->between($startTime, $endTime)) {
                        if ($user->fcm_token) {
                            $response = $fcmService->sendNotification($title, $body, $user->fcm_token);
                            $responses[] = $response;
                        }
                    }
                }
    
                $title = 'Absen Pulang';
                $body = 'Jangan lupa melakukan absen pulang sebelum pukul ' . $settingAbsent->clock_out_limit;
    
                $responses = [];
    
                $currentTime = Carbon::now();
    
                foreach ($users as $user) {
                    $absent = Absent::whereDate('created_at', Carbon::today())
                        ->where('master_user_uuid', $user->uuid)
                        ->first();
    
                    $startTime = Carbon::createFromTimeString($settingAbsent->clock_out);
                    $endTime = Carbon::createFromTimeString($settingAbsent->clock_out_limit);
    
                    if (($absent && !$absent->checkout_clock) && $currentTime->between($startTime, $endTime)) {
                        if ($user->fcm_token) {
                            $response = $fcmService->sendNotification($title, $body, $user->fcm_token);
                            $responses[] = $response;
                        }
                    }
                }
    
                $currentTime = Carbon::now();
                $startTime = Carbon::today()->setTime(1, 0);
                $endTime = Carbon::today()->setTime(3, 0);
                
                Log::info('Cek Kehadiran');
                if ($currentTime->between($startTime, $endTime)) {
                    $users = User::all();
                    Log::info('Masuk Jam Cek Kehadiran');
    
                    foreach ($users as $user) {
                        $check = Absent::whereDate('created_at', Carbon::yesterday())
                            ->where('checkin_status', '!=', 'tidak hadir')
                            ->where('checkin_status', '!=', 'cuti')
                            ->where('master_user_uuid', $user->uuid)
                            ->first();
    
                        if (!$check) {
                            Log::info("$user->email belum melakukan presensi");
    
                            $today = Carbon::today()->toDateString();
    
                            $leave = Leave::where('master_user_uuid', $user->uuid)
                                ->latest()
                                ->where('status', 'disetujui')
                                ->whereDate('start_date', '<=', $today)
                                ->whereDate('end_date', '>=', $today)
                                ->first();
    
                            Absent::create([
                                'master_user_uuid' => $user->uuid,
                                'status' => $leave ? 'cuti' : 'tidak hadir',
                                'date' => Carbon::yesterday(),
                                'created_at' => Carbon::yesterday()
                            ]);
                        }
    
                        $check = Absent::whereDate('created_at', Carbon::yesterday())
                            ->whereNull('checkout_status')
                            ->first();
    
                        if ($check) {
                            Log::info("$user->email belum melakukan checkout");
        
                            $check->checkout_status = 'tidak checkout';
                            $check->save();
                        }
                    }
                }
            } 
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
