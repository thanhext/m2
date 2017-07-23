<?php
namespace NVT\BannerManagement\Block\Adminhtml\Banner\Edit;
/**
 * Class Tabs
 * @package NVT\BannerManagement\Block\Adminhtml\Banner\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('banner_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Banner Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'banner_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock('NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab\Info')->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}