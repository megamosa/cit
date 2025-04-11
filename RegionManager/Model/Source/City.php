<?php
/**
 * City Source Model
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use MagoArab\RegionManager\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;

class City implements OptionSourceInterface
{
    /**
     * @var CityCollectionFactory
     */
    protected $cityCollectionFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param CityCollectionFactory $cityCollectionFactory
     */
    public function __construct(
        CityCollectionFactory $cityCollectionFactory
    ) {
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            $collection = $this->cityCollectionFactory->create();
            foreach ($collection as $city) {
                $this->options[] = [
                    'value' => $city->getId(),
                    'label' => $city->getDefaultName()
                ];
            }
        }
        return $this->options;
    }

    /**
     * Get options by region ID
     *
     * @param int $regionId
     * @return array
     */
    public function getOptionsByRegion($regionId)
    {
        $options = [];
        $collection = $this->cityCollectionFactory->create();
        $collection->addRegionFilter($regionId);
        
        foreach ($collection as $city) {
            $options[] = [
                'value' => $city->getId(),
                'label' => $city->getDefaultName()
            ];
        }
        
        return $options;
    }
}