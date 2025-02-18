import './component/sw-order-custom-items';

Shopware.Module.register('sw-order-detail-extension', {
    override: {
        'sw-order-detail-base': {
            template: `
                <div>
                    <sw-order-detail-base></sw-order-detail-base>
                    <sw-order-custom-items :orderLineItems="orderLineItems"/>
                </div>
            `,
            computed: {
                orderLineItems() {
                    return this.order ? this.order.lineItems : [];
                }
            }
        }
    }
});