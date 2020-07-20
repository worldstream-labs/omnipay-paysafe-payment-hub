<?php

namespace Omnipay\Paysafecard\Test\Message;

use Omnipay\PaysafePaymentHub\Message\Request\FetchTransactionRequest;
use Omnipay\PaysafePaymentHub\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionTest extends TestCase
{
    const PAYMENT_ID = '67869d92-4df1-455b-80f4-780abbce41b4';

    /**
     * @var FetchTransactionRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setPaymentId(self::PAYMENT_ID);
    }

    public function testGetDataReturnsAnEmptyArray()
    {
        self::assertEquals([], $this->request->getData());
    }

    public function testGetPaymentId()
    {
        self::assertEquals(self::PAYMENT_ID, $this->request->getPaymentId());
    }

    public function testSendDataWithSuccess()
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        /** @var FetchTransactionResponse $response */
        $response = $this->request->send();

        $expectedResponse = [
            'id'                 => '67869d92-4df1-455b-80f4-780abbce41b4',
            'paymentType'        => 'NETELLER',
            'paymentHandleToken' => 'PHi2MeARP5OxuXIm',
            'merchantRefNum'     => '4a33324e-ae23-4c48-955b-33ee189933f3',
            'currencyCode'       => 'EUR',
            'settleWithAuth'     => true,
            'txnTime'            => '2020-03-19T06:38:07.000+0000',
            'billingDetails'     => [
                'street1' => '100 Queen',
                'street2' => 'Unit 201',
                'city'    => 'Toronto',
                'zip'     => 'M5H 2N2',
                'country' => 'CA',
            ],
            'status'                  => 'COMPLETED',
            'gatewayReconciliationId' => 'b8a8ae3d-1771-4113-aa54-be0721977c0d',
            'amount'                  => 1100,
            'consumerIp'              => '172.0.0.1',
            'liveMode'                => false,
            'updatedTime'             => '2020-03-19T06:44:22Z',
            'statusTime'              => '2020-03-19T06:44:22Z',
            'gatewayResponse'         => [
                'orderId'           => 'ORD_5cc31a72-5ac9-429b-8105-43c8a1c1189e',
                'totalAmount'       => '1100',
                'currency'          => 'EUR',
                'lang'              => 'en_US',
                'customerId'        => 'CUS_718980F1-7B2F-41CA-A758-644043362B11',
                'verificationLevel' => '10',
                'transactionId'     => '127934948749341',
                'transactionType'   => 'Member to Merchant Transfer (Order)',
                'description'       => 'neteller.account@example.org to Neteller Simulator Test',
                'status'            => 'paid',
                'processor'         => 'NETELLER',
            ],
            'availableToSettle' => 0,
            'neteller'          => [
                'consumerId'       => 'consumer@example.com',
                'consumerIdLocked' => true,
            ],
        ];

        self::assertInstanceOf(FetchTransactionResponse::class, $response);
        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isCompleted());
        self::assertSame(1100, $response->getAmountInCents());
        self::assertSame('EUR', $response->getCurrency());
        self::assertSame($expectedResponse, $response->getData());
        self::assertSame($expectedResponse['gatewayResponse'], $response->getGatewayResponse());
    }

    public function testSendDataWithAuthorizationFailure()
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('FetchTransactionFailure.txt');

        /** @var FetchTransactionResponse $response */
        $response = $this->request->send();

        self::assertInstanceOf(FetchTransactionResponse::class, $response);
        self::assertFalse($response->isSuccessful());
        self::assertSame([], $response->getGatewayResponse());
    }
}
