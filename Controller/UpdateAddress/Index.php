<?php 
namespace Magneto\Ecommerceapi\Controller\UpdateAddress;
 
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
        public function execute()   {


        $customerId = $_POST['customerId'];  //CustomerId
        $langId = $_POST["langId"]; //languageId
        $currencyId = $_POST["curId"]; //CurrencyId
        $addressId = $_POST["addressId"];  //addressId of registered User

        $firstName = $_POST["firstname"];
        $lastName = $_POST["lastname"];
        $telephone =$_POST["telephone"];
        $city =$_POST["city"];
        $state = $_POST["regionId"];
        $country =$_POST["countryId"];
        $zipcode =$_POST["postcode"];
        $street[] =$_POST["street"];
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $addressRepository = $objectManager->get('\Magento\Customer\Api\AddressRepositoryInterface'); 
        $address = $addressRepository->getById($addressId);


        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
        $customer_check->setWebsiteId($website_id);
        $customer_check->load($customerId); 


            /*  ----Update Address With Address Id Start -------*/
            
        if ($customer_check->getId() ) {  
 
                $address->setFirstname($firstName);
                $address->setLastname($lastName);
                $address->setTelephone($telephone);
                $address->setCity($city);
                $address->setRegionId($state);
                $address->setCountryId($country);
                $address->setPostcode($zipcode);
                $address->setStreet($street);
                $addressRepository->save($address);
        
            $message =  "Address has been updated.";    

            $response = array('status' =>'OK',
                              'statusCode'=>200, 
                              'message'=>$message,
                              'id'=>$customerId/*, 
                              'langId'=>$langId*/); 
                                    
            echo json_encode($response);      

            /*  ----Update Address With Address Id End -------*/  

            /*  ----If customer is not registered-------*/
        }else {
            $message =  "Please login.";
            $response = array('status' =>'OK',
                              'statusCode'=>200,
                              'langId'=>$storeId, 
                              'message'=>$message); 
            echo json_encode($response);       
        }
    }
}