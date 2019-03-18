<?php
namespace Magneto\Ecommerceapi\Controller\ForgotPwd;

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
    	
		$emailId = $_POST["email"];
		$langId = $_POST["langId"];
		$currencyId = $_POST["curId"];  

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();

		$userData = array("username" => "admin", "password" => "admin@123");
		$ch = curl_init($baseUrl . "rest/V1/integration/admin/token");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
		$token = curl_exec($ch);

    	$url=$baseUrl . "rest/V1/customers/password?email=".$emailId."&template=email_reset&websiteId=1";
     	$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_encode($token)));

        $result = curl_exec($ch); 

	     if($result=='true'){

	        $response='{
	        	"statusCode":200,
	            "status":"true", 
	            "message":"Password reset link has been sent to your email id"
	        }';
	        echo $response;
	     } 
	     else{
	        $response='{
	        		"statusCode":300,
	                "status":"false",
	                "message":"This email address is not registered"
	            }';
	         echo $response; 
	    }

	}  
}


















