<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

use function array_key_exists;
use function current;
use function is_array;
use Omnipay\PaysafePaymentHub\PaymentHandle;

class GetPaymentHandlesByMerchantReferenceNumberResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() &&
            array_key_exists('paymentHandles', $this->data) &&
            is_array($this->data['paymentHandles']);
    }

    /**
     * @return array
     */
    public function getPaymentHandles()
    {
        return $this->data['paymentHandles'];
    }

    /**
     * @return PaymentHandle
     */
    public function getFirstPaymentHandle()
    {
        return new PaymentHandle(current($this->data['paymentHandles']));
    }
}
