<?php
namespace Magneto\Ecommerceapi\Controller\Signup;

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
        $email = $_POST["email"];  
        $firstname =$_POST["firstname"];
        $lastname = $_POST["lastname"];
        $password =$_POST["password"];
        if($password == ''){
            $status = array('status' =>'Error',
                            'statusCode'=>300,
                            'message'=>'Please enter password.');    
            echo $status1 = json_encode($status); 
        }else{
        $mobileNumber =$_POST["mobile_number"]; 
        $loyaltycheck=$_POST["loyalty_check"]; 
        $loyaltyNumber=$_POST["loyalty_number"];     
        $isType = $_POST["isType"];
        $FbUserId = $_POST["FbUserId"];

            $objectManager      = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager       = $objectManager->create('Magento\Store\Model\StoreManagerInterface');
            $customerFactory    = $objectManager->create('Magento\Customer\Model\CustomerFactory');
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

            $storeId =$storeManager->getStore()->getStoreId();
            $websiteId  = $storeManager->getWebsite()->getWebsiteId();
            $customer   = $customerFactory->create();  
            //$customer = $customerFactory->loadByEmail($email);
            $customer->setWebsiteId($websiteId);
            $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
            $customer_check->setWebsiteId($websiteId);  
            $customer_check->loadByEmail($email); 

                    if ($customer_check->getId()) 
                    {
                            $status = array('status' =>'OK',
                                            'statusCode'=>226,
                                            'langId'=>$storeId, 
                                            'message'=>'Email Id exist');    
                            echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);

                    }else{ 
                            $customer->setEmail($email);
                            $customer->setFirstname($firstname);
                            $customer->setLastname($lastname);
                            $customer->setPassword($password); 
                            $customer->setMobileNumber($mobileNumber);
                            $customer->setLoyaltyNumber($loyaltyNumber);   
                            //$customer = $customerFactory->authenticate($email, $password);  
                            /*$customer->setLoyaltyCheck($isType);
                            $customer->setLoyaltyCheck($FbUserId);*/    
                            $customer->save(); 
                            $status = array('status' =>'OK',
                                            'statusCode'=>200,
                                            'langId'=>$storeId,    
                                            'message'=>'Your account has been created.');  
                            echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
                    }
                }
    }         
} 