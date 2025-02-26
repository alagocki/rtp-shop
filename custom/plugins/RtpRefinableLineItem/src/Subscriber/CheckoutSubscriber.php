<?php /** @noinspection ALL */

namespace RtpRefinableLineItem\Subscriber;


use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\RequestStack;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class CheckoutSubscriber implements EventSubscriberInterface
{
    private EntityRepository $orderLineItemRepository;
    private EntityRepository $orderRepository;
    private RequestStack $requestStack;
    private Context $context;

    public function __construct(RequestStack $requestStack, EntityRepository $orderLineItemRepository, EntityRepository $orderRepository)
    {
        $this->orderLineItemRepository = $orderLineItemRepository;
        $this->orderRepository = $orderRepository;
        $this->requestStack = $requestStack;
        $this->context = Context::createDefaultContext();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvents::ORDER_LINE_ITEM_WRITTEN_EVENT => 'onOrderItemWritten',
            OrderEvents::ORDER_WRITTEN_EVENT => 'onOrderWritten',
        ];
    }

    public function onOrderItemWritten(EntityWrittenEvent $event): void
    {

        try {
//            $context = Context::createDefaultContext();
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
                        ], $this->context);
                    }
                }
            }
        } catch (\RuntimeException $e) {
            throw new \RuntimeException($e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }

    }

    public function onOrderWritten(EntityWrittenEvent $event): void
    {

        foreach ($event->getWriteResults() as $writeResult) {

            $orderId = $writeResult->getPrimaryKey();
            if (!$orderId) {
                continue;
            }

            $criteria = new Criteria([$orderId]);
            $criteria->addAssociation('lineItems'); // Bestellpositionen laden

            $order = $this->orderRepository->search($criteria, $event->getContext())->first();

            if (!$order) {
                continue;
            }


            $customizableItems = '';

            foreach ($order->getLineItems() as $lineItem) {

                if ($lineItem->getPayload() && !empty($lineItem->getPayload()['rtp_customizable'])) {

                    if (true === $lineItem->getPayload()['rtp_customizable']) {
                        $customizableItems .= $lineItem->getLabel() . ' - (' . $lineItem->getPayload()['productNumber'] . ') - ' . $lineItem->getQuantity() . 'x || ';
                    }
                }
            }


            if ($order->getCustomFields() && isset($order->getCustomFields()['custom_rtp_customizable_order']) && $order->getCustomFields()['custom_rtp_customizable_order'] === $customizableItems) {
                continue;
            }

            if (!empty($customizableItems)) {

                $criteria = new Criteria([$orderId]);
                $this->orderRepository->search($criteria, $this->context);

                $this->orderRepository->update([
                    [
                        'id' => $orderId,
                        'customFields' => [
                            'custom_rtp_customizable_order' => $customizableItems
                        ]
                    ]
                ], $this->context);

            }
        }



    }
}
