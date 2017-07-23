<?php
namespace NVT\BannerManagement\Model\ResourceModel\Item;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\Item','NVT\BannerManagement\Model\ResourceModel\Item');
    }
}
