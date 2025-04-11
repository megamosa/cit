<?php
/**
 * Address Edit Plugin
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Plugin\Customer;

use Magento\Customer\Block\Address\Edit;
use Magento\Framework\View\Element\AbstractBlock;

class AddressEditPlugin
{
    /**
     * Add city select script to address edit form
     *
     * @param Edit $subject
     * @param string $result
     * @return string
     */
    public function afterGetChildHtml(Edit $subject, $result)
    {
        $regionId = $subject->getRegionId();
        $city = $subject->getAddress()->getCity();
        
        $script = '<script type="text/javascript">
            require([
                "jquery",
                "domReady!",
                "MagoArab_RegionManager/js/checkout/city-loader"
            ], function($, domReady, cityLoader) {
                $(document).ready(function() {
                    var regionSelect = $("#region_id");
                    var citySelect = $("#city");
                    
                    // Initialize select2 for both selects
                    regionSelect.select2({
                        placeholder: $.mage.__("Please select a state/region."),
                        allowClear: true
                    });
                    
                    citySelect.select2({
                        placeholder: $.mage.__("Please select a city."),
                        allowClear: true
                    });
                    
                    // Handle region change
                    regionSelect.on("change", function() {
                        var regionId = $(this).val();
                        if (regionId) {
                            cityLoader.loadCities(regionId, citySelect);
                        } else {
                            citySelect.prop("disabled", true);
                            citySelect.empty();
                            citySelect.append($("<option></option>").attr("value", "").text($.mage.__("Please select a city.")));
                            citySelect.trigger("change");
                        }
                    });
                    
                    // Set initial value
                    var initialRegionId = "' . $regionId . '";
                    var initialCity = "' . $city . '";
                    
                    if (initialRegionId) {
                        regionSelect.val(initialRegionId).trigger("change");
                        
                        // Set city after cities are loaded
                        if (initialCity) {
                            setTimeout(function() {
                                citySelect.val(initialCity).trigger("change");
                            }, 1000);
                        }
                    }
                });
            });
        </script>';
        
        return $result . $script;
    }
}