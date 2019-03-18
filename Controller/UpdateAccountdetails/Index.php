<?php 
namespace Magneto\Ecommerceapi\Controller\UpdateAccountdetails;

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
            $langId =$_POST["langId"];
    	    $currencyId = $_POST["curId"];
            $customerId = $_POST["customerId"];
            $firstname = $_POST["firstname"];
            $mobileNumber= $_POST["mobile_number"];
            $loyaltyCheck= $_POST["loyalty_number"];
            $loyaltyCheck1 = $_POST["loyalty_check"];
    	    $lastname = $_POST["lastname"]; 
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\Customer')
            ->load($customerId);
			$email1 = $customerObj->getEmail();
			$email = $email1; 
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			$websiteId = $storeManager->getStore()->getWebsiteId();
            $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
            $customer_check->load($customerId);

            if ($customer_check->getId()){
        			$customerRepository = $objectManager->create('Magento\Customer\Api\CustomerRepositoryInterface');
        			$customer = $customerRepository->get($email, $websiteId);  
        			$customer = $customerRepository->getById($customerId);
        			$customer->setFirstname($firstname);
        			$customer->setLastname($lastname);
        			$customer->setCustomAttribute('mobile_number',$mobileNumber);
        			$customer->setCustomAttribute('loyalty_number',$loyaltyCheck);  
        			/*$customer->setMobileNumber($mobileNumber); */
        			$customerRepository->save($customer);
        			$status = array('status' =>'OK',
                                    'statusCode'=>200,
                                    'id'=>$customerId,  
                                    'message'=>'Your Information has been saved.');        

                    echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
            }else{
                    $status = array('status' =>'Error',
                                    'statusCode'=>300,
                                    'message'=>'Please fill all the fields.');        

                    echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
            }
            
            }
			}