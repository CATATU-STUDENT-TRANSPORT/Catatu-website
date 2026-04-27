<?php

return [

    'mpesa' => [
        'consumer_key' => env('MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'shortcode' => env('MPESA_SHORTCODE'),
        'passkey' => env('MPESA_PASSKEY'),
        'environment' => env('MPESA_ENV', 'sandbox'),
        'callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL').'/api/payments/callback'),
        'stub' => (bool) env('MPESA_STUB', true),
    ],

    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'anon_key' => env('SUPABASE_ANON_KEY'),
        'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY'),
        'jwt_secret' => env('SUPABASE_JWT_SECRET'),
        'storage_bucket' => env('SUPABASE_STORAGE_BUCKET', 'catatu'),
    ],

    'booking' => [
        'seat_hold_minutes' => (int) env('CATATU_SEAT_HOLD_MINUTES', 10),
        'student_email_domain_suffixes' => array_filter(explode(',', env(
            'CATATU_STUDENT_EMAIL_SUFFIXES',
            '.edu,.ac.ke,.strathmore.edu,.edu.ng,.ac.uk'
        ))),
    ],

];
