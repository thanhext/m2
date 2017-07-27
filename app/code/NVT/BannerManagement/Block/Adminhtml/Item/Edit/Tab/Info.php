<?php
namespace NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab;
/**
 * Class Info
 * @package NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\BannerManagement\Model\System\Config\Status;
class Info extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_formFactory;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }



    protected function _prepareForm()
    {
        //$id = $this->getRequest()->getParam('id');
        $model  = $this->_coreRegistry->registry('item');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $form->setFieldNameSuffix('item');

        $fieldset=$form->addFieldset(
            'item_fieldset',
            ['legend'=>__('General')]
        );
        $id = $model->getItemId();
        if($id){
            $fieldset->addField(
                'item_id',
                'hidden',
                ['name'=>'item_id']
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
              'maxlength' =>'255'
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
                'options'=>[ Status::STATUS_DISABLE => __('Disable'), Status::STATUS_ENABLED => __('Enable')],
                'value' => ['Enable'=> Status::STATUS_ENABLED]
            ]
        );
        $data = $model->getData();
        if(!$id){
            $data['is_active'] = Status::STATUS_ENABLED;
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
        return __('Item Info');
    }

    public function getTabTitle()
    {
        return __('Item Info');
    }

    public function isHidden()
    {
        return false;
    }


}