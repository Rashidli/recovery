<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseNotificationService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendOrderNotification($orderId)
    {
        // Example: Fetch the users who need to be notified
        $users = User::where('id', '!=', auth()->id())->get();
        $tokens = $users->pluck('fcm_token')->filter()->toArray();

        if (empty($tokens)) {
            return response()->json(['message' => 'No users with valid FCM tokens'], 400);
        }

        // Notification title and body
        $title = 'New Order Created';
        $body = 'Order #' . $orderId . ' has been created by ' . auth()->user()->name;

        // Store notification in the database
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body,
            ]);
        }

        // Send notification to Firebase
        $result = $this->firebaseService->sendNotification($tokens, $title, $body);

        return response()->json(['message' => 'Notification sent!', 'fcm_response' => $result]);
    }
}
