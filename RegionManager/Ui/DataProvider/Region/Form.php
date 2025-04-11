<?php
/**
 * Region Form DataProvider
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Ui\DataProvider\Region;

use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Form extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        
        $items = $this->collection->getItems();
        foreach ($items as $region) {
            $this->loadedData[$region->getId()] = $region->getData();
        }

        $data = $this->dataPersistor->get('region');
        if (!empty($data)) {
            $region = $this->collection->getNewEmptyItem();
            $region->setData($data);
            $this->loadedData[$region->getId()] = $region->getData();
            $this->dataPersistor->clear('region');
        }
        
        return $this->loadedData;
    }
}