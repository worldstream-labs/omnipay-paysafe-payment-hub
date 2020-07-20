<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

class FetchTransactionResponse extends Response
{
    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->getStatus() === self::STATUS_COMPLETED;
    }

    /**
     * @return int
     */
    public function getAmountInCents()
    {
        return $this->data['amount'];
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->data['currencyCode'];
    }

    /**
     * @return array
     */
    public function getGatewayResponse()
    {
        if (!array_key_exists('gatewayResponse', $this->data)) {
            return [];
        }

        return $this->data['gatewayResponse'];
    }
}
