<?php
/**
 * Layout Processor Plugin
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

class LayoutProcessorPlugin
{
    /**
     * Add custom field to shipping address form
     *
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        // Add select2 library to head
        // For a real-world implementation, you would need to load select2 via requirejs-config.js
        // Here we just demonstrate the concept

        // Ensure our city component is properly set up in shipping address
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'])) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
                'component' => 'MagoArab_RegionManager/js/view/form/element/city-select',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'MagoArab_RegionManager/form/element/city-select',
                    'elementTmpl' => 'MagoArab_RegionManager/form/element/city-select',
                    'additionalClasses' => 'search-select-field'
                ],
                'dataScope' => 'shippingAddress.city',
                'label' => __('City'),
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [
                    'required-entry' => true
                ],
                'sortOrder' => 82,
                'id' => 'city'
            ];
        }

        // Handle each available payment method's billing address form
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['payments-list']['children'])) {
            
            $paymentForms = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'];
                
            foreach ($paymentForms as $paymentMethodCode => $paymentMethodForm) {
                // Skip if this is not a payment method with a billing address form
                if (!isset($paymentMethodForm['children']['form-fields'])) {
                    continue;
                }
                
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode]['children']
                ['form-fields']['children']['city'] = [
                    'component' => 'MagoArab_RegionManager/js/view/form/element/city-select',
                    'config' => [
                        'customScope' => 'billingAddress' . $paymentMethodCode,
                        'template' => 'MagoArab_RegionManager/form/element/city-select',
                        'elementTmpl' => 'MagoArab_RegionManager/form/element/city-select',
                        'additionalClasses' => 'search-select-field'
                    ],
                    'dataScope' => 'billingAddress' . $paymentMethodCode . '.city',
                    'label' => __('City'),
                    'provider' => 'checkoutProvider',
                    'visible' => true,
                    'validation' => [
                        'required-entry' => true
                    ],
                    'sortOrder' => 82,
                    'id' => 'city'
                ];
            }
        }
        
        return $jsLayout;
    }
}