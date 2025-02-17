<?php

namespace RtpRefinableLineItem\Controller;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;

use Shopware\Core\System\SalesChannel\Context\CachedSalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
#[Route(defaults: ['_routeScope' => ['storefront']])]
class CustomizableCartController extends StorefrontController
{

    protected CartService $cartService;
    protected CachedSalesChannelContextFactory $salesChannelContextFactory;

    public function __construct(CartService $cartService, CachedSalesChannelContextFactory $salesChannelContextFactory)
    {
        $this->cartService = $cartService;
        $this->salesChannelContextFactory = $salesChannelContextFactory;
    }

    #[Route(path: '/cart/update-customizable', name: 'frontend.cart.update_customizable', defaults: ['XmlHttpRequest' => true], methods: ['POST'])]
    public function updateCustomizable(Request $request, SalesChannelContext $context): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $lineItemId = $data['lineItemId'] ?? null;
        $customizable = isset($data['customizable']) ? (bool) $data['customizable'] : false;

        if (!$lineItemId) {
            return new JsonResponse(['error' => 'Line Item ID fehlt'], 400);
        }

        // Warenkorb abrufen
        $cart = $this->cartService->getCart($context->getToken(), $context);

        // Überprüfen, ob das LineItem existiert
        $lineItem = $cart->get($lineItemId);
        if (!$lineItem) {
            return new JsonResponse(['error' => 'Line Item nicht gefunden'], 400);
        }

        // Payload-Wert setzen
        $lineItem->setPayloadValue('rtp_customizable', $customizable);

        // Warenkorb mit neuer Position speichern
        $this->cartService->recalculate($cart, $context);

        return new JsonResponse(['success' => true, 'customizable' => $customizable]);
    }
}