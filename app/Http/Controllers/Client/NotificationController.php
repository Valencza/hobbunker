<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notificationsUser = Notification::where('master_user_uuid', Auth::user()->uuid);

        // Today's Data
        $today = $notificationsUser->whereDate('created_at', Carbon::today())->get();

        // Yesterday's Data
        $yesterday = $notificationsUser->whereDate('created_at', Carbon::yesterday())->get();

        // Other Data Except Today and Yesterday
        $other = $notificationsUser->where(function ($query) {
            $query->whereDate('created_at', '<', Carbon::today())
                ->orWhereDate('created_at', '<', Carbon::yesterday());
        })->get();

        return view('client.pages.notification.index', compact(
            'today',
            'yesterday',
            'other'
        ));
    }
}
