<?php
namespace Magneto\EcommerceApi\Model\Resource;

class GetNotification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ashrafs_notification', 'id');
    }
}