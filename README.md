# Omnipay: Paysafe Payment Hub

[![Build Status](https://travis-ci.org/worldstream-labs/omnipay-paysafe-payment-hub.svg?branch=master)](https://travis-ci.org/worldstream-labs/omnipay-paysafe-payment-hub) [![Latest Stable Version](https://poser.pugx.org/worldstream-labs/omnipay-paysafe-payment-hub/v)](//packagist.org/packages/worldstream-labs/omnipay-paysafe-payment-hub) [![Total Downloads](https://poser.pugx.org/worldstream-labs/omnipay-paysafe-payment-hub/downloads)](//packagist.org/packages/worldstream-labs/omnipay-paysafe-payment-hub) [![License](https://poser.pugx.org/worldstream-labs/omnipay-paysafe-payment-hub/license)](//packagist.org/packages/worldstream-labs/omnipay-paysafe-payment-hub)

Paysafe Payment Hub library for the Omnipay V3 payment library, used for Neteller (and Skrill later).

## Installation
Use composer to add the library as dependency for your project
`composer require worldstream-labs/omnipay-paysafe-payment-hub`

## Development
To set up for development:  
`composer install`

## Usage

### Setup
```php
<?php

require 'vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('PaysafePaymentHub');
$gateway->setApiKey('yourApiKey');

// When deploying to production, don't forget to set test mode to false
$gateway->setTestMode(false);

```

### Creating a payment handle for Neteller
```php
<?php

require 'vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('PaysafePaymentHub');
$gateway->setApiKey('yourApiKey');

$response = $gateway->purchase([
    'amount'         => 11.00,
    'currency'       => 'EUR',
    'successUrl'     => 'https://url/success',
    'failureUrl'     => 'https://url/failure',
    'returnUrl'      => 'https://url/return',
    'consumerId'     => 'consumer@example.com',
    'merchantRefNum' => 'abcdefg',
    'description'    => 'description',
    'text'           => 'text',
    'paymentMethod'  => 'neteller',
])->send();

redirect($response->getRedirectUrl());

```

This call will return the `PurchaseResponse` object. If the call is succesful then you can redirect the customer to the `redirectUrl`. Depending on the result in the next screen the customer will be redirected to the success or failure url.

### Finalize Payment
Before you can finalize the payment, make sure the payment handle is successfully created and payable using the `getPaymentHandle` call.

```php
<?php

$gateway = Omnipay::create('PaysafePaymentHub');
$gateway->setApiKey('yourApiKey');

$response = $gateway->getPaymentHandle([
    'paymentHandleId' = $paymentHandleId,
])->send();

$paymentHandle = $response->getPaymentHandle();
if ($response->getPaymentHandle()->isPayable()) {
    $completePurchaseResponse = $gateway->completePurchase([
        'amount'             => 11.00,
        'currency'           => 'EUR',
        'paymentHandleToken' => $paymentHandle->getPaymentHandleToken(),
        'merchantRefNum'     => 'ref2',
        'dupCheck'           => false,
        'settleWithAuth'     => true,
        'customerIp'         => '172.0.0.1',
        'description'        => 'description',
    ])->send();
}

```

### Fetch transaction

```php
<?php

$gateway = Omnipay::create('PaysafePaymentHub');
$gateway->setApiKey('yourApiKey');

$response = $gateway->fetchTransaction([
    'paymentId' = $paymentId,
])->send();

if ($response->isComplete()) {
    // payment is complete
}

```

## Tests
Run the unit tests with `composer run test`

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/worldstream-labs/omnipay-paysafe-payment-hub/issues),
or better yet, fork the library and submit a pull request.

## References
[Paysafe Payment Hub documentation](https://developer.paysafe.com/en/additional-documentation/neteller-migration-guide/api/#/reference/0/payment-handle/process-payments)
