define([
    'jquery',
    'ko',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'mage/translate',
    'MagoArab_RegionManager/js/model/city-processor'
], function ($, ko, _, registry, Select, $t, cityProcessor) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false,
            imports: {
                update: '${ $.parentName }.region_id:value'
            }
        },

        /**
         * @param {String} value
         */
        update: function (value) {
            var regionField = registry.get(this.parentName + '.' + 'region_id'),
                country = registry.get(this.parentName + '.' + 'country_id'),
                options, initialOptions = [];

            if (!value || !country) {
                return;
            }

            this.regionId = value;

            cityProcessor.getCitiesByRegion(value).done(function (cities) {
                options = $.map(cities, function (city) {
                    return {
                        label: city.default_name,
                        value: city.default_name
                    };
                });

                // Add initial options to the city select
                _.each(initialOptions, function (option) {
                    options.push(option);
                });

                this.setOptions(options);
                this.set('disabled', false);
            }.bind(this));

            // If region field exists
            if (regionField) {
                this.placeholder(regionField.placeholder);
            }

            // Make select searchable
            setTimeout(function () {
                var citySelect = $('#' + this.uid);
                if (citySelect.length && !citySelect.hasClass('select2-hidden-accessible')) {
                    citySelect.select2({
                        placeholder: $t('Please select a city.'),
                        allowClear: true
                    });
                }
            }.bind(this), 500);
        },

        /**
         * @param {HTMLElement} element
         */
        afterRender: function (element) {
            // Make region select searchable
            var parentName = this.parentName,
                regionField = registry.get(parentName + '.region_id');

            if (regionField) {
                setTimeout(function () {
                    var regionSelect = $('#' + regionField.uid);
                    if (regionSelect.length && !regionSelect.hasClass('select2-hidden-accessible')) {
                        regionSelect.select2({
                            placeholder: $t('Please select a state/region.'),
                            allowClear: true
                        });
                    }
                }, 500);
            }
        }
    });
});