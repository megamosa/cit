<?php
/**
 * Region Edit Delete Button Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\Region\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->context = $context;
        $this->registry = $registry;
    }

    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        $regionId = $this->getRegionId();
        if ($regionId) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this region?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['region_id' => $this->getRegionId()]);
    }

    /**
     * Get region ID
     *
     * @return int|null
     */
    public function getRegionId()
    {
        $region = $this->registry->registry('region');
        return $region ? $region->getId() : null;
    }
}