<?php

namespace Omnipay\PaysafePaymentHub;

use function round;

class PaymentHandle
{
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_PAYABLE   = 'PAYABLE';
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getPaymentHandleToken()
    {
        return $this->data['paymentHandleToken'];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->data['status'];
    }

    /**
     * @return bool
     */
    public function isPayable()
    {
        return $this->data['status'] === self::STATUS_PAYABLE;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->data['status'] === self::STATUS_COMPLETED;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return round($this->data['amount'] / 100, 2);
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->data['currencyCode'];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
