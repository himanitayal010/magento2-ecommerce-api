<?php 
namespace Magneto\Ecommerceapi\Controller\Addaddress;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;


class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException {
        return null;
    }
    
    public function validateForCsrf(RequestInterface $request): ?bool{
        return true;
    }
    public function execute()  
    {   
        
        $customerId = $_POST['customerId']; 
        $currencyId = $_POST["curId"];
        $langId = $_POST["langId"];       
        $firstName = $_POST["firstname"];
        if(empty($firstName)){ 
            echo 'first Name is mandatory.';
        }

        $lastName = $_POST["lastname"];
        if(empty($lastName)){
            echo 'last Name is mandatory.';
        }

        $country =$_POST["countryId"];
        if(empty($country)){
            echo 'Country is mandatory.';
        }

        $zipcode =$_POST["postcode"];
        if(empty($zipcode)){
            echo 'zipcode is mandatory.';
        } 

        $city =$_POST["city"];
        if(empty($city)){
            echo 'city is mandatory.';
        }

        $telephone =$_POST["telephone"];
        if(empty($telephone)){
            echo 'Mobile Number is mandatory.';
        }

        $state = $_POST["region"];
        if(empty($state)){
            echo 'Mobile Number is mandatory.';
        }

        $street1 =$_POST["street(1)"];
        $street2 =$_POST["street(2)"];
        /*array (
            '0' => 'Sample address part1',
            '1' => 'Sample address part2',
        ),*/
       //$street2 =$_POST["street"];  
       
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        /* ---- Check Customer is registered or not ----- */

        $website_id = $storeManager->getStore()->getWebsiteId(); 
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->setWebsiteId($website_id);

        $customer_check->load($customerId); 

        /* ----Add Address start ----- */

        if ($customer_check->getId() ) {  
         $addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');
                            $address = $addresss->create();
                            $address->setCustomerId($customer_check->getId())       
                                    ->setFirstname($firstName)
                                    ->setLastname($lastName)
                                    ->setCountryId($country)
                                    ->setPostcode($zipcode)
                                    ->setCity($city)
                                    ->setRegion($state) 
                                    ->setTelephone($telephone)
                                    ->setStreet($street1.'  '.$street2)
                                    ->setSaveInAddressBook('1');
                            $address->save(); 
            $message =  "Address has been saved.";   

            $response = array('status' =>'OK',
                              'statusCode'=>200, 
                              'message'=>$message,
                              'id'=>$customerId);
                                    
            echo json_encode($response); 

             /* ----Add Address End ----- */ 

        }else {
            $message =  "Address has not been saved.";
            $response = array('status' =>'OK',
                              'statusCode'=>300,
                              'message'=>$message); 
            echo json_encode($response);  
        }
        
    } 
}  