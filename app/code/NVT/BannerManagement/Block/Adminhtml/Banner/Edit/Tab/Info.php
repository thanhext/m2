<?php
namespace NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab;
/**
 * Class Info
 * @package NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */

class Info extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_bannerStatus;
    protected $_formFactory;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \NVT\BannerManagement\Model\System\Config\Status $bannerStatus,
        array $data = []
    ) {
        $this->_bannerStatus = $bannerStatus;
        parent::__construct($context, $registry, $formFactory, $data);
    }



    protected function _prepareForm()
    {
        //$id = $this->getRequest()->getParam('id');
        $model  = $this->_coreRegistry->registry('banner');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('banner_');
        $form->setFieldNameSuffix('banner');

        $fieldset=$form->addFieldset(
            'banner_fieldset',
            ['legend'=>__('General')]
        );
        $id = $model->getBannerId();
        if($id){
            $fieldset->addField(
                'banner_id',
                'hidden',
                ['name'=>'banner_id']
            );
        }
        $fieldset->addField(
          'title',
          'text',
          [
              'name'=>'title',
              'label'=>__('Title'),
              'required' => true,
              'class' => 'required-entry',
              'maxlength' =>'255',
              'note' => 'Limited characters is 255'
          ]
        );
        $fieldset->addField(
            'short_description',
            'textarea',
            [
                'name'=>'short_description',
                'label'=>__('Short Description'),
                'maxlength' =>'255',
                'note' => 'Limited characters is 255'
            ]
        );
        $fieldset->addField(
            'is_active',
            'select',
            [
                'name'=>'is_active',
                'label'=>__('Status'),
                'options'=>$this->_bannerStatus->optionArray(),
                'value' => ['Enable'=> \NVT\BannerManagement\Model\System\Config\Status::STATUS_ENABLED]
            ]
        );
        $data = $model->getData();
        if(!$id){
            $data['is_active'] = \NVT\BannerManagement\Model\System\Config\Status::STATUS_ENABLED;
        }
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function canShowTab()
    {
        return true;
    }

    public function getTabLabel()
    {
        return __('Banner Info');
    }

    public function getTabTitle()
    {
        return __('Banner Info');
    }

    public function isHidden()
    {
        return false;
    }


}