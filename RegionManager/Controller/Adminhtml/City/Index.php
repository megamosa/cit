<?php
/**
 * City Controller Index
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\City;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
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
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * City list page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_RegionManager::city');
        $resultPage->addBreadcrumb(__('Region Manager'), __('Region Manager'));
        $resultPage->addBreadcrumb(__('Manage Cities'), __('Manage Cities'));
        $resultPage->getConfig()->getTitle()->prepend(__('Cities'));
        return $resultPage;
    }
}