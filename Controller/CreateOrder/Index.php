<?php 
namespace Magneto\Ecommerceapi\Controller\CreateOrder;
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
    public function execute() 
    { 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 

        $customerId = $_REQUEST["customerId"];
        $paymentId = $_REQUEST["paymentId"];

        $customerRepository =  $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');
        /*$address =  $objectManager->get('\Magento\Customer\Model\Address\Config'); */
        $activeShipping = $objectManager->create('Magento\Shipping\Model\Config')->getActiveCarriers(); 

        $customer = $customerRepository->getById($customerId);

        $addressId = $customer->getDefaultShipping();  
        $addressRepository = $objectManager->get('\Magento\Customer\Api\AddressRepositoryInterface'); 
        $address1 = $addressRepository->getById($addressId);

        $fName = $address1->getFirstname(); 
        $lName = $address1->getLastname();
        $region = 'BH';
        $city = $address1->getCity();
        $cId = $address1->getCountryId();
        $postCode = $address1->getPostcode();
        $telephone =$address1->getTelephone();
        $street = $address1->getStreet(); 
        
        
        if($customerId){
            $quote = $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId);
            $totalItems=count($quote->getAllItems()); 
            if($totalItems==0){
                    $response = array('status'=>'Error',
                                      'statusCode'=>300,
                                      'message'=>'Your cart is empty!');

                    echo json_encode($response);
            } else {
            /*$billing = $quote->setBillingAddress($addressData); 
            $shipping= $quote->setShippingAddress($addressData);*/
            $quote->getBillingAddress()->setRegion($region);  
            $quote->getBillingAddress()->setCity($city); 
            $quote->getBillingAddress()->setCountryId($cId);
            $quote->getBillingAddress()->setPostcode($postCode);
            $quote->getBillingAddress()->setStreet($street);
            $quote->getBillingAddress()->setTelephone($telephone);
            $quote->getBillingAddress()->setFirstName($fName);
            $quote->getBillingAddress()->setLastName($lName);
            $quote->getShippingAddress()->setRegion($region);  
            $quote->getShippingAddress()->setCity($city); 
            $quote->getShippingAddress()->setCountryId($cId);
            $quote->getShippingAddress()->setPostcode($postCode);
            $quote->getShippingAddress()->setStreet($street);
            $quote->getShippingAddress()->setTelephone($telephone);
            $quote->getShippingAddress()->setFirstName($fName);
            $quote->getShippingAddress()->setLastName($lName);
            
            $shippingAddress=$quote->getShippingAddress();

            //set shipping method is not working

            $shippingAddress->setCollectShippingRates(true)
                            ->collectShippingRates()
                            ->setShippingMethod('freeshipping_freeshipping'); 

            $quote->setPaymentMethod('cashondelivery'); //payment method

            $quote->setInventoryProcessed(false); //not effetc inventory

            $quote->save();

            $quote->getPayment()->importData(['method' => 'cashondelivery']); 

            $quote->collectTotals()->save();

            $order = $objectManager->get('\Magento\Quote\Model\QuoteManagement')->submit($quote); 
           /* $order->setEmailSent(0);
            $order->setEmailSent(0);*/
            $increment_id = $order->getRealOrderId();
            if($order->getEntityId()){ 
                $orderId = $order->getRealOrderId();
            }else{
                $orderId =['error'=>1,'msg'=>'Your custom message'];
            }
                    // Send Notification
        /*$title = $this->escapeHtml(__("Order Placed. Order number :")).$orderId;
        $subtitle = $this->escapeHtml(__("Your order has been placed successfully. "));

        if($website_id == '2'){
            $title = $this->escapeHtml(__("تم الطلب. رقم الطلب :")).$orderId;
            $subtitle = $this->escapeHtml(__("تم تقديم طلبك بنجاح. "));
        }
        // Get Count Start
        $notificationupdateTable = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotificationUpdate\Collection')->addFieldToFilter('customer_id', $customerId);
            $notif_update_count = count($notificationupdateTable); 

            $notificationTable = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id', $customerId);
                    $notif_count = count($notificationTable);

                    $badge = $notif_count - $notif_update_count;
                    // Get Count End

                    $url = 'https://fcm.googleapis.com/fcm/send';         
                    $headers = array(
                                'Authorization:key=AAAAHP7-WOo:APA91bE-8UF0Q2HHcVNok7syzKrKRuC4lA_Q5St-KR3dqgGtWFmu4WNtY-X6Pm0topyHlIH291jktxSWyNRaZw1b-zLV2yW9zb_MR-vc9CbA9uzMy3LxDDpBU_mGXaL9Iq7EeTaQlFN8',
                                'Content-Type: application/json'
                            );  

                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                    $fcm_token_android = $fcm_token_ios = array();
                        $collection = $objectManager->get('\Magneto\AppNotification\Model\ResourceModel\RegisterDevice\Collection')->addFieldToFilter('customer_id', $customerId);

                    if($collection->getData()){
                            foreach ($collection as $value) {
                                if($value->getDeviceType() == 'android'){
                                    $fcm_token_android[] = $value->getFirebaseToken();
                                }
                                if($value->getDeviceType() == 'ios'){
                                    $fcm_token_ios[] = $value->getFirebaseToken();
                                }
                            }
                            // Send for ANDROID
                            $payload = array(
                                // 'to' => $send_to,
                                "registration_ids" => $fcm_token_android,
                                'priority' => 'high',
                                'content_available' => true,
                                "mutable_content" => true,
                                "data" => [
                                    "mediaUrl" => "",
                                    "id" => $customerId,
                                    "AppName" => 'Ecommerce',
                                    "logo" => "".$baseUrl."pub/media/magneto/appnotification/mobilelogo.png",
                                    "query" => $baseUrl.'webcomponents/page/myorders?orderId='.$order->getId(),
                                    "navigationFlag" => '1',
                                    "type" => '4',
                                    "title" => $title,
                                    "body" => $subtitle,
                                    "badge" => $badge
                                ]
                            );
                            $ch = curl_init();
                            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                            curl_setopt( $ch,CURLOPT_POST, true );
                            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
                            $result = curl_exec($ch );
                            curl_close( $ch );

                            // Send for IOS
                            $payload = array(
                                    "registration_ids" => $fcm_token_ios,
                                    "priority" => "high",
                                    "mutable_content"=> true,
                                    "notification"=> [
                                        "id"=> "".$customerId."",
                                        "AppName"=>"Ecommerce",
                                        "logo"=>"".$baseUrl."pub/media/magneto/appnotification/mobilelogo.png",
                                        "title"=>"".$title."",
                                        "subtitle"=>"".$subtitle."",
                                        "badge"=> "".$badge."",
                                        "image"=>"",
                                        "query"=> "".$baseUrl."webcomponents/page/myorders?orderId=".$order->getId()."",
                                        "navigationFlag"=> "1", 
                                        "type"=>"4"
                                    ]

                            );
                            $ch = curl_init();
                            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                            curl_setopt( $ch,CURLOPT_POST, true );
                            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
                            $result = curl_exec($ch );
                            curl_close( $ch );
                    }*/
                
                    $response = array('status' =>'OK',
                                      'statusCode'=>200,
                                      'msessage'=>'Your Order has been created successfully!');

                    echo json_encode($response);


            }
        } else {
                    $response = array('status' =>'Error',
                                      'statusCode'=>300,
                                      'msessage'=>'Please try again!');

                    echo json_encode($response);
        } 
        

        
    }
}