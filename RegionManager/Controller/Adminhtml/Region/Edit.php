<?php
/**
 * Region Controller Edit
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'MagoArab_RegionManager::region';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

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
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param RegionFactory $regionFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        RegionFactory $regionFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->regionFactory = $regionFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Edit region page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('region_id');
        $model = $this->regionFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This region no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('region', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_RegionManager::region');
        $resultPage->addBreadcrumb(__('Region Manager'), __('Region Manager'));
        $resultPage->addBreadcrumb(__('Manage States'), __('Manage States'));
        
        $title = $id ? __('Edit State/Region') : __('New State/Region');
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend($title);
        
        return $resultPage;
    }
}