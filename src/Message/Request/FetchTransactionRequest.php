<?php

namespace Omnipay\PaysafePaymentHub\Message\Request;

use Omnipay\PaysafePaymentHub\Message\Response\FetchTransactionResponse;

class FetchTransactionRequest extends Request
{
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
     * @return FetchTransactionResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::GET,
            sprintf('/payments/%s', $this->getTransactionId())
        );

        $this->response = new FetchTransactionResponse($this, json_decode($response->getBody(), true));

        return $this->response;
    }
}
