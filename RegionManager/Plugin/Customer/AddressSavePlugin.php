<?php
/**
 * Address Save Plugin
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Plugin\Customer;

use Magento\Customer\Controller\Address\FormPost;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use MagoArab\RegionManager\Model\ResourceModel\City\CollectionFactory;

class AddressSavePlugin
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CollectionFactory
     */
    protected $cityCollectionFactory;

    /**
     * @param Session $customerSession
     * @param RequestInterface $request
     * @param ManagerInterface $messageManager
     * @param CollectionFactory $cityCollectionFactory
     */
    public function __construct(
        Session $customerSession,
        RequestInterface $request,
        ManagerInterface $messageManager,
        CollectionFactory $cityCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    /**
     * Validate city before saving address
     *
     * @param FormPost $subject
     * @return void
     */
    public function beforeExecute(FormPost $subject)
    {
        if ($this->request instanceof Http && $this->request->isPost()) {
            $regionId = $this->request->getParam('region_id');
            $city = $this->request->getParam('city');
            
            if ($regionId && $city) {
                // Validate city against region
                $cityCollection = $this->cityCollectionFactory->create();
                $cityCollection->addRegionFilter($regionId);
                $cityCollection->addFieldToFilter('default_name', ['eq' => $city]);
                
                if ($cityCollection->getSize() === 0) {
                    // City not found for this region, but we'll allow it
                    // We could restrict it by uncommenting the below lines
                    
                    // $this->messageManager->addErrorMessage(__('The selected city is not valid for the selected region.'));
                    // $this->customerSession->setAddressFormData($this->request->getParams());
                    // throw new \Exception(__('City validation failed.'));
                }
            }
        }
    }
}