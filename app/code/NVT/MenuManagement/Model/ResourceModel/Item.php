<?php
namespace NVT\MenuManagement\Model\ResourceModel;
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('menumanagement_item','item_id');
    }
}
