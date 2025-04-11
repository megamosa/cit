<?php
/**
 * Region Controller Save
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
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
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param RegionFactory $regionFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        RegionFactory $regionFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->regionFactory = $regionFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        
        if ($data) {
            if (empty($data['region_id'])) {
                $data['region_id'] = null;
            }

            /** @var \Magento\Directory\Model\Region $model */
            $model = $this->regionFactory->create();

            $id = $this->getRequest()->getParam('region_id');
            if ($id) {
                try {
                    $model->load($id);
                    if (!$model->getId()) {
                        $this->messageManager->addErrorMessage(__('This region no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    return $resultRedirect->setPath('*/*/');
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('Something went wrong while loading the region.'));
                    $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                    return $resultRedirect->setPath('*/*/');
                }
            }

            // Validate required fields
            if (empty($data['country_id'])) {
                $this->messageManager->addErrorMessage(__('Country is required.'));
                $this->dataPersistor->set('region', $data);
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $id]);
            }

            if (empty($data['code'])) {
                $this->messageManager->addErrorMessage(__('Region code is required.'));
                $this->dataPersistor->set('region', $data);
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $id]);
            }

            if (empty($data['default_name'])) {
                $this->messageManager->addErrorMessage(__('Region name is required.'));
                $this->dataPersistor->set('region', $data);
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $id]);
            }

            // Set model data
            $model->setData($data);

            try {
                // Save model
                $model->save();
                
                // Display success message
                $this->messageManager->addSuccessMessage(__('You saved the region.'));
                
                // Clear data in session
                $this->dataPersistor->clear('region');

                // Check if 'back' button was pressed
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['region_id' => $model->getId()]);
                }
                
                // Redirect to grid
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                // Display error message
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                // Display error message
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the region.'));
                
                // Log error
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }

            // Save data in session
            $this->dataPersistor->set('region', $data);
            
            // Redirect to edit form
            return $resultRedirect->setPath('*/*/edit', ['region_id' => $this->getRequest()->getParam('region_id')]);
        }
        
        // Redirect to grid
        return $resultRedirect->setPath('*/*/');
    }
}