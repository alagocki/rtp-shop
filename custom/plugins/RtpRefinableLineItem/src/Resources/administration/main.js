import './module/sw-order';

Shopware.Module.register('sw-order', {
    override: {
        component: {
            'sw-order-detail-base': {
                template: `
                    <div>
                        <sw-order-detail-base></sw-order-detail-base>
                        <div v-if="order">
                            <sw-field label="Veredelbar" v-model="order.customFields.customizable" type="switch"/>
                        </div>
                    </div>
                `
            }
        }
    }
});