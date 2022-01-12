<?php

namespace App\ThirdParty;

use Auth;
use Config;
use Braintree;

class CustomBraintree
{
    public function config()
    {
        $gateway = new Braintree\Configuration([
            'environment' => Config::get('services.braintree.environment'),
            'merchantId' => Config::get('services.braintree.merchant_id'),
            'publicKey' => Config::get('services.braintree.public_key'),
            'privateKey' => Config::get('services.braintree.private_key')
        ]);
        return $gateway;
    }
    public function createCustomer()
    {
        // https://developers.braintreepayments.com/reference/request/customer/create/php#company
        // https://developers.braintreepayments.com/reference/request/customer/create/php#customer-with-a-payment-method-and-billing-address
        $config = $this->config();
        $gateway = new Braintree\Gateway($config);
        $result = $gateway->customer()->create([
            'id' => Auth::user()->id,
            'firstName' => Auth::user()->firstname,
            'lastName' => Auth::user()->lastname,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone,
        ]);
        return $result;
    }

    public function createCard($cardInfo=NULL)
    {
        $config = $this->config();
        $gateway = new Braintree\Gateway($config);
        $data = explode('/',$cardInfo['expiry_date']);
        $expiry_month = $data[0];
        $expiry_year = $data[1];
        $result = $gateway->paymentMethod()->create([
            'customerId' => $cardInfo['braintree_customer_id'],
            'number' => $cardInfo['card_number'],
            'expirationMonth' => $expiry_month,
            'expirationYear' => $expiry_year,
            'cvv' => $cardInfo['cvv_number'],
            'paymentMethodNonce' => 'fake-valid-nonce',
            'options' => [
                'verifyCard' => true,
                'makeDefault' => true,
                //'failOnDuplicatePaymentMethod' => true
            ],
        ]);
        return $result;
    }

    public function nonce()
    {
        $config = $this->config();
        $gateway = new Braintree\Gateway($config);
        $token = $gateway->clientToken()->generate();
        $result = $gateway->paymentMethodNonce()->create($token);
        return $result->paymentMethodNonce->nonce;
    }

    public function removeCard($cardDetail=Null)
    {
        $config = $this->config();
        $gateway = new Braintree\Gateway($config);
        $result = $gateway->paymentMethod()->delete($cardDetail->token_id);
        return $result;
    }

    public function defaultCard($cardDetail=Null)
    {
        $config = $this->config();
        $gateway = new Braintree\Gateway($config);
        $updateResult = $gateway->paymentMethod()->update(
        $cardDetail->token_id,
            [
                'options' => [
                    'makeDefault' => true
                ]
            ]
        );
        return $updateResult;
    }
    public function propertyPayment($data = Null)
    {
        $config = $this->config();
        $gateway = new Braintree\Gateway($config);
        $result = $gateway->transaction()->sale([
          'amount' =>$data['amount'] ,
          'paymentMethodNonce' => 'fake-valid-nonce',
          'customerId' => $data['customerId'],
           'options' => [
               'storeInVaultOnSuccess' => true,
            ]
        ]);
        return $result;
    }

}
