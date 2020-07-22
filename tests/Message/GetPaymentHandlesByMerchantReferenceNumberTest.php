<?php

namespace Omnipay\PaysafePaymentHub\Message;

use Omnipay\PaysafePaymentHub\Message\Request\GetPaymentHandlesByMerchantReferenceNumberRequest;
use Omnipay\PaysafePaymentHub\Message\Response\GetPaymentHandlesByMerchantReferenceNumberResponse;
use Omnipay\PaysafePaymentHub\PaymentHandle;
use Omnipay\Tests\TestCase;

class GetPaymentHandlesByMerchantReferenceNumberTest extends TestCase
{
    /**
     * @var GetPaymentHandlesByMerchantReferenceNumberRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new GetPaymentHandlesByMerchantReferenceNumberRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->setMerchantReferenceNumber('merchantRefNum');
    }

    public function testGetDataIsEmpty()
    {
        $this->assertEquals([], $this->request->getData());
    }

    public function testGetMerchantReferenceNumber()
    {
        $this->assertSame('merchantRefNum', $this->request->getMerchantReferenceNumber());
    }

    public function testSendDataWithSuccess()
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('GetPaymentHandlesByMerchantReferenceNumber.txt');

        $expectedPaymentHandle = [
            'id'                 => '037490af-89ee-4794-9022-f7b91a6ddaac',
            'paymentType'        => 'NETELLER',
            'paymentHandleToken' => 'paymentHandleId',
            'merchantRefNum'     => 'b8a8ae3d-1771-4113-aa54-be0721977c0d',
            'currencyCode'       => 'EUR',
            'txnTime'            => '2020-03-19T06:38:07.000+0000',
            'customerIp'         => '172.0.0.1',
            'status'             => 'INITIATED',
            'links'              => [
                [
                    'rel'  => 'redirect_payment',
                    'href' => 'https://api.qa.paysafe.com/alternatepayments/v1/redirect?accountId=1011872745&paymentHandleId=037490af-89ee-4794-9022-f7b91a6ddaac&token=eyJhbGciOiJIUzI1NiJ9.eyJhY2QiOiIxMDExODcyNzQ1IiwicHlkIjoiMDM3NDkwYWYtODllZS00Nzk0LTkwMjItZjdiOTFhNmRkYWFjIiwiZXhwIjoxNTg0NjAxNjg4fQ.e-C3wR7Z3sqBLp0-lS4ksZNsKvs4jV-J_kLphB4N5aM',
                ],
            ],
            'liveMode'           => false,
            'usage'              => 'SINGLE_USE',
            'action'             => 'REDIRECT',
            'executionMode'      => 'SYNCHRONOUS',
            'amount'             => 100,
            'merchantDescriptor' => [
                'dynamicDescriptor' => 'OnlineStore',
                'phone'             => '12345678',
            ],
            'timeToLiveSeconds' => 899,
            'gatewayResponse'   => [
                'orderId'     => 'ORD_5cc31a72-5ac9-429b-8105-43c8a1c1189e',
                'totalAmount' => '1100',
                'currency'    => 'EUR',
                'lang'        => 'en_US',
                'status'      => 'pending',
                'processor'   => 'NETELLER',
            ],
            'returnLinks' => [
                [
                    'rel'  => 'default',
                    'href' => 'https://url/return',
                ],
                [
                    'rel'  => 'on_completed',
                    'href' => 'https://url/success',
                ],
                [
                    'rel'  => 'on_failed',
                    'href' => 'https://url/failure',
                ],
            ],
            'transactionType' => 'PAYMENT',
            'updatedTime'     => '2020-03-19T06:38:08Z',
            'statusTime'      => '2020-03-19T06:38:08Z',
            'neteller'        => [
                'consumerId'       => 'consumer@example.com',
                'consumerIdLocked' => true,
            ],
        ];

        /** @var GetPaymentHandlesByMerchantReferenceNumberResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(GetPaymentHandlesByMerchantReferenceNumberResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame([$expectedPaymentHandle], $response->getPaymentHandles());

        $paymentHandle = $response->getFirstPaymentHandle();
        $this->assertEquals(new PaymentHandle($expectedPaymentHandle), $paymentHandle);

        $this->assertSame('paymentHandleId', $paymentHandle->getPaymentHandleToken());
        $this->assertSame(false, $paymentHandle->isCompleted());
        $this->assertSame('INITIATED', $paymentHandle->getStatus());
        $this->assertSame(1.00, $paymentHandle->getAmount());
        $this->assertSame('EUR', $paymentHandle->getCurrencyCode());
        $this->assertSame($expectedPaymentHandle, $paymentHandle->getData());
    }
}
