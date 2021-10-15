define([
    'ko',
    'underscore',
    'Magento_Checkout/js/model/quote',
    'mage/storage',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/model/resource-url-manager',
    'Rokanthemes_OnePageCheckout/js/model/shipping-save-processor/payload-extender',
    'Magento_Customer/js/model/customer'
], function (
    ko,
    _,
    quote,
    storage,
    errorProcessor,
    selectBillingAddressAction,
    resourceUrlManager,
    payloadExtender,
    customer
) {
    'use strict';

    return {
        /**
         * @return {jQuery.Deferred}
         */
        saveShippingInformation: function () {
            var payload;

            if (!quote.billingAddress()) {
                selectBillingAddressAction(quote.shippingAddress());
            }

            var billingAddress = quote.billingAddress();

            if (!customer.isLoggedIn()) {
                if (billingAddress) {
                    if (!_.isUndefined(billingAddress.street)) {
                        if (billingAddress.street.length == 0) {
                            delete billingAddress.street;
                        }
                    } else {
                        delete billingAddress.street;
                    }
                }
            }

            payload = {
                addressInformation: {
                    'shipping_address': quote.shippingAddress(),
                    'billing_address': quote.billingAddress(),
                    'shipping_method_code': quote.shippingMethod()['method_code'],
                    'shipping_carrier_code': quote.shippingMethod()['carrier_code']
                }
            };

            payloadExtender(payload);

            return storage.post(
                resourceUrlManager.getUrlForSetShippingInformation(quote),
                JSON.stringify(payload)
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            );
        }
    };
});
