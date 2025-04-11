<?php
/**
 * Region Controller Index
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
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
     * Region list page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_RegionManager::region');
        $resultPage->addBreadcrumb(__('Region Manager'), __('Region Manager'));
        $resultPage->addBreadcrumb(__('Manage States'), __('Manage States'));
        $resultPage->getConfig()->getTitle()->prepend(__('States/Regions'));
        return $resultPage;
    }
}