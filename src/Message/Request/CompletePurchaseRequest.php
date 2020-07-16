<?php

namespace Omnipay\PaysafePaymentHub\Message\Request;

use function is_bool;
use function json_decode;
use Omnipay\PaysafePaymentHub\Message\Response\CompletePurchaseResponse;

class CompletePurchaseRequest extends Request
{
    /**
     * @return string
     */
    public function getPaymentHandleToken()
    {
        return $this->getParameter('paymentHandleToken');
    }

    /**
     * @param string $paymentHandleToken
     *
     * @return self
     */
    public function setPaymentHandleToken($paymentHandleToken)
    {
        return $this->setParameter('paymentHandleToken', $paymentHandleToken);
    }

    /**
     * @return string
     */
    public function getMerchantRefNum()
    {
        return $this->getParameter('merchantRefNum');
    }

    /**
     * @param string $merchantRefNum
     *
     * @return self
     */
    public function setMerchantRefNum($merchantRefNum)
    {
        return $this->setParameter('merchantRefNum', $merchantRefNum);
    }

    /**
     * @return bool
     */
    public function getDupCheck()
    {
        return $this->getParameter('dupCheck');
    }

    /**
     * @param bool $dupCheck
     *
     * @return self
     */
    public function setDupCheck($dupCheck)
    {
        return $this->setParameter('dupCheck', $dupCheck);
    }

    /**
     * @return bool
     */
    public function getSettleWithAuth()
    {
        return $this->getParameter('settleWithAuth');
    }

    /**
     * @param bool $dupCheck
     *
     * @return self
     */
    public function setSettleWithAuth($settleWithAuth)
    {
        return $this->setParameter('settleWithAuth', $settleWithAuth);
    }

    /**
     * @return string
     */
    public function getCustomerIp()
    {
        return $this->getParameter('customerIp');
    }

    /**
     * @param string $customerIp
     *
     * @return self
     */
    public function setCustomerIp($customerIp)
    {
        return $this->setParameter('customerIp', $customerIp);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'merchantRefNum'     => $this->getMerchantRefNum(),
            'amount'             => $this->getAmountInteger(),
            'currencyCode'       => $this->getCurrency(),
            'paymentHandleToken' => $this->getPaymentHandleToken(),
        ];

        if (is_bool($this->getDupCheck())) {
            $data['dupCheck'] = $this->getDupCheck();
        }

        if (is_bool($this->getSettleWithAuth())) {
            $data['settleWithAuth'] = $this->getSettleWithAuth();
        }

        if (!empty($this->getCustomerIp())) {
            $data['customerIp'] = $this->getCustomerIp();
        }

        if (!empty($this->getDescription())) {
            $data['description'] = $this->getDescription();
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/payments', $data);

        $this->response = new CompletePurchaseResponse($this, json_decode($response->getBody(), true));

        return $this->response;
    }
}
