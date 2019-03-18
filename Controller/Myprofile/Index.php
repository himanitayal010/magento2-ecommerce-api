<?php 
namespace Magneto\Ecommerceapi\Controller\Myprofile;

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
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
        $customerId = $_POST["customerId"];
        $langId = $_POST["langId"];
        $currencyId = $_POST["curId"]; 

        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->setWebsiteId($website_id);
        $customer_check->load($customerId); 
        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $customerAddress = array();
        
        if ($customer_check->getId()) {
            		foreach ($customerObj->getAddresses() as $address){
            		    $customerAddress[] = $address->toArray();
            		}
            		foreach ($customerAddress as $customerAddres){
            		    $telephone = $customerAddres['telephone'];
            		}
                  		$addresses = $customer->getAddresses();
                  		$customerfname = $customer->getFirstname(); 
                  		$customerlname = $customer->getLastname();
                  		$customerMobile = $customer->getMobileNumber();
                      if($customerMobile == ''){
                        $customerMobile = "";  
                      } 
                  		$customerEmail = $customer->getEmail(); 
                      $loyaltyCheck = $customer->getLoyaltyNumber(); 
                      if($loyaltyCheck == ''){
                        $loyaltyCheck = "";
                      }
                	$myProfileUISettings = array('mediaType' =>'1',
                                            	 'backgroundMediaData'=>'#ffffff',
                                            	 'arrowColor'=>'#666666',
                                               'navDividerColor'=>'#e6e6e6',
                	         'fieldLable'=>array('textColor' =>'#666666',
                                            	 'font'=>'1',
                                            	 'fontSize'=>'14'),
                            'fieldText'=>array('textColor' =>'#212121',
                                            	 'font'=>'2',
                                            	 'fontSize'=>'17'),
                	       'buttonSubmit'=>array('font' =>'2',
                                            	 'textColor'=>'#008BFF',
                                            	 'fontSize'=>'14',
                                            	 'backgroundColor'=>'#ffffff'),
                          'buttonUpdate'=>array('font' =>'2',
                	                             'textColor'=>'#008BFF',
                	                             'fontSize'=>'14',
                	                             'backgroundColor'=>'#ffffff'),
                  'labelChangePassword' => array('font' =>'2',
                                                 'textColor'=>'#212121',
                                                 'fontSize'=>'16',
                                                 'backgroundColor'=>'#ffffff'),
               'labelDeactivateAccount' => array('font' =>'2',
                                                 'textColor'=>'#E82B1A',
                                                 'fontSize'=>'16',
                                                 'backgroundColor'=>'#ffffff'));

                  	$myProfileData = array('firstName' =>$customerfname,
                                           'lastName'=>$customerlname,
                                           'mobile'=>$customerMobile,
                                           'email'=>$customerEmail,
                                           'loyaltyCardNumber'=>$loyaltyCheck); 

                  	$status = array('status' =>'OK',
                                    'statusCode'=>200, 
                                    'message'=>'Success',
                                    'isUpdateUISettingFlag'=>'0',
                                    'myProfileUISettings'=>$myProfileUISettings,
                                    'myProfileData'=>$myProfileData);  

                    echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
                }else{ 
                    $status = array('status' =>'OK',
                                    'statusCode'=>300, 
                                    'message'=>'No Data Found!'); 
                    echo $status1 = json_encode($status); 
                }
  } 
}
