define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setBillingAddressAction) {
        return wrapper.wrap(setBillingAddressAction, function (originalAction, messageContainer) {
            var billingAddress = quote.billingAddress();

            if (billingAddress !== null && billingAddress.customAttributes) {
                billingAddress.customAttributes = Object.values(billingAddress.customAttributes).filter(
                    function (attribute) {
                        return attribute.hasOwnProperty('attribute_code');
                    }
                );
            }

            return originalAction(messageContainer);
        });
    };
});