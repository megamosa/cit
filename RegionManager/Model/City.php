<?php
/**
 * City Model
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Model;

use Magento\Framework\Model\AbstractModel;
use MagoArab\RegionManager\Api\Data\CityInterface;
use MagoArab\RegionManager\Model\ResourceModel\City as ResourceCity;

class City extends AbstractModel implements CityInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceCity::class);
    }

    /**
     * Get City ID
     *
     * @return int|null
     */
    public function getCityId()
    {
        return $this->getData(self::CITY_ID);
    }

    /**
     * Set City ID
     *
     * @param int $cityId
     * @return $this
     */
    public function setCityId($cityId)
    {
        return $this->setData(self::CITY_ID, $cityId);
    }

    /**
     * Get Region ID
     *
     * @return int|null
     */
    public function getRegionId()
    {
        return $this->getData(self::REGION_ID);
    }

    /**
     * Set Region ID
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * Get City Code
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set City Code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get Default Name
     *
     * @return string|null
     */
    public function getDefaultName()
    {
        return $this->getData(self::DEFAULT_NAME);
    }

    /**
     * Set Default Name
     *
     * @param string $defaultName
     * @return $this
     */
    public function setDefaultName($defaultName)
    {
        return $this->setData(self::DEFAULT_NAME, $defaultName);
    }
}