<?php
/**
 * City Resource Model
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class City extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_country_region_city', 'city_id');
    }
}