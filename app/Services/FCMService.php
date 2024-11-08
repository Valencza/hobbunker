<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FCMService
{
    protected $client;
    protected $projectId;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => public_path('cacert.pem') // Update this line
        ]);
        $this->projectId = 'hobbunker-b4824'; // replace with your Firebase project ID
    }

    public function sendNotification($title, $body, $token, $data = [])
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $message = [
            "message" => [
                "token" => $token,
                "notification" => [
                    "title" => $title,
                    "body" => $body
                ]
            ]
        ];


        $headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];


        Log::info('FCM message: ' . json_encode($message));

        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'json' => $message,
            ]);

            Log::info('FCM response: ' . $response->getBody());
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
            return $this->getAccessToken();
        }
    }

    private function getAccessToken()
    {
        $serviceAccount = json_decode(file_get_contents(public_path('hobbunker-b4824-firebase-adminsdk-zwh42-fa9c797dcb.json')), true);

        $now = time();
        $expires = $now + 3600; // Token valid for 1 hour

        $payload = [
            'iss' => $serviceAccount['client_email'],
            'sub' => $serviceAccount['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $expires,
            'scope' => 'https://www.googleapis.com/auth/cloud-platform'
        ];

        $jwt = \Firebase\JWT\JWT::encode($payload, $serviceAccount['private_key'], 'RS256');

        $response = $this->client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['access_token'];
    }
}
