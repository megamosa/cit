<?php
/**
 * City Repository Interface
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use MagoArab\RegionManager\Api\Data\CityInterface;

interface CityRepositoryInterface
{
    /**
     * Save city
     *
     * @param CityInterface $city
     * @return CityInterface
     * @throws LocalizedException
     */
    public function save(CityInterface $city);

    /**
     * Retrieve city by ID
     *
     * @param int $cityId
     * @return CityInterface
     * @throws NoSuchEntityException
     */
    public function getById($cityId);

    /**
     * Retrieve cities by region ID
     *
     * @param int $regionId
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws NoSuchEntityException
     */
    public function getByRegionId($regionId);

    /**
     * Retrieve cities matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete city
     *
     * @param CityInterface $city
     * @return bool
     * @throws LocalizedException
     */
    public function delete(CityInterface $city);

    /**
     * Delete city by ID
     *
     * @param int $cityId
     * @return bool
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($cityId);
}