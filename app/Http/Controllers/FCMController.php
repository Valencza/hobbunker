<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;

class FCMController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendNotification(Request $request)
    {
        $title = $request->input('title');
        $body = $request->input('body');

        $users = User::all();

        $responses = [];
        foreach ($users as $user) {
            if ($user->fcm_token) {
                $response = $this->fcmService->sendNotification($title, $body, $user->fcm_token);
                $responses[] = $response;
            }
        }

        return response()->json($responses);
    }

    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->token]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }
}
