<?php
namespace NVT\BannerManagement\Model;
class Item extends \Magento\Framework\Model\AbstractModel implements \NVT\BannerManagement\Api\Data\ItemInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'item';

    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\ResourceModel\Item');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
