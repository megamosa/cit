<?php
/**
 * City Edit Form Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\City\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Directory\Model\Config\Source\Allregion;

class Form extends Generic implements TabInterface
{
    /**
     * @var Country
     */
    protected $countrySource;

    /**
     * @var Allregion
     */
    protected $regionSource;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Country $countrySource
     * @param Allregion $regionSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Country $countrySource,
        Allregion $regionSource,
        array $data = []
    ) {
        $this->countrySource = $countrySource;
        $this->regionSource = $regionSource;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('city');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]
        ]);

        $form->setHtmlIdPrefix('city_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('City Information')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'city_id',
                'hidden',
                ['name' => 'city_id']
            );
        }

        $fieldset->addField(
            'region_id',
            'select',
            [
                'name' => 'region_id',
                'label' => __('State/Region'),
                'title' => __('State/Region'),
                'required' => true,
                'values' => $this->regionSource->toOptionArray()
            ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('City Code'),
                'title' => __('City Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'default_name',
            'text',
            [
                'name' => 'default_name',
                'label' => __('City Name'),
                'title' => __('City Name'),
                'required' => true
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('City Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('City Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}