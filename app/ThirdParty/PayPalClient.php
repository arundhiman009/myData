<?php

namespace App\ThirdParty;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function environment()
    {
        $clientId = config('paypal.mode') == 'live' ? config('paypal.live.client_id') : config('paypal.sandbox.client_id');
        $clientSecret = config('paypal.mode') == 'live' ? config('paypal.live.client_secret') : config('paypal.sandbox.client_secret');

        if(config('paypal.mode') == 'live'){
            return new ProductionEnvironment($clientId, $clientSecret);
        }
        return new SandboxEnvironment($clientId, $clientSecret);


    }
}
