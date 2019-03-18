<?php 
namespace Magneto\Ecommerceapi\Controller\MyOrders;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;


class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface{

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException {
        return null;
    }
    
    public function validateForCsrf(RequestInterface $request): ?bool{
        return true;
    } 
    public function execute() { 

      
         $customerId = $_REQUEST["customerId"];  
        $langId=$_REQUEST["langId"];
        $currencyId = $_REQUEST["curId"];   

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $currencysymbol = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currency = $currencysymbol->getStore()->getCurrentCurrencyCode(); 

        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl(); 
        $lastyear = date('Y-m-d', strtotime("-1 year"));
        $orderCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Collection');
        $orderData = $orderCollection->addAttributeToFilter('customer_id',$customerId)  
                    ->addAttributeToFilter('status','pending')
                    ->addAttributeToFilter('created_at', array('gteq'  => $lastyear))->load();
  
        $generalUISettings  = array('mediaType' =>'1',
                                    'backgroundMediaData'=>'#ffffff',
                                    'backgroundForOrderId'=>'#DEDEDE',
                                    'isShadow'=>'1');

        $myOrdersUISettings = array('myOrdersUISettings'=>array('isShadow' =>'1',
                                    'mediaType'=>'1',
                                    'backgroundMediaData'=>'#f5f5f5','backgroundForProductDetails'=>'#ffffff'),
             'orderIdText' => array('textColor' =>'#666666',
                                    'font'=>'2',
                                    'fontWeight'=>'600',
                                    'fontSize'=>'36px'),
          'orderIdSubText' => array('textColor' =>'#666666',
                                    'font'=>'2',
                                    'fontWeight'=>'600',
                                    'fontSize'=>'36px'),
     'lbelViewDetailsText' => array('textColor' =>'#666666',
                                    'font'=>'2',
                                    'fontSize'=>'36px',
                                    'arrowColor'=>'#666666'),
               'orderDate' => array('textColor' =>'#666666',
                                    'font'=>'2',
                                    'fontWeight'=>'600', 
                                    'fontSize'=>'36px'),
             'productName' => array('textColor' =>'#666666',
                                    'font'=>'2',
                  'fontWeight'=>'600',
                                    'fontSize'=>'36px'),
   'productDeliveryStatus' => array('textColor' =>'#666666',
                                    'font'=>'2',
                                    'fontWeight'=>'600',
                                    'fontSize'=>'36px',
                                    'statusColor'=>'#3EB658',
                                    'arrowColor'=>'#666666'));

        /*--------- Order Collection Start---------*/
        foreach ($orderData as $_order){
          //echo "<pre>";print_r($_order->getdata());die();

                $oId =  $_order->getEntityId();
                $orderIdText = "ORDER ID:".$oId."";
                $amount = $_order->getbase_subtotal();
                $subText = $_order->getTotalQtyOrdered()." Items "." Bill Amount: ".$amount." ".$currency;

                $orderitems  = array();
                //$myOrdersData1 = array();
              foreach ($_order->getAllVisibleItems() as $_item) {            
         
                       $itemId = $_item->getProductId(); 
                       $productName = $_item->getName();
                       $status = $_order->getStatus();
                       $proImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$_item->getProduct()->getImage();
                       $orderDate = $_order->getCreatedAt();
                       $createDate = new \DateTime($orderDate);
                       $created_at = $createDate->format('Y-m-d');

                            $orderitems[] = array('itemId' =>$itemId, 
                                                  'itemName'=>$productName,
                                                  'status'=>$status,
                                                  'statusCode'=>'1',
                                                  'image'=>$proImage); 

                              
              }       

              $myOrdersData1[] = array('orderId' =>$oId,
                                                   'orderIdText'=>$orderIdText,
                                                   'subText'=>$subText, 
                                                   'orderDate'=>$created_at,
                                                   'Orderdetails'=>$baseUrl."webcomponents/page/Myorderdetails?customerId=$customerId&langId=$langId&curId=$currencyId",
                                                   'items'=> $orderitems);           
        }      
        /*--------- Order Collection END---------*/

        /*--------- Check Order Array---------*/

        if(empty($myOrdersData1)){
                $response = array('status' =>'OK',
                                  'statusCode'=>200,
                                  'message'=>'No data found!');    

                echo json_encode($response); 
        }else{
                 /*$myOrdersData = array($myOrdersData1);*/  
                 $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
                 $customer_check->load($customerId); 

    /*--------- Check Customer --------*/

        if ( $customer_check->getId())  {
        $component = array('componentId' =>'myOrders',
                           'sequenceId'=>'1',
                           'isActive'=>'1',
                           'myOrdersUISettings'=>$myOrdersUISettings,
                           'myOrdersData'=>$myOrdersData1);
        $componentall = array($component); 
        
        $response = array('status' =>'OK',
                          'statusCode'=>200,
                          'id'=>$customerId,
                          'langId'=>$langId,
                          'message'=>'Success',
                          'isUpdateUISettingFlag'=>'0',
                          'generalUISettings'=>$generalUISettings,
                          'component'=>$componentall);    

        echo json_encode($response,JSON_UNESCAPED_SLASHES);
        }else{
          $response = array('status' =>'OK',
                            'statusCode'=>230,
                            'langId'=>$storeId, 
                            'message'=>'Please login to check order.');    

        echo json_encode($response,JSON_UNESCAPED_SLASHES);
        }
      }
  }    
} 