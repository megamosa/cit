<?php
/**
 * City Collection
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Model\ResourceModel\City;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MagoArab\RegionManager\Model\City;
use MagoArab\RegionManager\Model\ResourceModel\City as ResourceCity;

class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(City::class, ResourceCity::class);
    }

    /**
     * Add region filter to collection
     *
     * @param int $regionId
     * @return $this
     */
    public function addRegionFilter($regionId)
    {
        $this->addFieldToFilter('region_id', $regionId);
        return $this;
    }
}