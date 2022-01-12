<?php
/**
 * PayPal Setting & API Credentials
 *
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', 'AdqXt7TMGo_l2T3_mHUtjW9_9bX4Fj5jNAA3LLJ3pVHAzSzD7pBCehLxvZ0fSJRELQWQDpNO7MrBRfGN'),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', 'ECCzKzLQilCPkaJNI5Wwo7SZVARhWQZHeIZZUBm9x43J2xzIHzlZe6i2zyaHFTkDo9U_lTO1sE1ShwIU'),
    ],
    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', false), // Validate SSL when creating api client.
];
