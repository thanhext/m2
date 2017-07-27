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
                'note'      => '(*.jpg, *.png, *.gif)',
                'before_element_html' => $this->getImageHtml('image', $model->getData('image'))
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
                'class' => 'required-url'
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

    protected function getImageHtml($field, $image)
    {
        $js     = '';
        $html   = '';
        if ($image) {
            $urlBase = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false);
            $html .= '<div id="image-wrapper">';
            $html .= '<image style="width:100%;" src="'. $urlBase . $image .'" />';
            $html .= '<input type="hidden" value="' . $image . '" name="old_' . $field . '"/>';
            $html .= '<div id="draggable2" style="display: inline-block;border: 1px solid red; position: absolute;" class="ui-widget-content"><p>You can drag me around&hellip;</p><p class="ui-widget-header">&hellip;but you can\'t drag me by this handle.</p></div>';
            $html .= '</div>';



            $js .= '<script>require(["jquery", "jquery/ui", "domReady!"], function($){';

            $js .= '$( "#draggable2" ).draggable({ containment: "#image-wrapper", scroll: false, stop: function() {
                            var wPosition = $("#image-wrapper").position();
                            var height = $("#image-wrapper").height() - wPosition.top - parseFloat($(this).css("borderTopWidth")) - parseFloat($(this).css("borderBottomWidth"));
                            var width = $("#image-wrapper").width() - wPosition.left  - parseFloat($(this).css("borderLeftWidth")) - parseFloat($(this).css("borderRightWidth"));
                            var position = $(this).position();
                            var jsStyle = JSON.stringify(position);
                            var top = (position.top * 100)/height;
                            var left = (position.left * 100)/width
                            console.log(position.left);
                            console.log(width);
                            
                            $("#item_style").val("{top: "+ top.toFixed(3) +"%;left: "+ left.toFixed(3) +"%}")} 
                        });';


            $js .= 'JSON.stringify = JSON.stringify || function (obj) {';
            $js .= '    var t = typeof (obj);';
            $js .= '    if (t != "object" || obj === null) {';
            $js .= '        if (t == "string") obj = \'"\'+obj+\'"\';';
            $js .= '        return String(obj);';
            $js .= '    }';
            $js .= '    else {';
            $js .= '        var n, v, json = [], arr = (obj && obj.constructor == Array);';
            $js .= '    for (n in obj) {';
            $js .= '        v = obj[n]; t = typeof(v);';
            $js .= '        if (t == "string") v = \'"\'+v+\'"\';';
            $js .= '        else if (t == "object" && v !== null) v = JSON.stringify(v);';
            $js .= '            json.push((arr ? "" : \'"\' + n + \'":\') + String(v));';
            $js .= '        }';
            $js .= '        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");';
            $js .= '    }';
            $js .= '};';


            $js .= 'var tmp = {one: 1, two: "2"};';
            $js .= 'var ex = JSON.stringify(tmp);';

            $js .= 'alert(ex.replace(/\,/g, \';\'));';

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