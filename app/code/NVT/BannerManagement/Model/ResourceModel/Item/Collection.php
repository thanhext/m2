<?php
namespace NVT\BannerManagement\Model\ResourceModel\Item;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\Item','NVT\BannerManagement\Model\ResourceModel\Item');
        $this->_map['fields']['item_id'] = 'main_table.item_id';
        $this->_map['fields']['store'] ='store_table.store_id';
        $this->_eventPrefix = 'bannermanager';
        $this->_eventObject = 'collection_load_item';
    }
    /**
     * Redeclare after load method for specifying collection items original data
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();

        $connection =$this->getResource()->getConnection();
        foreach ($this->_items as $item) {
            try {
//                $slect = $connection->select()->from(
//                    ['b' => $connection->getTableName('bannermanagement_banner')],
//                    ['*']
//                )->join(
//                    ['m' => $connection->getTableName('bannermanagement_banner_item')],
//                    'm.banner_id = b.banner_id',
//                    []
//                )->where('b.banner_id=?', $item->getId());
                $slect = $connection->select()->from(
                    ['b' => $connection->getTableName('bannermanagement_banner_item')],
                    ['banner_id']
                )->where('b.item_id=?', $item->getId());
                $from = $connection->fetchCol($slect);
                $item->setData('banner_id', $from);
            } catch(\Exception $e) {
                $connection->rollBack();
            }
            if ($this->_resetItemsDataChanged && ($item instanceof \Magento\Framework\Model\AbstractModel)) {
                $item->setDataChanges(false);
            }
        }
        $this->_eventManager->dispatch('core_collection_abstract_load_after', ['collection' => $this]);
        if ($this->_eventPrefix && $this->_eventObject) {
            $this->_eventManager->dispatch($this->_eventPrefix . '_load_after', [$this->_eventObject => $this]);
        }
        return $this;
    }

}
