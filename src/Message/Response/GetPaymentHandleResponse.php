<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\PaysafePaymentHub\PaymentHandle;

class GetPaymentHandleResponse extends Response implements RedirectResponseInterface
{
    /**
     * @return bool
     */
    public function isRedirect()
    {
        return isset($this->data['links']['rel']) && $this->data['links']['rel'] === self::REDIRECT_PAYMENT;
    }

    /**
     * @return PaymentHandle
     */
    public function getPaymentHandle()
    {
        return new PaymentHandle($this->data);
    }
}
