<?php
namespace NVT\BannerManagement\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;

class Item extends AbstractHelper
{
    const BANNER_PATH_CONFIG = 'banner';

    protected $_item;
    protected $_html;
    protected $_class;
    protected $_itemFactory;
    protected $_storeManager;
    protected $_imageFile;
    protected $_assetRepo;
    protected $_isfrontend = true;

    /**
     * Image constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \NVT\BannerManagement\Model\ItemFactory $itemFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \NVT\BannerManagement\Model\ItemFactory $itemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig
    ) {
        $this->_itemFactory = $itemFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
        $this->_assetRepo = $assetRepo;
        $this->viewConfig = $viewConfig;
    }

    /**
     * Reset all previous data
     *
     * @return $this
     */
    protected function _reset()
    {
        $this->_html = null;
        $this->_item = null;
        $this->_imageFile = null;
        $this->_class = null;
        return $this;
    }

    /**
     * Initialize Helper to work with Item
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @return $this
     */
    public function init($item, $class = null)
    {
        $this->_reset();
        $this->setItem($item);
        $this->setClass($class);
        $this->setImageProperties();
        return $this;
    }

    /**
     * Set current Item
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @return $this
     */
    protected function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }
    protected function setClass($class)
    {
        if(is_array($class)){
            $rclass = implode(' ', $class);
        }
        else {
            $rclass = $class;
        }
        $rclass .= ' item-wrapper item-'. $this->_item->getId();
        $this->_class = trim($rclass);
    }
    public function getClass()
    {
        return $this->_class;
    }
    /**
     * Set image properties
     *
     * @return $this
     */
    protected function setImageProperties()
    {
        $html  = '<div class="'. $this->getClass() .'" style="position: relative;z-index: 1;" >';
        $html .= '  <img src="'. $this->getImageSrc() .'" alt="'. $this->getAlt() .'" />';
        $html .= '  <div class="caption" style="position: absolute;z-index: 2;' . $this->_item->getStyle() .'">'. $this->getLabel() .'</div>';
        $html .= '</div>';
        $this->_html = $html;
        return $this;
    }
    public function toHtml()
    {
        return $this->_html;
    }

    public function _init($item)
    {
        $this->_reset();
        $this->setItem($item);
        $this->wrapperItem();
        return $this;
    }
    public function wrapperItem()
    {
        $labels = [['text'=>'add test text', 'style'=>null], ['text'=>'add text js', 'style'=>null]];
        $labels = [];
        $html  = '<div class="__wrapper" style="position: relative;z-index: 1;" >';
        $html .= '    <div data-role="header" ><div data-role="toolbar"><span class="__icon-add" data-role="add"></span><span class="__icon-edit" data-role="edit"></span><span class="__icon-remove" data-role="remove"></span></div></div>';
        $html .= '    <div data-role="content" >';
        if(count($labels)){
            foreach ($labels as $label){
                $html .= $this->addWidget($label);
            }
        }
        $html .= '    </div>';
        if($this->_item->getId()){
            $html .= '  <img src="'. $this->getImageSrc() .'" alt="'. $this->getAlt() .'" />';
        }
        $html .= '</div>';
        $html .= '<script>require(["jquery", "jquery/ui", "domReady!"], function($){';
        // add widget
        $html .= '$("body").on("click", ".__wrapper > [data-role=\"header\"] [data-role=\"toolbar\"] [data-role=\"add\"]", function(){';
        $html .= '    var elementContent = $(this).parent().parent().next("[data-role=\"content\"]");';
        $html .= '    var item = \''. $this->addWidget() . '\';';
        $html .= '    elementContent.append(item);';
        // draggable widget
        $html .= '    if($(".__wrapper").find("[data-role=\"widget\"]").length){';
        $html .= '      $(".__wrapper").find("[data-role=\"widget\"]").draggable({ containment: ".__wrapper", cancel: "[data-role=\"body\"]", scroll: false, stop: function() {
                            var element = $(".__wrapper");
                            var wPosition = element.position();
                            var height = element.height() - wPosition.top - parseFloat($(this).css("borderTopWidth")) - parseFloat($(this).css("borderBottomWidth"));
                            var width = element.width() - wPosition.left  - parseFloat($(this).css("borderLeftWidth")) - parseFloat($(this).css("borderRightWidth"));
                            var position = $(this).position();
                            var top = (position.top * 100)/height;
                            var left = (position.left * 100)/width;
                            var style = "top: "+ top.toFixed(3) +"%;left: "+ left.toFixed(3) +"%;";
                            var text = $(this).find("[data-role=\"body\"]").html();
                            var data = "{\"text\": \""+ text +"\", \"style\": \""+ style +"\"}"; 
                            $(this).find("input").val(data);
                            selectionUpdate($(this).index());
                          } 
                        });';
        $html .= '    }';
        $html .= '});';

        // remove widget
        $html .= '$("body").on("click", "[data-role=\"widget\"] [data-role=\"remove\"]", function(){
                     var elementWidget = $(this).parent().parent().parent("[data-role=\"widget\"]");
                     if(confirm("Are you sure you want to delete item!")){
                        elementWidget.remove();
                     }
                 });';
        // remove widget by selector
        $html .= '$("body").on("click", ".__wrapper > [data-role=\"header\"] [data-role=\"toolbar\"] [data-role=\"remove\"]", function(){
                    var selector = $(this).attr("data-selector");
                    if (typeof selector === "undefined") {
                        alert("please pick an item.");
                    }
                    else{
                        if(confirm("Are you sure you want to delete item!")){
                            $(".__wrapper "+ selector).remove();
                        }
                    }
                 });';



        // click change selector
        $html .= '$("body").on("click", ".__wrapper [data-role=\"widget\"]", function(){
                    selectionUpdate($(this).index());
                 });';

        // function add sellection
        $html .= 'function selectionUpdate(idx){
                  var selector = "[data-role=\"widget\"]:eq("+ idx +")" 
                  var element = $(".__wrapper > [data-role=\"header\"] [data-role=\"toolbar\"]");
                  var eEdit = element.find("[data-role=\"edit\"]");
                  var eRemove = element.find("[data-role=\"remove\"]");
                      eEdit.attr("data-selector", selector);
                      eRemove.attr("data-selector", selector);
                 }';
        $html .= '});</script>';

        $this->_html = $html;
    }

    public function addWidget($data = '')
    {
        $html  = '';
        if($data){
            $label = json_decode($data);
        }
        else{
            $label = new \stdClass;
            $label->text = __('Click here to change the content.');
            $label->style = null;
        }
        $html .= '    <div data-role="widget" style="'. $label->style .'">';
        $html .= '        <div data-role="header" ><input type="hidden"><div data-role="toolbar"><span class="__icon-add" title="Add/Edit" data-role="add"></span><span class="__icon-remove" title="Remove" data-role="remove"></span></div></div>';
        $html .= '        <div data-role="body">';
        $html .=            $label->text;
        $html .= '        </div>';
        $html .= '    </div>';
        return $html;
    }
    public function editContent()
    {
        $html  = '';
        $html .= '<div class="__wrapper_edit_widget">';

        $html .= '</div>';
        return $html;
    }
    /**
     * @return string
     */
    public function getImageSrc()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false)
                . $this->_item->getImage();
    }

    /**
     * Get current Image model
     *
     * @return \NVT\BannerManagement\Model\ItemFactory
     */
    protected function _getModel()
    {
        if (!$this->_model) {
            $this->_model = $this->_itemFactory->create();
        }
        return $this->_model;
    }


    /**
     * Get current Item
     *
     * @return \NVT\BannerManagement\Model\Item
     */
    protected function getItem()
    {
        return $this->_item;
    }

    /**
     * Set Image file
     *
     * @param string $file
     * @return $this
     */
    public function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    /**
     * Get Image file
     *
     * @return string
     */
    protected function getImageFile()
    {
        return $this->_imageFile;
    }
    /**
     * Return image label
     *
     * @return string
     */
    public function getLabel()
    {
        return json_decode($this->_item->getDescription());
    }
    public function getAlt()
    {
        return $this->_item->getTitle();
    }
}
