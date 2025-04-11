define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();

            if (shippingAddress.customAttributes) {
                shippingAddress.customAttributes = Object.values(shippingAddress.customAttributes).filter(
                    function (attribute) {
                        return attribute.hasOwnProperty('attribute_code');
                    }
                );
            }

            return originalAction();
        });
    };
});