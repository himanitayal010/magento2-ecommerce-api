<?php
namespace Magneto\Ecommerceapi\Controller\Notification;

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
        if(empty($deviceId)){ 
            echo 'Device Id is mandatory.';
        }
        $pageSize = $_REQUEST["pageSize"];
        $page = $_REQUEST["page"];
        $langId = $_REQUEST["langId"];

        $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();

        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 

        /*Get Notification Data From Admin*/
        $updateTable = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotificationUpdate\Collection')->addFieldToFilter('device_id', $deviceId);

        if(!empty($updateTable->getData())){
          foreach ($updateTable as $updateData) {
            $not_id[] = $updateData->getNotificationId();
            $cust_id = $updateData->getCustomerId();
          }
          if(empty($customerId)){
            $customerId = $cust_id;
          }

          $connectionUrl = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id',$customerId)->addFieldToFilter('store_ids', $langId);

          $connectionUrl->setOrder('created_at', 'desc');
          $connectionUrl->setPageSize($pageSize);
          $connectionUrl->setCurPage($page);

          $total_pages = $connectionUrl->getLastPageNumber();
          // echo '<pre>'; print_r($connectionUrl->getData());echo 'asd'; print_r($cust_id); die();
            foreach ($connectionUrl as $value) {
              $appid = $value->getAppnotificationId();
              if(in_array($appid, $not_id)){
                // 
              }else{
                $id = $value->getAppnotificationId();
                    $title = $value->getTitle();
                    $subtitle = $value->getSubtitle();
                    $time = $value->getCreatedAt();
                    $image = $value->getImage();
                    $result[] = array('id'=>$id,'titleText' => $title,'subTitleText' => $subtitle,'image'=>$baseUrl.'pub/media/'.$image,'time' => $time,'type'=>'1','navigationFlag'=>'1','query'=>$baseUrl."ashrafs/ecommerceapi/productdetail?productId=7&customerId=22&langId=1&currencyId=1");
              }
            }
            if(empty($result)){
              $notificationData = '';
            }else{
              $notificationData = array('list'=>$result);
            }
            
        }else{
          if(empty($customerId)){
            $customerId = '0';
          }
          $connectionUrl = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id',$customerId)->addFieldToFilter('store_ids', $langId);

          $connectionUrl->setOrder('created_at', 'desc');
          $connectionUrl->setPageSize($pageSize);
          $connectionUrl->setCurPage($page);
          $total_pages = $connectionUrl->getLastPageNumber();
            foreach ($connectionUrl as $value) {
              $id = $value->getAppnotificationId();
                    $title = $value->getTitle();
                    $subtitle = $value->getSubtitle();
                    $time = $value->getCreatedAt();
                    $image = $value->getImage();
                    $result[] = array('id'=>$id,'titleText' => $title,'subTitleText' => $subtitle,'image'=>$baseUrl.'pub/media/'.$image,'time' => $time,'type'=>'1','navigationFlag'=>'1','query'=>$baseUrl."ashrafs/ecommerceapi/productdetail?productId=7&customerId=22&langId=1&currencyId=1");
            }
            if(empty($result)){
              $notificationData = ''; 
            }else{
              $notificationData = array('list'=>$result);
            }
          }

          if($page > $total_pages){
            $response = array('status' =>'Error',
                              'statusCode'=>300,
                              'message'=>'No data found!');    
            echo json_encode($response);
          }elseif(empty($notificationData)){
            $response = array('status' =>'Error',
                              'statusCode'=>300,
                              'message'=>'No data found!');    
            echo json_encode($response);
          }else{ 
            
        $notificationUISettings = array('isShadow'=>'1',
                                        'shadowColor'=>'#a4a4a4',
                                        'mediaType'=>'1',
                                        'backgroundMediaData'=>'#ffffff',
                                        'notificationIcon'=>$baseUrl.'pub/media/notification-flat.jpg',
                         'title'=>array('textColor'=>'#212121',
                                        'font'=>'1',
                                        'fontSize'=>'15'),
                   'description'=>array('textColor'=>'#666666',
                                        'font'=>'1',
                                        'fontSize'=>'12'),
                          'time'=>array('textColor'=>'#9e9e9e',
                                        'font'=>'1',
                                        'fontSize'=>'10')); 
        $component[] =array('componentId'=>'notification',
                          'sequenceId'=>'1',
                          'isActive'=>'1',
                          'notificationUISettings'=>$notificationUISettings,
                          'notificationData'=>$notificationData);
 
        $status = array('status'=>'OK',
                        'statusCode'=>200,
                        'message'=>'Success',
                        'isUpdateUISettingFlag'=>'1',
                        'generalUISettings'=>array('backgroundColor'=>'#f6f6f6',
                                                   'isShadow'=>'1',
                                                   'navDividerColor'=>'#DEDEDE'),
                        'component'=>$component);  

        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);  
      }
    }
}