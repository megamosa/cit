<?php
/**
 * Region Edit Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\Region;

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
     * Initialize region edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'region_id';
        $this->_blockGroup = 'MagoArab_RegionManager';
        $this->_controller = 'adminhtml_region';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Region'));
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

        $this->buttonList->update('delete', 'label', __('Delete Region'));
    }

    /**
     * Retrieve text for header element depending on loaded region
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $region = $this->_coreRegistry->registry('region');
        if ($region->getId()) {
            return __("Edit Region '%1'", $this->escapeHtml($region->getDefaultName()));
        }
        return __('New Region');
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        $region = $this->_coreRegistry->registry('region');
        if ($region->getId()) {
            return $this->getUrl('*/*/save', ['region_id' => $region->getId()]);
        }
        return $this->getUrl('*/*/save');
    }
}