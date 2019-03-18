<?php
namespace Magneto\EcommerceApi\Model\ResourceModel\GetNotification;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection 
{
    protected function _construct()
    {
        $this->_init('Magneto\EcommerceApi\Model\GetNotification','Magneto\EcommerceApi\Model\ResourceModel\GetNotification');
    }
}
