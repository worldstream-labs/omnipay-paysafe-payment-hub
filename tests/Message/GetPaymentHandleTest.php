<?php

namespace Omnipay\PaysafePaymentHub\Message;

use Omnipay\PaysafePaymentHub\Message\Request\GetPaymentHandleRequest;
use Omnipay\PaysafePaymentHub\Message\Response\GetPaymentHandleResponse;
use Omnipay\Tests\TestCase;

class GetPaymentHandleTest extends TestCase
{
    /**
     * @var GetPaymentHandleRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new GetPaymentHandleRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setPaymentHandleId('paymentHandleId');
    }

    public function testGetDataIsEmpty()
    {
        $this->assertEquals([], $this->request->getData());
    }

    public function testGetPaymentHandleId()
    {
        $this->assertSame('paymentHandleId', $this->request->getPaymentHandleId());
    }

    public function testSendDataWithSuccess()
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('GetPaymentHandle.txt');

        /** @var GetPaymentHandleResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(GetPaymentHandleResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('PAYABLE', $response->getStatus());
        $this->assertSame('037490af-89ee-4794-9022-f7b91a6ddaac', $response->getPaymentId());
        $this->assertTrue($response->getPaymentHandle()->isPayable());
        $this->assertFalse($response->isRedirect());
    }
}
