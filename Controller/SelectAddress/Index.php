<?php 
namespace Magneto\Ecommerceapi\Controller\SelectAddress; 

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

		$customerId =$_POST["customerId"];
		$currencyId =$_POST["curId"];
		$langId = $_POST["langId"];
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$storeId =$storeManager->getStore()->getStoreId();
		$website_id = $storeManager->getStore()->getWebsiteId(); 
		$customer_check = $objectManager->get('Magento\Customer\Model\Customer');
		$customer_check->setWebsiteId($website_id);
		$customer_check->load($customerId);

		if ($customer_check->getId() ) { 


		$customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();

		$addressRepository = $objectManager->get('\Magento\Customer\Api\AddressRepositoryInterface');
		$customerRepository =    $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');
		$customer = $customerRepository->getById($customerId);
        $shippingAddressId = $customer->getDefaultShipping();  

		$customer = $customerFactory->load($customerId); 
		$email = $customer->getEmail();

		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
		$address = $customerObj->getAddresses();
		

		foreach ($address as $customerAddres) { 

			$addressId = $customerAddres['entity_id'];
			$street = $customerAddres['street'];  
			$firstName = $customerAddres['firstname']; 
			$lastName =$customerAddres['lastname'];
			$city = $customerAddres['city'];
			$country =$customerAddres['country_id'];
			$region = $customerAddres['region'];
			$postCode = $customerAddres['postcode'];
			$telephone =$customerAddres['telephone']; 
			$flat = $customerAddres['company'];
			if($flat == ''){
				$flat = "";
			} 
			if($shippingAddressId == $addressId){

					$addressd[] = array('addressId'=>$addressId,
									    'firstname'=>$firstName, 
									    'id'=>$customerId,
									    'lastName'=>$lastName,
									    'street'=>$street, 
									    'city'=>$city,
									    'country'=>$country, 
									    'region'=>$region,
									    'postCode'=>$postCode,
									    'telephone'=>$telephone,
									    'isSelected'=>'1'); 
			} else{

					$addressd[] = array('addressId'=>$addressId,
									    'firstname'=>$firstName, 
									    'id'=>$customerId,
									    'lastName'=>$lastName,
									    'street'=>$street, 
									    'city'=>$city,
									    'country'=>$country, 
									    'region'=>$region,
									    'postCode'=>$postCode,
									    'telephone'=>$telephone,
									    'isSelected'=>'0');
		}
		} 

				
				if(!empty($addressd)){
					$response = array('status' =>'OK',
									  'statusCode'=>200, 
									  'langId'=>$storeId,
									  'message'=>'Success',  
									  'component' =>$addressd);       

					echo json_encode($response,JSON_UNESCAPED_SLASHES);
				} else {
					$response = array('status' =>'Error',
									  'statusCode'=>300,
									  'message'=>'No Data Found');

					echo json_encode($response,JSON_UNESCAPED_SLASHES);
				}
				} else {
					
					$response = array('status' =>'Error',
									  'statusCode'=>300,  
									  'message'=>'You are not registered with us.');        

					echo json_encode($response,JSON_UNESCAPED_SLASHES);
				}
		
	}
}