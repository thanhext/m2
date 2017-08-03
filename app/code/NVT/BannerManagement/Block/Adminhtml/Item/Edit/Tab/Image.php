<?php
namespace NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab;
/**
 * Class Info
 * @package NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\BannerManagement\Model\System\Config\Status;
class Image extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_formFactory;
    protected $_coreRegistry;
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }



    protected function _prepareForm()
    {
        $model  = $this->_coreRegistry->registry('item');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $form->setFieldNameSuffix('item');

        $fieldset=$form->addFieldset(
            'item_fieldset',
            ['legend'=>__('General')]
        );
        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'class' => 'required-entry',
                'required' => true,
                'note'      => '(*.jpg, *.png, *.gif)'
            ]
        );
        $fieldset->addField(
            'link',
            'text',
            [
                'name' => 'link',
                'label' => __('Link'),
                'title' => __('Link'),
                'required' => false,
                'class' => 'required-url'
            ]
        );
        $fieldset->addField(
            'style',
            'text',
            [
                'name' => 'style',
                'label' => __('Style'),
                'title' => __('Style'),
                'required' => false,
                'class' => 'style-url',
                'before_element_html' => $this->getImageHtml('image', $model)
            ]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => false,
                'style' => 'height:10em',
                'wysiwyg'   => true,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function getImageHtml($field, $model)
    {
        $js     = '';
        $html   = '';
        $image  = $model->getData($field);
        $style  = $model->getStyle();
        if ($image) {
            $urlBase = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false);
            $html .= '<div id="image-wrapper" class="image-wrapper">';
            $html .= '<image style="width:100%;" src="'. $urlBase . $image .'" />';
            $html .= '<input type="hidden" value="' . $image . '" name="old_' . $field . '"/>';
            $html .= '<div class="ui-widget-content desciption-image" style="'. $style .'" ></div>';
            $html .= '</div>';



            $js .= '<script>require(["jquery", "jquery/ui", "domReady!"], function($){';




            $js .= '$("body").on("change", "#item_description", function(){';
            $js .= '    var description = "<div id=\"desciption-image\" class=\"desciption-image\">"+ $(this).val() + "</div>";';
            $js .= '    if($(".desciption-image").length){';
            $js .= '        $(".desciption-image").html($(this).val());';
            $js .= '    } else {';
            $js .= '        $("#image-wrapper").append(description);';
            $js .= '    }';
            $js .= '});';

            $js .= '$(".desciption-image").draggable({ containment: "#image-wrapper", scroll: false, stop: function() {
                            var wPosition = $("#image-wrapper").position();
                            var height = $("#image-wrapper").height() - wPosition.top - parseFloat($(this).css("borderTopWidth")) - parseFloat($(this).css("borderBottomWidth"));
                            var width = $("#image-wrapper").width() - wPosition.left  - parseFloat($(this).css("borderLeftWidth")) - parseFloat($(this).css("borderRightWidth"));
                            var position = $(this).position();
                            var jsStyle = JSON.stringify(position);
                            var top = (position.top * 100)/height;
                            var left = (position.left * 100)/width
                            $("#item_style").val("top: "+ top.toFixed(3) +"%;left: "+ left.toFixed(3) +"%")} 
                        });';

            $js .= '});</script>';
            $html .= $js;
        }
        return $html;
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