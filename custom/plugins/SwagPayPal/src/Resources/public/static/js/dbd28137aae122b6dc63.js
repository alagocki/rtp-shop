(window["webpackJsonpPluginswag-pay-pal"]=window["webpackJsonpPluginswag-pay-pal"]||[]).push([[370],{4287:function(){},7370:function(n,a,o){"use strict";o.r(a),o.d(a,{default:function(){return t}}),o(7945);var t=Shopware.Component.wrapComponentConfig({template:'<sw-alert\n        class="swag-plugin-apple-pay-warning"\n        variant="warning"\n        :closable="true"\n        @close="onCloseAlert"\n>\n    {{ $tc(\'swag-paypal.settingForm.checkout.domainAssociation.title\') }}\n\n    <br />\n    <sw-external-link\n            class="mt-1"\n            :href="domainAssociationLink"\n    >\n        {{ $tc(\'swag-paypal.settingForm.checkout.domainAssociation.link\') }}\n    </sw-external-link>\n</sw-alert>\n',props:{isSandbox:{required:!0}},computed:{domainAssociationLink(){return this.isSandbox?"https://www.sandbox.paypal.com/uccservicing/apm/applepay":"https://www.paypal.com/uccservicing/apm/applepay"}},methods:{onCloseAlert(){this.$emit("hideDomainAssociationEvent")}}})},7945:function(n,a,o){var t=o(4287);t.__esModule&&(t=t.default),"string"==typeof t&&(t=[[n.id,t,""]]),t.locals&&(n.exports=t.locals),o(5346).Z("f7d3290c",t,!0,{})}}]);