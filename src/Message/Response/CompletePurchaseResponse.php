<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

class CompletePurchaseResponse extends Response
{
    const STATUS_COMPLETED = 'COMPLETED';

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->data['status'] === self::STATUS_COMPLETED;
    }
}
