<?php 
namespace Magneto\Ecommerceapi\Controller\GuestAccount;
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

       		  $email = $_POST["email"];  
       		  $mobileNumber =$_POST["mobile_number"]; 
       		  $firstname = 'Guest';
       		  $lastname = 'User';
    		    $objectManager      = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager       = $objectManager->create('Magento\Store\Model\StoreManagerInterface');
            $customerFactory    = $objectManager->create('Magento\Customer\Model\CustomerFactory');
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $store = $storeManager->getStore();
            $storeId =$storeManager->getStore()->getStoreId();
            $websiteId  = $storeManager->getWebsite()->getWebsiteId();
            $customer   = $customerFactory->create();  
            $customer->setWebsiteId($websiteId);
            $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
            $customer_check->setWebsiteId($websiteId);  
            $customer_check->loadByEmail($email); 

            if ($customer_check->getId()) {

                    $response = array('status' =>'Error',
                                      'statusCode'=>226, 
                                      'message'=>'Email Id exist'); 

                    echo json_encode($response,JSON_UNESCAPED_SLASHES);

            }else{ 
        					$customer = $customerFactory->create();
        					$customer->setWebsiteId($websiteId)->setStore($store);
        					$customer->setWebsiteId($websiteId)
        					          ->setStore($store)
        					          ->setFirstname($firstname)
        					          ->setLastname($lastname)
        					          ->setEmail($email)
        					          ->setMobile($mobileNumber)
        					          ->setPassword(rand());
        					$customer->save();
        					$customerId = $customer->getId(); 
        					$response  = array('status' =>'OK',
                							   'statusCode'=>200,
                							   'id'=>$customerId, 
                							   'msessage'=>'Your Account created successfully!');
        					echo json_encode($response);
            }       
  } 
}