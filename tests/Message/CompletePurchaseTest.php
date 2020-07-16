<?php

namespace Omnipay\PaysafePaymentHub\Message;

use Omnipay\PaysafePaymentHub\Message\Request\CompletePurchaseRequest;
use Omnipay\PaysafePaymentHub\Message\Response\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $this->initializeData();

        $this->assertSame(
            [
                'merchantRefNum'     => 'ref',
                'amount'             => 1100,
                'currencyCode'       => 'EUR',
                'paymentHandleToken' => 'token',
                'dupCheck'           => false,
                'settleWithAuth'     => true,
                'customerIp'         => '172.0.0.1',
                'description'        => 'description',
            ],
            $this->request->getData()
        );
    }

    public function testSendDataWithSuccess()
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        /** @var CompletePurchaseResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
    }

    private function initializeData()
    {
        $this->request->initialize([
            'amount'             => 11.00,
            'currency'           => 'EUR',
            'paymentHandleToken' => 'token',
            'merchantRefNum'     => 'ref',
            'dupCheck'           => false,
            'settleWithAuth'     => true,
            'customerIp'         => '172.0.0.1',
            'description'        => 'description',
        ]);
    }
}
