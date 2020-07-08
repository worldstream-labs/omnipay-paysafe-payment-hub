<?php

namespace Omnipay\PaysafePaymentHub\Message\Request;

use function json_decode;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PaysafePaymentHub\Message\Response\GetPaymentHandlesByMerchantReferenceNumberResponse;
use function sprintf;

class GetPaymentHandlesByMerchantReferenceNumberRequest extends Request
{
    /**
     * @return string
     */
    public function getMerchantReferenceNumber()
    {
        return $this->getParameter('merchantReferenceNumber');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setMerchantReferenceNumber($value)
    {
        return $this->setParameter('merchantReferenceNumber', $value);
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
            sprintf('/paymenthandles?merchantRefNum=%s', $this->getMerchantReferenceNumber()));

        $this->response = new GetPaymentHandlesByMerchantReferenceNumberResponse(
            $this,
            json_decode($response->getBody(), true)
        );

        return $this->response;
    }
}
