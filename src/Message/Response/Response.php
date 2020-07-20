<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

use function array_key_exists;
use function json_encode;
use Omnipay\Common\Message\AbstractResponse;

abstract class Response extends AbstractResponse
{
    const REDIRECT_PAYMENT = 'redirect_payment';
    const STATUS_COMPLETED = 'COMPLETED';

    public function isSuccessful()
    {
        return !array_key_exists('error', $this->data);
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->data['status'];
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return json_encode($this->data);
    }
}
