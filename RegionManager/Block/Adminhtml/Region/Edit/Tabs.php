<?php
/**
 * Region Edit Tabs Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\Region\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('region_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Region Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main_section',
            [
                'label' => __('Region Information'),
                'title' => __('Region Information'),
                'content' => $this->getChildHtml('form'),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}