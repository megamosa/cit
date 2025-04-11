<?php
/**
 * City Grid Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\City;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Directory\Model\Config\Source\Allregion;
use MagoArab\RegionManager\Model\ResourceModel\City\CollectionFactory;

class Grid extends Extended
{
    /**
     * @var CollectionFactory
     */
    protected $cityCollectionFactory;

    /**
     * @var Country
     */
    protected $countrySource;

    /**
     * @var Allregion
     */
    protected $regionSource;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $cityCollectionFactory
     * @param Country $countrySource
     * @param Allregion $regionSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $cityCollectionFactory,
        Country $countrySource,
        Allregion $regionSource,
        array $data = []
    ) {
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->countrySource = $countrySource;
        $this->regionSource = $regionSource;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('cityGrid');
        $this->setDefaultSort('city_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->cityCollectionFactory->create();
        /* @var $collection \MagoArab\RegionManager\Model\ResourceModel\City\Collection */
        $collection->getSelect()->joinLeft(
            ['region' => $collection->getTable('directory_country_region')],
            'main_table.region_id = region.region_id',
            ['region_name' => 'region.default_name', 'country_id' => 'region.country_id']
        );
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'city_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'city_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'country_id',
            [
                'header' => __('Country'),
                'type' => 'options',
                'index' => 'country_id',
                'options' => $this->getCountryOptions()
            ]
        );

        $this->addColumn(
            'region_id',
            [
                'header' => __('State/Region'),
                'type' => 'options',
                'index' => 'region_id',
                'options' => $this->getRegionOptions()
            ]
        );

        $this->addColumn(
            'code',
            [
                'header' => __('City Code'),
                'index' => 'code'
            ]
        );

        $this->addColumn(
            'default_name',
            [
                'header' => __('City Name'),
                'index' => 'default_name'
            ]
        );

        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'city_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Get country options
     *
     * @return array
     */
    protected function getCountryOptions()
    {
        $options = [];
        
        try {
            $countries = $this->countrySource->toOptionArray();
            
            foreach ($countries as $country) {
                // Verificar que sea un array con las claves esperadas
                if (!is_array($country) || !isset($country['value']) || !isset($country['label'])) {
                    continue;
                }
                
                // Manejar el caso donde label podría ser un objeto
                $label = $country['label'];
                if (is_object($label) && method_exists($label, '__toString')) {
                    $label = (string)$label;
                } elseif (is_object($label)) {
                    continue; // Omitir si no podemos convertirlo a string
                }
                
                $options[$country['value']] = $label;
            }
        } catch (\Exception $e) {
            // En caso de error, devolver un array vacío
            return [];
        }
        
        return $options;
    }

    /**
     * Get region options
     *
     * @return array
     */
    protected function getRegionOptions()
    {
        $options = [];
        
        try {
            // Utilizamos el objeto regionSource que es una instancia de Allregion
            $regions = $this->regionSource->toOptionArray();
            
            foreach ($regions as $region) {
                // Nos aseguramos de que sea un array con los valores esperados
                if (!is_array($region)) {
                    continue;
                }
                
                // Si es un elemento de grupo (label, value donde value es un array)
                if (isset($region['label']) && isset($region['value']) && is_array($region['value'])) {
                    foreach ($region['value'] as $subRegion) {
                        if (isset($subRegion['value']) && isset($subRegion['label']) && !empty($subRegion['value'])) {
                            $options[$subRegion['value']] = $subRegion['label'];
                        }
                    }
                }
                // Si es un elemento directo (value, label)
                elseif (isset($region['value']) && isset($region['label']) && !empty($region['value'])) {
                    $options[$region['value']] = $region['label'];
                }
            }
        } catch (\Exception $e) {
            // En caso de error, devolver un array vacío
            return [];
        }
        
        return $options;
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['city_id' => $row->getId()]);
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
}