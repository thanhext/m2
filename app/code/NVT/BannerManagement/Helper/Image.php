<?php
namespace NVT\BannerManagement\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;

class Image extends AbstractHelper
{
    const BANNER_PATH_CONFIG = 'banner';

    protected $_item;
    protected $_model;
    protected $_itemFactory;
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
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig
    ) {
        $this->_itemFactory = $itemFactory;
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
        $this->_model = null;
        $this->_item = null;
        $this->_imageFile = null;
        $this->attributes = [];
        return $this;
    }

    /**
     * Initialize Helper to work with Image
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @param string $imageId
     * @param array $attributes
     * @return $this
     */
    public function init($item, $imageId, $attributes = [])
    {
        $this->_reset();

        $this->setItem($item);
        $this->setImageProperties();

        return $this;
    }

    /**
     * Set image properties
     *
     * @return $this
     */
    protected function setImageProperties()
    {
        $html  = '<div style="' . $this->_item->getStyle() .'" >';
        $html .= '<img src="'. $this->getImageSrc()
                            . '" alt="'. $this->getLabel()
                            . '" width="'. $this->getWidth()
                            . '" height="'. $this->getHeight() .'" />';
        $html .= '</div>';
        return $this;
    }
    protected function getWidth()
    {
        return null;
    }
    protected function getHeight()
    {
        return null;
    }
    /**
     *
     */
    protected function getImageSrc()
    {
        $image      = $this->_item->getImage();
        $urlBase    = $this->_storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false);
        return $urlBase . $image;
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
        $label = $this->_item->getLable();
        if (empty($label)) {
            $label = $this->_item->getTitle();
        }
        return $label;
    }
}
