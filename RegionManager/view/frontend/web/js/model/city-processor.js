define([
    'jquery',
    'mage/storage',
    'Magento_Customer/js/customer-data'
], function ($, storage, customerData) {
    'use strict';

    var cacheKey = 'region-cities-cache',
        cache = customerData.get(cacheKey);

    return {
        /**
         * Get cities by region
         *
         * @param {String} regionId
         * @returns {*}
         */
        getCitiesByRegion: function (regionId) {
            var serviceUrl = 'mago_regionmanager/city/listAction',
                payload = {regionId: regionId},
                cachedData = cache();

            if (cachedData && cachedData[regionId]) {
                var deferred = $.Deferred();
                deferred.resolve(cachedData[regionId]);
                return deferred.promise();
            }

            return storage.post(
                serviceUrl,
                JSON.stringify(payload),
                false
            ).done(
                function (result) {
                    var cached = cache(),
                        cachedData = cached || {};

                    cachedData[regionId] = result;
                    cache(cachedData);
                }
            );
        }
    };
});