<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

use function array_key_exists;
use function is_array;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\PaysafePaymentHub\Exception\RedirectUrlException;
use RuntimeException;

class PurchaseResponse extends Response implements RedirectResponseInterface
{
    public function isRedirect()
    {
        try {
            $this->getRedirectUrl();

            return true;
        } catch (RedirectUrlException $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->isRedirect()) {
            return false;
        }

        return parent::isSuccessful();
    }

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    public function getRedirectUrl()
    {
        if (!array_key_exists('links', $this->data) || !is_array($this->data['links'])) {
            throw new RedirectUrlException('No redirect url available');
        }

        foreach ($this->data['links'] as $link) {
            if ($link['rel'] !== self::REDIRECT_PAYMENT) {
                continue;
            }

            return $link['href'];
        }

        throw new RedirectUrlException('No redirect url found');
    }
}
