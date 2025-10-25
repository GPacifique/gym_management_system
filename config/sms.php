<?php

return [
    'driver' => env('SMS_DRIVER', 'log'), // 'twilio' or 'log'

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'), // e.g., "+15005550006"
    ],
];
