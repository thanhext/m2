<?php
namespace NVT\BannerManagement\Model\ResourceModel;
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('bannermanagement_item','item_id');
    }
}
