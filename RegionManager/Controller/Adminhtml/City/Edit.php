<?php
/**
 * City Controller Edit
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\City;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use MagoArab\RegionManager\Api\CityRepositoryInterface;
use MagoArab\RegionManager\Model\CityFactory;

class Edit extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'MagoArab_RegionManager::city';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var CityRepositoryInterface
     */
    protected $cityRepository;

    /**
     * @var CityFactory
     */
    protected $cityFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param CityRepositoryInterface $cityRepository
     * @param CityFactory $cityFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        CityRepositoryInterface $cityRepository,
        CityFactory $cityFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->cityRepository = $cityRepository;
        $this->cityFactory = $cityFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Edit city page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('city_id');
        $model = $this->cityFactory->create();

        if ($id) {
            try {
                $model = $this->cityRepository->getById($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This city no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('city', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_RegionManager::city');
        $resultPage->addBreadcrumb(__('Region Manager'), __('Region Manager'));
        $resultPage->addBreadcrumb(__('Manage Cities'), __('Manage Cities'));
        
        $title = $id ? __('Edit City') : __('New City');
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend($title);
        
        return $resultPage;
    }
}