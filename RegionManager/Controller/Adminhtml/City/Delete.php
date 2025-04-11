<?php
/**
 * City Controller Delete
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\City;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MagoArab\RegionManager\Api\CityRepositoryInterface;

class Delete extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'MagoArab_RegionManager::city';

    /**
     * @var CityRepositoryInterface
     */
    protected $cityRepository;

    /**
     * @param Context $context
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(
        Context $context,
        CityRepositoryInterface $cityRepository
    ) {
        parent::__construct($context);
        $this->cityRepository = $cityRepository;
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
        
        $id = $this->getRequest()->getParam('city_id');
        if ($id) {
            try {
                $this->cityRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the city.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['city_id' => $id]);
            }
        }
        
        $this->messageManager->addErrorMessage(__('We can\'t find a city to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}