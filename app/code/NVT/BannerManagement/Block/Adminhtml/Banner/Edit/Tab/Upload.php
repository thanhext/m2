<?php
namespace NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab;
/**
 * Class Properties
 * @package NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab
 */
class Properties extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected function _prepareForm()
    {
        $model  = $this->_coreRegistry->registry('banner');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('banner_');
        $form->setFieldNameSuffix('banner');
        $fieldset =$form->addFieldSet(
            'properties_banner',
            [
                'legend'=>'Properties'
            ]
        );
        $fieldset->addField(
            'url',
            'banner',
            [
                'title'=>__('Properties Banner'),
                'name'=>'url',
                'label'=>__('Banner File'),
                'note'=>__('Allow item type: jpg, png, gif, jpeg. Select large item for better experience.'),
                'required' => true
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        parent::_prepareForm();
    }

    /**
     * Return label of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Properties Banner');
    }

    /**
     * Return title of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Properties Banner');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

}