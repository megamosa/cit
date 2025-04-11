<?php
/**
 * City Interface
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Api\Data;

interface CityInterface
{
    /**
     * Constants for keys of data array
     */
    const CITY_ID = 'city_id';
    const REGION_ID = 'region_id';
    const CODE = 'code';
    const DEFAULT_NAME = 'default_name';

    /**
     * Get City ID
     *
     * @return int|null
     */
    public function getCityId();

    /**
     * Set City ID
     *
     * @param int $cityId
     * @return $this
     */
    public function setCityId($cityId);

    /**
     * Get Region ID
     *
     * @return int|null
     */
    public function getRegionId();

    /**
     * Set Region ID
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId);

    /**
     * Get City Code
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Set City Code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get Default Name
     *
     * @return string|null
     */
    public function getDefaultName();

    /**
     * Set Default Name
     *
     * @param string $defaultName
     * @return $this
     */
    public function setDefaultName($defaultName);
}