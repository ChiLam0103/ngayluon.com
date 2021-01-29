<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAA6e7CrQY:APA91bGV_eoLufIYyEDg1dtDBsVn4-RHx4k5F63sd7ZN2yKSnOtaG_Fjlj48GfGC5GLVPH29eNJuekmKALy3UV-6iXJThR3BvlDW5Evi_pXWo9RRkHw0RnNH2A31JhqOxnKgzW9sew2r'),
        'sender_id' => env('FCM_SENDER_ID', '1004733115654'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
