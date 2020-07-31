<?php

namespace Omnipay\PaysafePaymentHub\Message;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Omnipay\PaysafePaymentHub\Exception\RedirectUrlException;
use Omnipay\PaysafePaymentHub\Message\Request\PurchaseRequest;
use Omnipay\PaysafePaymentHub\Message\Request\Request;
use Omnipay\PaysafePaymentHub\Message\Response\PurchaseResponse;
use Omnipay\Tests\TestCase;
use UnexpectedValueException;

class PurchaseTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testWithInvalidPaymentMethod()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Payment method abc not supported');

        $this->request->setPaymentMethod('abc');
    }

    public function testSetAndGetAllData()
    {
        $this->request->setAmount('11.00');
        $this->request->setCurrency('EUR');
        $this->request->setSuccessUrl('https://url/success');
        $this->request->setFailureUrl('https://url/failure');
        $this->request->setReturnUrl('https://url/return');
        $this->request->setConsumerId('consumer@example.com');
        $this->request->setMerchantRefNum('abcdefg');
        $this->request->setDescription('description');
        $this->request->setText('text');

        $this->assertSame('11.00', $this->request->getAmount());
        $this->assertSame('EUR', $this->request->getCurrency());
        $this->assertSame('https://url/success', $this->request->getSuccessUrl());
        $this->assertSame('https://url/failure', $this->request->getFailureUrl());
        $this->assertSame('https://url/return', $this->request->getReturnUrl());
        $this->assertSame('consumer@example.com', $this->request->getConsumerId());
        $this->assertSame('abcdefg', $this->request->getMerchantRefNum());
        $this->assertSame('description', $this->request->getDescription());
        $this->assertSame('text', $this->request->getText());
    }

    public function testGetData()
    {
        $this->initializeData();

        $data = [
            'merchantRefNum'  => 'abcdefg',
            'transactionType' => 'PAYMENT',
            'paymentType'     => 'NETELLER',
            'amount'          => 1100,
            'currencyCode'    => 'EUR',
            'returnLinks'     => [
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
            'neteller'        => [
                'consumerId'         => 'consumer@example.com',
                'detail1Description' => 'description',
                'detail1Text'        => 'text',
            ],
        ];

        $this->assertSame($data, $this->request->getData());
    }

    private function initializeData()
    {
        $this->request->initialize([
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
        ]);
    }

    public function testSendDataWithSuccess()
    {
        $apiKey = 'abc';
        $this->request->setApiKey($apiKey);
        $this->initializeData();
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $lastRequest = $this->getMockClient()->getLastRequest();

        $expectedRequest = new GuzzleRequest(
            Request::POST,
            'https://api.paysafe.com/paymenthub/v1/paymenthandles',
            [
                'Content-Type'  => 'application/json; charset=utf-8',
                'Authorization' => 'Basic '.$apiKey,
            ],
            json_encode([
                'merchantRefNum'  => 'abcdefg',
                'transactionType' => 'PAYMENT',
                'paymentType'     => 'NETELLER',
                'amount'          => 1100,
                'currencyCode'    => 'EUR',
                'returnLinks'     => [
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
                'neteller' => [
                    'consumerId'         => 'consumer@example.com',
                    'detail1Description' => 'description',
                    'detail1Text'        => 'text',
                ],
            ])
        );

        $this->assertEquals($expectedRequest->getMethod(), $lastRequest->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $lastRequest->getUri());

        $this->assertJsonStringEqualsJsonString(
            (string) $expectedRequest->getBody(),
            (string) $lastRequest->getBody()
        );

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://neteller/redirect', $response->getRedirectUrl());
        $this->assertEquals('INITIATED', $response->getStatus());

        $expectedMessage = [
            'id'                 => '82d57742-e2db-48ea-a726-a60e6f8265a3',
            'paymentType'        => 'NETELLER',
            'paymentHandleToken' => 'PHQhlWpTRKzBXubN',
            'merchantRefNum'     => 'abcdefg',
            'currencyCode'       => 'EUR',
            'dupCheck'           => true,
            'status'             => 'INITIATED',
            'liveMode'           => true,
            'usage'              => 'SINGLE_USE',
            'action'             => 'REDIRECT',
            'executionMode'      => 'SYNCHRONOUS',
            'amount'             => 1100,
            'customerIp'         => '172.0.0.1',
            'timeToLiveSeconds'  => 899,
            'gatewayResponse'    => [
                'orderId'     => 'ORD_0d676b4b-0eb8-4d78-af25-e41ab431e325',
                'totalAmount' => 1100,
                'currency'    => 'EUR',
                'status'      => 'pending',
                'lang'        => 'en_US',
                'processor'   => 'NETELLER',
            ],
            'neteller' => [
                'consumerId'         => 'consumer@example.com',
                'detail1Description' => 'description',
                'detail1Text'        => 'text',
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
            'txnTime'     => '2020-07-04T10:39:50Z',
            'updatedTime' => '2020-07-04T10:39:50Z',
            'statusTime'  => '2020-07-04T10:39:50Z',
            'links'       => [
                [
                    'rel'  => 'test',
                    'href' => 'https://neteller/test',
                ],
                [
                    'rel'  => 'redirect_payment',
                    'href' => 'https://neteller/redirect',
                ],
            ],
        ];

        $this->assertSame($expectedMessage, json_decode($response->getMessage(), true));
    }

    public function testPurchaseResponseWithError()
    {
        $apiKey = 'abc';
        $this->request->setApiKey($apiKey);
        $this->initializeData();
        $this->setMockHttpResponse('PurchaseError.txt');

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isSuccessful());
    }

    public function testPurchaseResponseIsMissingLinks()
    {
        $this->expectException(RedirectUrlException::class);
        $this->expectExceptionMessage('No redirect url available');

        $apiKey = 'abc';
        $this->request->setApiKey($apiKey);
        $this->initializeData();
        $this->setMockHttpResponse('PurchaseMissingLinks.txt');

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $response->getRedirectUrl();
    }

    public function testPurchaseResponseHasNoRedirectLink()
    {
        $this->expectException(RedirectUrlException::class);
        $this->expectExceptionMessage('No redirect url found');

        $apiKey = 'abc';
        $this->request->setApiKey($apiKey);
        $this->initializeData();
        $this->setMockHttpResponse('PurchaseMissingRedirectLink.txt');

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $response->getRedirectUrl();
    }
}
