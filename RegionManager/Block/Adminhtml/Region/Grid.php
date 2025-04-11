<?php
/**
 * Region Grid Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\Region;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;

class Grid extends Extended
{
    /**
     * @var CollectionFactory
     */
    protected $regionCollectionFactory;

    /**
     * @var Country
     */
    protected $countrySource;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $regionCollectionFactory
     * @param Country $countrySource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $regionCollectionFactory,
        Country $countrySource,
        array $data = []
    ) {
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->countrySource = $countrySource;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('regionGrid');
        $this->setDefaultSort('region_id');
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
        $collection = $this->regionCollectionFactory->create();
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
            'region_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'region_id',
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
            'code',
            [
                'header' => __('Region Code'),
                'index' => 'code'
            ]
        );

        $this->addColumn(
            'default_name',
            [
                'header' => __('Region Name'),
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
                        'field' => 'region_id'
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
                if (!is_array($country) || !isset($country['value']) || !isset($country['label'])) {
                    continue;
                }
                
                $label = $country['label'];
                if (is_object($label) && method_exists($label, '__toString')) {
                    $label = (string)$label;
                } elseif (is_object($label)) {
                    continue;
                }
                
                $options[$country['value']] = $label;
            }
        } catch (\Exception $e) {
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
        return $this->getUrl('*/*/edit', ['region_id' => $row->getId()]);
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