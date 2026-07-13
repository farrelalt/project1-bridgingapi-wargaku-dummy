<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'media_center' => [
    'timeout' => (int) env('MEDIA_CENTER_TIMEOUT', 10),

    'endpoints' => [
        'login' => env('MEDIA_CENTER_LOGIN_URL'),
        'register' => env('MEDIA_CENTER_REGISTER_URL'),
        'profile' => env('MEDIA_CENTER_PROFILE_URL'),

        'keluhan_create' => env('MEDIA_CENTER_KELUHAN_CREATE_URL'),
        'keluhan' => env('MEDIA_CENTER_KELUHAN_URL'),
        'keluhan_detail' => env('MEDIA_CENTER_KELUHAN_DETAIL_URL'),
        'keluhan_selesai' => env('MEDIA_CENTER_KELUHAN_SELESAI_URL'),
        'keluhan_hapus' => env('MEDIA_CENTER_KELUHAN_HAPUS_URL'),

        'tanggapan_create' => env('MEDIA_CENTER_TANGGAPAN_CREATE_URL'),
        'tanggapan' => env('MEDIA_CENTER_TANGGAPAN_URL'),

        'kategori' => env('MEDIA_CENTER_KATEGORI_URL'),
        'chanel' => env('MEDIA_CENTER_CHANEL_URL'),
        'kecamatan' => env('MEDIA_CENTER_KECAMATAN_URL'),
        'kelurahan' => env('MEDIA_CENTER_KELURAHAN_URL'),
        'topik' => env('MEDIA_CENTER_TOPIK_URL'),
        'status' => env('MEDIA_CENTER_STATUS_URL'),
        'instansi' => env('MEDIA_CENTER_INSTANSI_URL'),

        'keluhan_rating' => env('MEDIA_CENTER_KELUHAN_RATING_URL'),
        'view_keluhan_rating' => env(
            'MEDIA_CENTER_VIEW_KELUHAN_RATING_URL'
        ),
    ],
],

];
