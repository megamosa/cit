<?php
/**
 * Block Adminhtml City
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class City extends Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_city';
        $this->_blockGroup = 'MagoArab_RegionManager';
        $this->_headerText = __('Manage Cities');
        $this->_addButtonLabel = __('Add New City');
        parent::_construct();
        
        if ($this->_isAllowedAction('MagoArab_RegionManager::city_save')) {
            $this->buttonList->update('add', 'label', __('Add New City'));
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