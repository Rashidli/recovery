<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    private $serverKey;

    public function __construct()
    {
        // Your Firebase server key from the Firebase Console
        $this->serverKey = env('FIREBASE_SERVER_KEY');
    }

    public function sendToTopic($topic, $title, $body, $data = [])
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $payload = [
            'to' => "/topics/$topic", // Target the topic
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data, // Custom data for your app
        ];

        // Use Laravel's HTTP client to send the request
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        return $response->json();
    }
}

