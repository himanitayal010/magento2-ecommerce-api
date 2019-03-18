<?php
namespace Magneto\Ecommerceapi\Controller\NotificationDelete;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException {
        return null;
    }
    
    public function validateForCsrf(RequestInterface $request): ?bool{
        return true;
    }

    public function execute() 
    { 
        $customerId = $_POST['customerId'];
        $deviceId = $_POST['deviceId'];
        if(count($deviceId) != 1){ 
            echo 'Device Id is mandatory.';
        }
        $notification_id = $_POST['notificationId'];

        $flag = $_POST['flag'];
        if(count($customerId) != 1){ 
            echo 'Error: Flag is mandatory.';
        }
        // Flag 0 = Deleted/Readed

        $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 

        /* Set Read Notification Data */
        $updateTable = $objectManager->get('Magneto\AppNotification\Model\AppNotificationUpdate');

        $collection = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotificationUpdate\Collection')->addFieldToFilter('device_id', $deviceId)->addFieldToFilter('notification_id', $notification_id);

        // Already Inserted Data
        if(count($collection) >= 1){
            foreach ($collection as $value) {
                $id = $value->getId();

                $update_not_id = $value->getNotificationId();

                if($notification_id){  // If Notification Id given
                    $data = array('id'=>$id, 'notification_id'=>$notification_id, 'customer_id'=>$customerId, 'device_id'=>$deviceId);
                    $updateTable->setData($data);
                    $updateTable->getData();
                    $updateTable->save();
                    $result = 'Notification deleted successfully';
                }
                if(empty($notification_id)){ // If Notification ID not given delete(0) all notification of given customer id
                  $collection = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id',$customerId);
                    if(count($update_not_id) >=1){
                        
                        $data = array('id'=>$id, 'notification_id'=>$update_not_id, 'customer_id'=>$customerId, 'device_id'=>$deviceId);
                        $updateTable->setData($data);
                        $updateTable->getData();
                        $updateTable->save();
                        $result = 'Notification deleted successfully';
                    }else{
                        foreach ($collection as $value) {
                        $not_id = $value->getAppnotificationId();
                        $data = array('id'=>$id, 'notification_id'=>$not_id, 'customer_id'=>$customerId, 'device_id'=>$deviceId);
                        $updateTable->setData($data);
                        $updateTable->getData();
                        $updateTable->save();
                        $result = 'Notification deleted successfully';
                    }
                }
                  
                }       
            }
        }else{
            // All Read notification Collection
            if($notification_id && $customerId){  // If Notification Id given
                $data = array('notification_id'=>$notification_id, 'customer_id'=>$customerId, 'device_id'=>$deviceId);
                $updateTable->setData($data);
                $updateTable->getData();
                $updateTable->save();
                $result = 'Notification deleted successfully';
            }
            elseif(empty($notification_id) && empty($customerId)){ // If Notification ID not given delete(0) all notification of given customer id
                $collection = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id','0');
                if(count($collection) >= 1){
                    foreach ($collection as $value) {
                        $not_id = $value->getAppnotificationId();
                        $data = array('notification_id'=>$not_id, 'customer_id'=>'0', 'device_id'=>$deviceId);
                        $updateTable->setData($data);
                        $updateTable->getData();
                        $updateTable->save();
                        $result = 'Notification deleted successfully';
                    }
                }else{
                    $result = 'Notification not found';                  
                }        
            }
            elseif($notification_id && empty($customerId)){
                $data = array('notification_id'=>$notification_id, 'customer_id'=>'0', 'device_id'=>$deviceId);
                $updateTable->setData($data);
                $updateTable->getData();
                $updateTable->save();
                $result = 'Notification deleted successfully';
            }
            elseif(empty($notification_id) && $customerId){
                $collection = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id',$customerId);
                if(count($collection) >= 1){
                    foreach ($collection as $value) {
                        $not_id = $value->getAppnotificationId();
                        $data = array('notification_id'=>$not_id, 'customer_id'=>$customerId, 'device_id'=>$deviceId);
                        $updateTable->setData($data);
                        // $updateTable->getData();
                        $updateTable->save();
                        $result = 'Notification deleted successfully';
                    }
                }else{
                    $result = 'Notification not found';                  
                }        
            }
        }        

        // Set Response
        $status = array('status'=>'OK',
                        'statusCode'=>200,
                        'message'=>$result);  

        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);  

    }

}
