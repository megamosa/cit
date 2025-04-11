var config = {
    map: {
        '*': {
            'Magento_Checkout/js/model/shipping-rate-processor/new-address': 'MagoArab_RegionManager/js/model/shipping-rate-processor/new-address',
            'Magento_Checkout/js/model/shipping-rate-processor/customer-address': 'MagoArab_RegionManager/js/model/shipping-rate-processor/customer-address',
            'Magento_Checkout/js/action/set-shipping-information': 'MagoArab_RegionManager/js/action/set-shipping-information'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/action/create-shipping-address': {
                'MagoArab_RegionManager/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/set-billing-address': {
                'MagoArab_RegionManager/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'MagoArab_RegionManager/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'MagoArab_RegionManager/js/action/create-billing-address-mixin': true
            }
        }
    }
};