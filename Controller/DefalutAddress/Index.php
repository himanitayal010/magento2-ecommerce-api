<?php 
namespace Magneto\Ecommerceapi\Controller\DefalutAddress; 
 
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
        
        $addressId = $_POST["addressId"]; //Your address id which you want to set as default;
        $customerId = $_POST["customerId"];
    
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $address = $objectManager->create('Magento\Customer\Model\Address')->load($addressId);
        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $website_id = $storeManager->getStore()->getWebsiteId(); 
        $customer_check->setWebsiteId($website_id);
        $customer_check->load($customerId);
        //echo "<pre>";print_r(get_class_methods($address));exit();

        $address->setCustomerId($customer->getId());
        if ($customer_check->getId() ) { 

                $address->setIsDefaultShipping('1');  
                $address->save();
                $response = array('status' =>'OK',
                                  'statusCode'=>200,
                                  'message'=>'Your address have been saved as default.');
                echo json_encode($response);  
        }else{

                $response = array('status' =>'Error',
                                'statusCode'=>300,
                                'message'=>'Your are not registered with us.');
                echo json_encode($response); 
            }
    }
  }     