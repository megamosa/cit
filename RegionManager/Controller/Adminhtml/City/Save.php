<?php
/**
 * City Controller Save
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\City;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use MagoArab\RegionManager\Api\CityRepositoryInterface;
use MagoArab\RegionManager\Model\CityFactory;

class Save extends Action
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
     * @var CityFactory
     */
    protected $cityFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param CityRepositoryInterface $cityRepository
     * @param CityFactory $cityFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        CityRepositoryInterface $cityRepository,
        CityFactory $cityFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->cityRepository = $cityRepository;
        $this->cityFactory = $cityFactory;
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
            if (empty($data['city_id'])) {
                $data['city_id'] = null;
            }

            /** @var \MagoArab\RegionManager\Model\City $model */
            $model = $this->cityFactory->create();

            $id = $this->getRequest()->getParam('city_id');
            if ($id) {
                try {
                    $model = $this->cityRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This city no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->cityRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the city.'));
                $this->dataPersistor->clear('city');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['city_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the city.'));
            }

            $this->dataPersistor->set('city', $data);
            return $resultRedirect->setPath('*/*/edit', ['city_id' => $this->getRequest()->getParam('city_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}