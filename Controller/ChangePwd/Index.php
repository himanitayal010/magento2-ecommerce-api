<?php 
namespace Magneto\Ecommerceapi\Controller\ChangePwd;

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
        public function execute()  {    


        $customerId = $_POST["customerId"]; 
        $password = $_POST["password"];
        $oldpassword = $_POST["oldPassword"];
        $langId = $_POST["langId"];
        $currencyId = $_POST["curId"]; 
        $validate; 
 
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $website_id = $storeManager->getStore()->getWebsiteId(); 
            $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
            $customerFactorys = $objectManager->create('\Magento\Customer\Model\ResourceModel\CustomerFactory'); 
            /*echo "<pre>"; print_r(get_class_methods( $customerObj ));exit();*/
      
            $username = $customerObj['email'];

            $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create()->setWebsiteId($website_id);  
            $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
            $customer_check->load($customerId);  
  
                if ($customer_check->getId()){ 
                    try {    
                             $loyalty = $customerObj['loyalty_number']; 
                             $customer = $customerFactory->authenticate($username, $oldpassword);
                             $customerObj->updateData($customerObj);
                             // $customerFactorys->saveAttribute($customerObj, 'loyalty_number');
                             /*$customer = $customerFactorys->authenticate($username, $oldpassword);*/
                             $customerObj->changePassword($password); 
                             $customerObj->save();     
                             $response = array('status' =>'OK',  
                                               'statusCode'=>200,
                                               'message'=>'Change Password Successfully!'/*,
                                            'dgfj'=>$loya*/);     
 

                        }catch(\Exception $e) {
                            $response = array('status' =>'Eror',
                                              'statusCode'=>300,   
                                              'message'=>'Old password is wrong!');     

                        }  
                }else{

                             $response=array('status' =>'Error',
                                             'statusCode'=>300,   
                                             'message'=>'Your Account is not registered with us..');    

                     }  
                            echo json_encode($response);
    }  
}