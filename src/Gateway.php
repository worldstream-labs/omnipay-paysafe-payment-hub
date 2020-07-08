<?php

namespace Omnipay\PaysafePaymentHub;

use Omnipay\Common\AbstractGateway;
use Omnipay\PaysafePaymentHub\Message\Request\CompletePurchaseRequest;
use Omnipay\PaysafePaymentHub\Message\Request\GetPaymentHandleRequest;
use Omnipay\PaysafePaymentHub\Message\Request\GetPaymentHandlesByMerchantReferenceNumberRequest;
use Omnipay\PaysafePaymentHub\Message\Request\PurchaseRequest;

/**
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      deleteCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface      fetchTransaction(array $options = [])
 */
class Gateway extends AbstractGateway
{
    const NAME = 'Paysafe Payment Hub';

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'testMode' => true,
            'apiKey'   => '',
        ];
    }

    /**
     * @return PurchaseRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $options = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    /**
     * @return GetPaymentHandleRequest
     */
    public function getPaymentHandle(array $options = [])
    {
        return $this->createRequest(GetPaymentHandleRequest::class, $options);
    }

    /**
     * @return GetPaymentHandlesByMerchantReferenceNumberRequest
     */
    public function getPaymentHandlesByMerchantReferenceNumber(array $options = [])
    {
        return $this->createRequest(GetPaymentHandlesByMerchantReferenceNumberRequest::class, $options);
    }

    /**
     * @return string
     * @return self
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param string $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        return $this->setParameter('apiKey', $apiKey);
    }
}
