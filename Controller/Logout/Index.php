<?php 
namespace Magneto\Ecommerceapi\Controller\Logout;

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

        $customerId = $_POST["customerId"];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeId =$storeManager->getStore()->getStoreId();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
       // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->load($customerId); 
        if ($customer_check->getId())  
        {
          $customerSession->logout(); 
          $message ='Logged Out Successfully!';  
        }  
   
        $status = array('status' =>'OK',
                        'statusCode'=>200,
                        'id'=>$customerId,
                        'langId'=>$storeId, 
                        'message'=>$message);  

        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
    }
}  