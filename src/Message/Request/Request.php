<?php

namespace Omnipay\PaysafePaymentHub\Message\Request;

use function json_encode;
use Omnipay\Common\Message\AbstractRequest;
use Psr\Http\Message\ResponseInterface;
use function sprintf;

abstract class Request extends AbstractRequest
{
    const ENDPOINT_LIVE = 'https://api.paysafe.com/paymenthub/';
    const ENDPOINT_TEST = 'https://api.test.paysafe.com/paymenthub/';

    const POST = 'POST';
    const GET  = 'GET';

    const API_VERSION = 'v1';

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param string $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        return $this->setParameter('apiKey', $apiKey);
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->getParameter('paymentId');
    }

    /**
     * @param string $paymentId
     *
     * @return self
     */
    public function setPaymentId($paymentId)
    {
        return $this->setParameter('paymentId', $paymentId);
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    private function getUrl($endpoint)
    {
        $url = $this->getTestMode() ? self::ENDPOINT_TEST : self::ENDPOINT_LIVE;

        return implode('', [$url, self::API_VERSION, $endpoint]);
    }

    /**
     * @param string $method
     * @param string $endpoint
     *
     * @return ResponseInterface
     */
    public function sendRequest($method, $endpoint, array $data = [])
    {
        $headers = [
            'Content-Type'  => 'application/json; charset=utf-8',
            'Authorization' => sprintf('Basic %s', $this->getApiKey()),
        ];

        return $this->httpClient->request(
            $method,
            $this->getUrl($endpoint),
            $headers,
            empty($data) ? null : json_encode($data)
        );
    }
}
