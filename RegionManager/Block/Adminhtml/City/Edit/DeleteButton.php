<?php
/**
 * City Edit Delete Button Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\City\Edit;

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
        $cityId = $this->getCityId();
        if ($cityId) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this city?')
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
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['city_id' => $this->getCityId()]);
    }

    /**
     * Get city ID
     *
     * @return int|null
     */
    public function getCityId()
    {
        $city = $this->registry->registry('city');
        return $city ? $city->getId() : null;
    }
}