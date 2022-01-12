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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'google' => [

        'client_id' => env('GOOGLE_CLIENT_ID'),

        'client_secret' => env('GOOGLE_CLIENT_SECRET'),

        'redirect' => env('GOOGLE_CALLBACK_URL'),

    ],
   'facebook' => [

        'client_id' => env('FACEBOOK_CLIENT_ID'),

        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),

        'redirect' => env('FB_CALLBACK_URL'),

    ],
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google_Key'=>[
        'api_key'=>env('GOOGLE_API_KEY') 
    ],

   'braintree' => [
        'environment' => env('BTREE_ENVIRONMENT'),
        'merchant_id' => env('BTREE_MERCHANT_ID'),
        'public_key' => env('BTREE_PUBLIC_KEY'),
        'private_key' => env('BTREE_PRIVATE_KEY'),
    ],
    
    'pusher' =>[
        'PUSHER_APP_ID'=>env('PUSHER_APP_ID'),
        'PUSHER_APP_KEY'=>env('PUSHER_APP_KEY'),
        'PUSHER_APP_SECRET'=>env('PUSHER_APP_SECRET'),
        'PUSHER_APP_CLUSTER'=>env('PUSHER_APP_CLUSTER'),
    ],
    'stripe' => [
     'secret' => env('STRIPE_SECRET'),
 ],


];
