<?php 
namespace Magneto\Ecommerceapi\Controller\MyAccount;

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
    
        $customerId = $_REQUEST["customerId"];  
        $langId =$_REQUEST["langId"];
        $currencyId =$_REQUEST["curId"];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
        $store = $objectManager->get('Magento\Framework\Locale\Resolver'); 
        $lang = $store->getLocale(); 
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeId =$storeManager->getStore()->getStoreId();
        $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
        
        $customer = $customerFactory->load($customerId); 
        $email = $customer->getEmail();
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->setWebsiteId($website_id);
        $customer_check->loadByEmail($email); 
        $myAccountUISettings = array('arrowColor' =>'#666666',
                                     'dividerColor'=>'#dedede',
                                     'mediaType'=>'1',
                                     'backgroundMediaData'=>'#ffffff',
                     'title'=> array('textColor' =>'#4A4A4A',
                                      'font'=>'2',
                                      'fontSize'=>'16'),
                     'logout'=> array('textColor' =>'#308afc',
                                      'font'=>'2', 
                                      'fontSize'=>'16'));  

        $logout = array('title' =>'Logout',
                         'type'=>'NATIVE',
                         'navigation'=>'popup');  

        $legal = array('title' =>'Legal', 
                       'type'=>'NATIVE',
                       'navigation'=>'LegalPoliciesVC');

        $accountSettings = array('title' =>'Account Settings',
                                 'type'=>'NATIVE',
                                 'navigation'=>'UpdateIInformationVC');

        $contactUs = array('title' =>'Contact Us',
                           'type'=>'WEB',
                           'navigation'=>$baseUrl."webcomponents/page/Contact");

        $clearHistory = array('title' =>'Clear History',
                             'type'=>'NATIVE',
                             'navigation'=>'popup');

        $languagecurrency = array('title' =>'Language & Currency',
                                  'type'=>'NATIVE',
                                  'navigation'=>'LanguageVc');

        $notification = array('title' =>'Notifications',
                              'type'=>'NATIVE',
                              'navigation'=>'NotificationVC');

        $myReviews = array('title' =>'My Reviews',
                           'type'=>'NATIVE',
                           'navigation'=>'MyReviewListVC'); 

        $myWishlist = array('title' =>'My Wishlist',
                            'type'=>'NATIVE', 
                            'navigation'=>'MyWishListVC');

        $myOrders = array('title' =>'My Orders',
                         'type'=>'WEB', 
                         'navigation'=>$baseUrl."webcomponents/page/myorders?customerId=$customerId&langId=$langId&curId=$currencyId"); 

        $myAccountDataall = array($myOrders,$myWishlist,$myReviews,$notification,$languagecurrency,$clearHistory,$contactUs,$accountSettings,$legal,$logout);
        if ($customer_check->getId() ) {

                $myAccountData = array('list'=>$myAccountDataall); 

                $myAccount = array('componentId' =>'myAccount',
                                   'sequenceId'=>'1',  
                                   'isActive'=>'1',
                                   'myAccountUISettings'=>$myAccountUISettings,
                                   'myAccountData'=>$myAccountData);

                $component1 = array($myAccount); 

                $generalUISettings = array('mediaType' =>'1',
                                           'backgroundMediaData'=>'#ffffff',
                                           'navDividerColor'=>'#DEDEDE');

                $response = array('status' =>'OK',
                                'statusCode'=>200, 
                                'id'=>$customerId, 
                                'message'=>'Success',
                                'isUpdateUISettingFlag'=>'0',
                                'generalUISettings'=>$generalUISettings,
                                'component'=>$component1); 

                echo json_encode($response,JSON_UNESCAPED_SLASHES);
        }else {  
                    $login = array('title' =>'Login',
                                   'type'=>'NATIVE',
                                   'navigation'=>'UserManagmentVc');   
                   $myAccountDataall = array($myWishlist,$notification,$languagecurrency,$clearHistory,$contactUs,$legal,$login);
                   $myAccountData = array('list'=>$myAccountDataall);  

                    $myAccount = array('componentId' =>'myAccount',
                                       'sequenceId'=>'1',  
                                       'isActive'=>'1',
                                       'myAccountUISettings'=>$myAccountUISettings,
                                       'myAccountData'=>$myAccountData);
                    $component1 = array($myAccount);

                    $generalUISettings = array('mediaType' =>'1',
                                               'backgroundMediaData'=>'#ffffff',
                                               'navDividerColor'=>'#DEDEDE');

                    $response = array('status' =>'OK',
                                    'statusCode'=>200, 
                                    'message'=>'Success',
                                    'langId'=>$langId,
                                    'isUpdateUISettingFlag'=>'0',
                                    'generalUISettings'=>$generalUISettings,
                                    'component'=>$component1);
             
                    echo json_encode($response,JSON_UNESCAPED_SLASHES); 
        }
    }   
} 
          