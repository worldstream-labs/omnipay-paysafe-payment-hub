<?php

namespace Omnipay\PaysafePaymentHub;

use Omnipay\PaysafePaymentHub\Message\Request\CompletePurchaseRequest;
use Omnipay\PaysafePaymentHub\Message\Request\GetPaymentHandleRequest;
use Omnipay\PaysafePaymentHub\Message\Request\GetPaymentHandlesByMerchantReferenceNumberRequest;
use Omnipay\PaysafePaymentHub\Message\Request\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway();
    }

    public function testGetName()
    {
        $this->assertEquals('Paysafe Payment Hub', $this->gateway->getName());
    }

    public function testApiKeyIsSet()
    {
        $this->gateway->setApiKey('abc');

        $this->assertEquals('abc', $this->gateway->getApiKey());
    }

    public function testPurchase()
    {
        $this->assertInstanceOf(PurchaseRequest::class, $this->gateway->purchase());
    }

    public function testCompletePurchase()
    {
        $this->assertInstanceOf(CompletePurchaseRequest::class, $this->gateway->completePurchase());
    }

    public function testGetPaymentHandle()
    {
        $this->assertInstanceOf(GetPaymentHandleRequest::class, $this->gateway->getPaymentHandle());
    }

    public function testGetPaymentHandleByMerchantReferenceNumber()
    {
        $this->assertInstanceOf(
            GetPaymentHandlesByMerchantReferenceNumberRequest::class,
            $this->gateway->getPaymentHandlesByMerchantReferenceNumber()
        );
    }
}
