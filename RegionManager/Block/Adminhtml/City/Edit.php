<?php
/**
 * City Edit Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\City;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize city edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'city_id';
        $this->_blockGroup = 'MagoArab_RegionManager';
        $this->_controller = 'adminhtml_city';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save City'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );

        $this->buttonList->update('delete', 'label', __('Delete City'));
    }

    /**
     * Retrieve text for header element depending on loaded city
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $city = $this->_coreRegistry->registry('city');
        if ($city->getId()) {
            return __("Edit City '%1'", $this->escapeHtml($city->getDefaultName()));
        }
        return __('New City');
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        /** @var \MagoArab\RegionManager\Model\City $city */
        $city = $this->_coreRegistry->registry('city');
        if ($city->getId()) {
            return $this->getUrl('*/*/save', ['city_id' => $city->getId()]);
        }
        return $this->getUrl('*/*/save');
    }
}