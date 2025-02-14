<?php

namespace RtpRefinableLineItem\Subscriber;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CustomizableCartProcessor implements CartProcessorInterface
{
    public function process(CartDataCollection $data, Cart $original, SalesChannelContext|Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {

        foreach ($original->getLineItems() as $lineItem) {
            // Falls das Feld existiert, setzen wir es in den Payload
            $customizable = $lineItem->getPayloadValue('rtp_customizable') ?? false;
            $lineItem->setPayloadValue('rtp_customizable', (bool) $customizable);
        }
    }
}

