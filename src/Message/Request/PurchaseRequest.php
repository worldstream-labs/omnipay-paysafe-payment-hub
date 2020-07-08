<?php

namespace Omnipay\PaysafePaymentHub\Message\Request;

use function in_array;
use function json_decode;
use Omnipay\PaysafePaymentHub\Message\Response\PurchaseResponse;
use UnexpectedValueException;

class PurchaseRequest extends Request
{
    const PAYMENT_TYPE_NETELLER    = 'NETELLER';
    const TRANSACTION_TYPE_PAYMENT = 'PAYMENT';
    const PAYMENT_METHOD_NETELLER  = 'neteller';

    const PAYMENT_METHODS = [self::PAYMENT_METHOD_NETELLER];

    const LINK_REL_DEFAULT      = 'default';
    const LINK_REL_ON_COMPLETED = 'on_completed';
    const LINK_REL_ON_FAILED    = 'on_failed';

    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'merchantRefNum'  => $this->getMerchantRefNum(),
            'transactionType' => self::TRANSACTION_TYPE_PAYMENT,
            'paymentType'     => self::PAYMENT_TYPE_NETELLER,
            'amount'          => $this->getAmountInteger(),
            'currencyCode'    => $this->getCurrency(),
            'returnLinks'     => [
                [
                    'rel'  => self::LINK_REL_DEFAULT,
                    'href' => $this->getReturnUrl(),
                ],
                [
                    'rel'  => self::LINK_REL_ON_COMPLETED,
                    'href' => $this->getSuccessUrl(),
                ],
                [
                    'rel'  => self::LINK_REL_ON_FAILED,
                    'href' => $this->getFailureUrl(),
                ],
            ],
        ];

        if ($this->getPaymentMethod() === self::PAYMENT_METHOD_NETELLER) {
            $data[self::PAYMENT_METHOD_NETELLER] = $this->getNetellerData();
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getNetellerData()
    {
        $data = [
            'consumerId' => $this->getConsumerId(),
        ];

        if ($this->getDescription()) {
            $data['detail1Description'] = $this->getDescription();
        }

        if ($this->getText()) {
            $data['detail1Text'] = $this->getText();
        }

        return $data;
    }

    /**
     * @param string $value
     *
     * @return PurchaseRequest
     *
     * @throws UnexpectedValueException
     */
    public function setPaymentMethod($value)
    {
        if (!in_array($value, self::PAYMENT_METHODS)) {
            throw new UnexpectedValueException('Payment method '.$value.' not supported');
        }

        return parent::setPaymentMethod($value);
    }

    /**
     * @return string
     */
    public function getConsumerId()
    {
        return $this->getParameter('consumerId');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setConsumerId($value)
    {
        return $this->setParameter('consumerId', $value);
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->getParameter('successUrl');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setSuccessUrl($value)
    {
        return $this->setParameter('successUrl', $value);
    }

    /**
     * @return string
     */
    public function getFailureUrl()
    {
        return $this->getParameter('failureUrl');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setFailureUrl($value)
    {
        return $this->setParameter('failureUrl', $value);
    }

    /**
     * @return string
     */
    public function getMerchantRefNum()
    {
        return $this->getParameter('merchantRefNum');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setMerchantRefNum($value)
    {
        return $this->setParameter('merchantRefNum', $value);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->getParameter('text');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setText($value)
    {
        return $this->setParameter('text', $value);
    }

    /**
     * @param array $data
     *
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/paymenthandles', $data);

        $this->response = new PurchaseResponse($this, json_decode($response->getBody(), true));

        return $this->response;
    }
}
