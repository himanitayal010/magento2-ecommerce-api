<?php 
namespace Magneto\Ecommerceapi\Controller\MyPolicies; 

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
    public function execute(){ 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $storeId =$storeManager->getStore()->getStoreId();
        $customerId = $_POST["customerId"];
        $currencyId = $_POST["curId"];
        $langId = $_POST["langId"]; 
        
        $generalUISettings = array('mediaType' => '1',
                                   'backgroundMediaData' => '#ffffff',
                                   'navDividerColor' => '#DEDEDE');

        $shipping = array('title' => 'Shipping & Delivery Policy',
                          'type' => 'WEB',
                          'navigation' => $baseUrl."web_component/order/shipping.html");

        $terms = array('title' => 'Terms & Condition',
                       'type' => 'WEB',
                       'navigation' => $baseUrl."web_component/order/terms-condition.html");

        $refund = array('title' => 'Refund Policy', 
                        'type' => 'WEB',
                        'navigation' => $baseUrl."web_component/order/refund.html");

        $privacy = array('title' => 'Privacy Policy',
                         'type' => 'WEB',
                         'navigation' => $baseUrl."Webcomponents/Controller/Page/mypolicy");

        $cancellation = array('title' => 'Cancellation Policy',
                              'type' => 'WEB',
                              'navigation' => $baseUrl."web_component/order/cancellation.html"); 

        $billing = array('title' => 'Billing & Payments',
                         'type' => 'WEB',
                         'navigation' => $baseUrl."web_component/order/billing.html");

        $policyDataAll = array($shipping,$terms,$refund,$privacy,$cancellation,$billing);

        $policyData = array('list' => $policyDataAll); 

        $component[]  = array('componentId' => 'policy',
                              'sequenceId' => '1',
                              'isActive' => '1',
        'policyUISettings' => array('arrowColor' => '#666666',
                                    'dividerColor' => '#dedede',
                                    'mediaType' => '1', 
                                    'backgroundMediaData' => '#ffffff',
                    'title'=> array('textColor' => '#4A4A4A',
                                    'font' => '2',
                                                      'fontWeight'=>'600',
                                    'fontSize' => '36px'), 
                    'subTitle'=>array('textColor'=>'#212121',
                                              'font'=>'2',
                                              'fontWeight'=>'600',
                                              'fontSize'=> '36px')),
                            'policyData'=> $policyData);
   
        $status = array('status' =>'OK',
                        'statusCode'=>200, 
                        'id'=>$customerId,
                        'langId'=>$storeId, 
                        'message'=>'Success',
                        'isUpdateUISettingFlag' => '0',
                        'generalUISettings' =>$generalUISettings,
                        'component'=>$component);  

        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
    } 
}