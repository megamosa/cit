<?php
/**
 * Block Adminhtml Region
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Region extends Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_region';
        $this->_blockGroup = 'MagoArab_RegionManager';
        $this->_headerText = __('Manage States/Regions');
        $this->_addButtonLabel = __('Add New State/Region');
        parent::_construct();
        
        if ($this->_isAllowedAction('MagoArab_RegionManager::region_save')) {
            $this->buttonList->update('add', 'label', __('Add New State/Region'));
        } else {
            $this->buttonList->remove('add');
        }
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
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