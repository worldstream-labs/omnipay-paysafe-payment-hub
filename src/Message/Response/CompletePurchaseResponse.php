<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

class CompletePurchaseResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->data['status'] === self::STATUS_COMPLETED;
    }
}
