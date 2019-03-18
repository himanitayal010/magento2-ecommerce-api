<?php 
namespace Magneto\Ecommerceapi\Controller\ApplyPromocode;

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

        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
       // $customerId = 2;
        $customerId = $_REQUEST["customerId"];
        if($customerId){
        	$quote = $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId);
        	$couponCode = $_REQUEST["couponCode"];
        	if($couponCode){      		
        		$quote->setCouponCode($couponCode)->collectTotals()->save();
        		if($quote->getCouponCode()){
        			$response = array('status' =>'OK',
        							  'statusCode'=>200,
        							  'message'=>'Coupon Applied Successfully!');
        			
        		}else{
        			$response = array('status' =>'Error',
        							  'statusCode'=>300,
        							  'message'=>'Invalid Coupon Code!');
        		}

        			echo json_encode($response);	
        	}
        }
	}
}  