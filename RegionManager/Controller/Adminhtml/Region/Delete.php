<?php
/**
 * Region Controller Delete
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'MagoArab_RegionManager::region';

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @param Context $context
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        Context $context,
        RegionFactory $regionFactory
    ) {
        parent::__construct($context);
        $this->regionFactory = $regionFactory;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $id = $this->getRequest()->getParam('region_id');
        if ($id) {
            try {
                // Load region model
                $model = $this->regionFactory->create();
                $model->load($id);
                
                // Delete region
                $model->delete();
                
                // Display success message
                $this->messageManager->addSuccessMessage(__('You deleted the region.'));
                
                // Redirect to grid
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                // Display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                
                // Redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $id]);
            } catch (\Exception $e) {
                // Display error message
                $this->messageManager->addErrorMessage(__('Something went wrong while deleting the region.'));
                
                // Log error
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                
                // Redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $id]);
            }
        }
        
        // Display error message if no ID was provided
        $this->messageManager->addErrorMessage(__('We can\'t find a region to delete.'));
        
        // Redirect to grid
        return $resultRedirect->setPath('*/*/');
    }
}