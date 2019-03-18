<?php
namespace Magneto\Ecommerceapi\Controller\Login; 

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
 
        $username = $_POST["email"];
        $isType = $_POST["isType"];   

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $url = $baseUrl."rest";
        $token_url = $url."/V1/integration/customer/token";
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
        $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create()->setWebsiteId($website_id); 
        $accountObject = $objectManager->get('\Magento\Customer\Api\AccountManagementInterface');  
        $customer = $customerFactory->loadByEmail($username);  
        $customerId = $customer->getId(); 
        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $mobile = $customer->getMobile_number();
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->setWebsiteId($website_id);
        $customer_check->loadByEmail($username); 

        if ($isType == 0 && $customer_check->getId()){ 
            $password = $_POST["password"];
            try {
		            $customer = $customerFactory->authenticate($username,$password); 
		            $ch = curl_init();
		            $data = array("username" => $username, "password" => $password);
		            $data_string = json_encode($data);
		            $ch = curl_init();
		            curl_setopt($ch,CURLOPT_URL, $token_url);
		            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		            $token = curl_exec($ch);
		            $accessToken = json_decode($token);
		             
		            $status = array('status' =>'OK',
		                            'statusCode'=>200, 
		                            'message'=>'Login Successfully',
		                            'customerId'=>$customerId,
                                    'firstname'=>$firstname,
                                    'lastname'=>$lastname,
                                    'mobile'=>$mobile,
                                    'email'=>$username);          
		      
		            echo $status1 = json_encode($status);  
            } catch (\Exception $e) {
           /* ($e->getMessage());*/
		           $status = array('status' =>'Error',
		                           'statusCode'=>300, 
		                           'message'=>'Wrong Credentials!');          
		      
		            echo $status1 = json_encode($status);
   			  }
        }elseif ($isType == 1 && $customer_check->getId()){
            $ch = curl_init(); 
            $data = array("username" => $username);
            $data_string = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $token_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $token = curl_exec($ch);
            $accessToken = json_decode($token);
 
            $status = array('status' =>'OK',
                            'statusCode'=>200, 
                            'message'=>'Login Successfully',
                            'customerId'=>$customerId,
                            'firstname'=>$firstname,
                            'lastname'=>$lastname,
                            'mobile'=>$mobile,
                            'email'=>$username);         

		            echo $status1 = json_encode($status);   
        }elseif ($isType == 2 && $customer_check->getId()) { 
            $ch = curl_init();  
            $fbUserId = $_POST["fbUserId"]; 
            $account = '@facebook.com';  
            $fbUserId = $fbUserId.''.$account;
            $data = array("username" =>$username ,"fbUserId"=>$fbUserId);  
            $data_string = json_encode($data); 
            $ch = curl_init(); 
            curl_setopt($ch,CURLOPT_URL, $token_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $token = curl_exec($ch);
            $accessToken = json_decode($token);
     
            $status = array('status' =>'OK',
                            'statusCode'=>200,  
                            'message'=>'Login Successfully',
                            'customerId'=>$customerId);        

            echo $status1 = json_encode($status);   
        }elseif ($isType == 1 && !$customer_check->getId()){  
            $status = array('status' =>'OK',
                            'statusCode'=>510, 
                            'message'=>'Please Enter Your Details');        
            echo $status1 = json_encode($status);   
       /* }elseif ($isType == 2 && !$customer_check->getId()){
            $fbUserId = $_POST["fbUserId"]; 
            $ch = curl_init();  
            $fbUserId = $_POST["fbUserId"];  
            $data = array("username" =>$username,"fbUserId"=> $fbUserId); 
            $data_string = json_encode($data);
            $ch = curl_init(); 
            curl_setopt($ch,CURLOPT_URL, $token_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $token = curl_exec($ch);
            $accessToken = json_decode($token);
     
            $status = array('status' =>'OK',
                            'statusCode'=>200,  
                            'message'=>'Login Successfully',
                            'customerId'=>$customerId);        

            echo $status1 = json_encode($status);  */    
        }else{ 
            $status = array('status'=>'Error', 
                            'statusCode'=>225,
                            'message'=>'credentials are wrong!');  
            echo $status1 = json_encode($status);   
        } 
    }       
}
