<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <type name="Magento\Checkout\Block\Checkout\LayoutProcessor">
        <plugin name="magoarab_regionmanager_checkout_processor" type="MagoArab\RegionManager\Plugin\Checkout\LayoutProcessorPlugin" sortOrder="10"/>
    </type>
    
    <type name="Magento\Customer\Block\Address\Edit">
        <plugin name="magoarab_regionmanager_customer_address_edit" type="MagoArab\RegionManager\Plugin\Customer\AddressEditPlugin" sortOrder="10"/>
    </type>
    
    <type name="Magento\Customer\Controller\Address\FormPost">
        <plugin name="magoarab_regionmanager_customer_address_save" type="MagoArab\RegionManager\Plugin\Customer\AddressSavePlugin" sortOrder="10"/>
    </type>
</config>