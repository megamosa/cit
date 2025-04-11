<?php
/**
 * Region Edit Form Block
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Block\Adminhtml\Region\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Model\Config\Source\Country;

class Form extends Generic implements TabInterface
{
    /**
     * @var Country
     */
    protected $countrySource;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Country $countrySource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Country $countrySource,
        array $data = []
    ) {
        $this->countrySource = $countrySource;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('region');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]
        ]);

        $form->setHtmlIdPrefix('region_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Region Information')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'region_id',
                'hidden',
                ['name' => 'region_id']
            );
        }

        $fieldset->addField(
            'country_id',
            'select',
            [
                'name' => 'country_id',
                'label' => __('Country'),
                'title' => __('Country'),
                'required' => true,
                'values' => $this->countrySource->toOptionArray()
            ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('Region Code'),
                'title' => __('Region Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'default_name',
            'text',
            [
                'name' => 'default_name',
                'label' => __('Region Name'),
                'title' => __('Region Name'),
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
        return __('Region Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Region Information');
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