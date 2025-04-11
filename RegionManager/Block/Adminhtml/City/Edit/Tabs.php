<?php
/**
 * City Edit Tabs Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\City\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('city_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('City Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main_section',
            [
                'label' => __('City Information'),
                'title' => __('City Information'),
                'content' => $this->getChildHtml('form'),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}