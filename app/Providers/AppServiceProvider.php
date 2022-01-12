<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
/*
        $gateway = new \Braintree\Gateway([
        'environment' => 'sandbox',
        'merchantId' => 'c4gs9ws9w6nwj9ry',
        'publicKey' => '8g6tgtf79yp5mnpm',
        'privateKey' => '103c2e9ee2f92d1041cb37cd5443e4e4'
    ]);
        config(['braintree' => $gateway]);*/
    }
}
