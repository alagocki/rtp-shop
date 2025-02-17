<?php /** @noinspection ALL */

namespace RtpRefinableLineItem\Subscriber;


use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckoutSubscriber implements EventSubscriberInterface
{
    private EntityRepository $orderLineItemRepository;

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack, EntityRepository $orderLineItemRepository)
    {
        $this->orderLineItemRepository = $orderLineItemRepository;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvents::ORDER_LINE_ITEM_WRITTEN_EVENT => 'onOrderItemWritten'
        ];
    }

    public function onOrderItemWritten(EntityWrittenEvent $event): void
    {

        try {
            $context = Context::createDefaultContext();
            $customizable = $this->requestStack->getCurrentRequest()->get('rtp_customizable');
            if (null !== $customizable) {
                foreach ($event->getWriteResults() as $writeResult) {
                    $payload = $writeResult->getPayload();
                    $lineItemId = $payload['id'] ?? null;
                    if ($lineItemId && null !== $customizable && isset($customizable[$payload['productId']])) {

                        // Speichert das Feld in den Custom Fields der Bestellposition
                        $this->orderLineItemRepository->update([
                            [
                                'id' => $lineItemId,
                                'customFields' => [
                                    'custom_rtp_customizable' => (bool)$customizable[$payload['productId']]
                                ]
                            ]
                        ], $context);
                    }
                }
            }
        } catch (\RuntimeException $e) {
            throw new \RuntimeException($e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }

    }
}
