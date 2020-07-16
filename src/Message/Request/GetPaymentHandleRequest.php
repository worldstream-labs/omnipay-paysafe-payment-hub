<?php

namespace Omnipay\PaysafePaymentHub\Message\Request;

use function json_decode;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PaysafePaymentHub\Message\Response\GetPaymentHandleResponse;
use function sprintf;

class GetPaymentHandleRequest extends Request
{
    /**
     * @return string
     */
    public function getPaymentHandleId()
    {
        return $this->getParameter('paymentHandleId');
    }

    /**
     * @param string $paymentHandleId
     *
     * @return self
     */
    public function setPaymentHandleId($paymentHandleId)
    {
        return $this->setParameter('paymentHandleId', $paymentHandleId);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [];
    }

    /**
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::GET,
            sprintf('/paymenthandles/%s', $this->getPaymentHandleId())
        );

        $this->response = new GetPaymentHandleResponse($this, json_decode($response->getBody(), true));

        return $this->response;
    }
}
