<?php

namespace RtpRefinableLineItem\Subscriber;

use Shopware\Core\Checkout\Cart\Event\CartBeforeSerializationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CartBeforeSerializationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CartBeforeSerializationEvent::class => 'onCartBeforeSerialization',
        ];
    }

    public function onCartBeforeSerialization(CartBeforeSerializationEvent $event): void
    {
        $allowed = $event->getCustomFieldAllowList();
        $allowed[] = 'custom_rtp_customizable_order';

        $event->setCustomFieldAllowList($allowed);
    }
}