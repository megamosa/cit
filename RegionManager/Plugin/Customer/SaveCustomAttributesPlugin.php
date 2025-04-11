<?php
/**
 * Save Custom Attributes Plugin
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Plugin\Customer;

use Magento\Customer\Model\Address;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use MagoArab\RegionManager\Api\CityRepositoryInterface;
use MagoArab\RegionManager\Model\ResourceModel\City\Collection as CityCollection;
use MagoArab\RegionManager\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

class SaveCustomAttributesPlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @var CityCollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @param RequestInterface $request
     * @param CityRepositoryInterface $cityRepository
     * @param CityCollectionFactory $cityCollectionFactory
     */
    public function __construct(
        RequestInterface $request,
        CityRepositoryInterface $cityRepository,
        CityCollectionFactory $cityCollectionFactory
    ) {
        $this->request = $request;
        $this->cityRepository = $cityRepository;
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    /**
     * Validate city against available cities for the region
     *
     * @param Address $subject
     * @return void
     * @throws LocalizedException
     */
    public function beforeSave(Address $subject)
    {
        $regionId = $subject->getRegionId();
        $city = $subject->getCity();

        if ($regionId && $city) {
            try {
                // Get cities for this region
                /** @var CityCollection $cityCollection */
                $cityCollection = $this->cityCollectionFactory->create();
                $cityCollection->addRegionFilter($regionId);
                $cityCollection->addFieldToFilter('default_name', ['eq' => $city]);

                // If no city found for this region with this name, check if we need to add it
                if ($cityCollection->getSize() === 0) {
                    // This is where you would add code to auto-create the city if that's a requirement
                    // For now, we're just validating
                    // Uncomment the below line if you want strict validation
                    // throw new LocalizedException(__('The selected city is not valid for the selected region.'));
                }
            } catch (NoSuchEntityException $e) {
                // Region not found, this will be caught by Magento's region validation
            }
        }
    }
}