<?php
namespace NVT\MenuManagement\Model;
class Menu extends \Magento\Framework\Model\AbstractModel implements \NVT\MenuManagement\Api\Data\MenuInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'menu';

    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\ResourceModel\Menu');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
