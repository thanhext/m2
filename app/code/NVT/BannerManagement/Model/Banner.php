<?php
namespace NVT\BannerManagement\Model;
class Banner extends \Magento\Framework\Model\AbstractModel implements \NVT\BannerManagement\Api\Data\BannerInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG         = 'banner';

    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\ResourceModel\Banner');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
