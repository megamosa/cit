define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (createBillingAddressAction) {
        return wrapper.wrap(createBillingAddressAction, function (originalAction, addressData) {
            if (addressData.customAttributes) {
                addressData.customAttributes = Object.values(addressData.customAttributes).filter(
                    function (attribute) {
                        return attribute.hasOwnProperty('attribute_code');
                    }
                );
            }

            return originalAction(addressData);
        });
    };
});