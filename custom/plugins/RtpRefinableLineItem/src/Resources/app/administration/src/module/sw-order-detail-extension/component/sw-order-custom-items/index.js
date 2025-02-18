import template from './sw-order-custom-items.html.twig';

Shopware.Component.register('sw-order-custom-items', {
    template,
    props: {
        orderLineItems: {
            type: Array,
            required: true
        }
    },
    computed: {
        filteredItems() {
            return this.orderLineItems.filter(item => item.customFields && item.customFields.custom_rtp_customizable);
        }
    }
});