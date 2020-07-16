<?php

namespace Omnipay\PaysafePaymentHub\Message\Response;

use function array_key_exists;
use function is_array;
use Omnipay\Common\Message\RedirectResponseInterface;
use RuntimeException;

class PurchaseResponse extends Response implements RedirectResponseInterface
{
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    public function getRedirectUrl()
    {
        if (!array_key_exists('links', $this->data) || !is_array($this->data['links'])) {
            throw new RuntimeException('No redirect url available');
        }

        foreach ($this->data['links'] as $link) {
            if ($link['rel'] !== self::REDIRECT_PAYMENT) {
                continue;
            }

            return $link['href'];
        }

        throw new RuntimeException('No redirect url found');
    }
}
