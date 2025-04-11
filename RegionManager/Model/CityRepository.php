<?php
/**
 * City Repository
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use MagoArab\RegionManager\Api\CityRepositoryInterface;
use MagoArab\RegionManager\Api\Data\CityInterface;
use MagoArab\RegionManager\Model\CityFactory;
use MagoArab\RegionManager\Model\ResourceModel\City as ResourceCity;
use MagoArab\RegionManager\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

class CityRepository implements CityRepositoryInterface
{
    /**
     * @var ResourceCity
     */
    protected $resource;

    /**
     * @var CityFactory
     */
    protected $cityFactory;

    /**
     * @var CityCollectionFactory
     */
    protected $cityCollectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @param ResourceCity $resource
     * @param CityFactory $cityFactory
     * @param CityCollectionFactory $cityCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceCity $resource,
        CityFactory $cityFactory,
        CityCollectionFactory $cityCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->cityFactory = $cityFactory;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save city
     *
     * @param CityInterface $city
     * @return CityInterface
     * @throws CouldNotSaveException
     */
    public function save(CityInterface $city)
    {
        try {
            $this->resource->save($city);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the city: %1',
                $exception->getMessage()
            ));
        }
        return $city;
    }

    /**
     * Retrieve city by ID
     *
     * @param int $cityId
     * @return CityInterface
     * @throws NoSuchEntityException
     */
    public function getById($cityId)
    {
        $city = $this->cityFactory->create();
        $this->resource->load($city, $cityId);
        if (!$city->getId()) {
            throw new NoSuchEntityException(__('City with id "%1" does not exist.', $cityId));
        }
        return $city;
    }

    /**
     * Retrieve cities by region ID
     *
     * @param int $regionId
     * @return SearchResultsInterface
     */
    public function getByRegionId($regionId)
    {
        $collection = $this->cityCollectionFactory->create();
        $collection->addRegionFilter($regionId);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        
        return $searchResults;
    }

    /**
     * Retrieve cities matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->cityCollectionFactory->create();
        
        $this->collectionProcessor->process($searchCriteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        
        return $searchResults;
    }

    /**
     * Delete city
     *
     * @param CityInterface $city
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CityInterface $city)
    {
        try {
            $this->resource->delete($city);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the city: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete city by ID
     *
     * @param int $cityId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($cityId)
    {
        return $this->delete($this->getById($cityId));
    }
}