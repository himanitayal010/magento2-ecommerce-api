<?php 
namespace Magneto\Ecommerceapi\Controller\Addtocart; 

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

        /*$response = array();

        $productId = $_POST["productId"]; 
        $cartFlag = $_POST["cartFlag"];  
        $langId = $_POST["langId"];  
        $curId = $_POST["curId"]; 
        $installationId = $_POST["installationId"];
        $warrantyId = $_POST["warrantyId"]; 
        $is_warranty = 1;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 

        $customerId = $_POST["customerId"];*/  
        

        /*if($warrantyId == 1) {
            $one_year_warranty = $product->getData('extend_warranty_coverage_for_1');

        } elseif ($warrantyId == 2) {
            $two_year_warranty = $product->getData('extend_warranty_coverage_for_2');

        } elseif ($warrantyId == 3) {
            $three_year_warranty = $product->getData('extend_warranty_coverage_for_3');

        } else {
            $price = $product->getPrice();
        }


        if($installationId == 1) {
            $installation_fee = $product->getData('installation'); 

        } else {
           
        }*/
        /*$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('magneto_warranty'); //gives table name with prefix
         
        $sql = "Insert Into " . $tableName . " (quote_item_id,is_warranty,one_year_warranty,two_year_warranty,three_year_warranty,installation_fee) Values (quoteId,$is_warranty,$one_year_warranty,$two_year_warranty,$three_year_warranty,$installation_fee)";
        $connection->query($sql);
          */
        

        /*$installationFee = $product->getData('installation');
        $quoteFactory = $objectManager->create('\Magento\Quote\Model\QuoteFactory');
        $quote = $objectManager->create('\Magento\Quote\Model\Quote');

        $quotes = $quoteFactory->create()->getCollection()
                  ->addFieldToFilter('customer_id',$customerId)
                  ->addFieldToFilter('is_active',1);

        if ($cartFlag == 1){ // Add Product to Cart
            if($quotes->getSize()){ // Old Customer 
                $quoteData = $quotes->getData();
                if(isset($quoteData[0]['entity_id'])){
                    $quoteId = $quoteData[0]['entity_id'];
                    $quote = $quote->load($quoteId);
                    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); 
                    if($product->getId()){
                        $quote->addProduct($product);
                        $quote->collectTotals();
                        $quote->save();
                        $response = array('status' =>'OK','statusCode'=>200, 'message'=>'Product Added To cart.');
                    }else{
                        $response = array('status' =>'ERROR','statusCode'=>300, 'message'=>'Product not found in the catalog');
                    }
                }
            }else{ // New Customer 
                $customer = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface')->getById($customerId);
                $quote->assignCustomer($customer);
                $quote->setIsActive(1);            
                $quote->setStoreId($storeManager->getStore()->getId());
                $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
                if($product->getId()){
                    $quote->addProduct($product);
                    $quote->collectTotals();
                    $quote->save();
                    $response = array('status' =>'OK','statusCode'=>200, 'message'=>'Product Added To cart.');
                }else{
                    $response = array('status' =>'ERROR','statusCode'=>300, 'message'=>'Product not found in the catalog');
                }
            } 
        }

        if($cartFlag == 0){ // Remove Product from Cart
            $productExist = false;
            if($quotes->getSize()){
                $quoteData = $quotes->getData();
                if(isset($quoteData[0]['entity_id'])){
                    $quoteId = $quoteData[0]['entity_id'];
                    $quote = $quote->load($quoteId);
                    $items = $quote->getAllVisibleItems(); 
                    foreach ($items as $item) {
                        if($item['product_id'] == $productId){
                            $productExist = true;
                            $item->delete(); 
                            $quote->collectTotals();
                            $quote->save();
                            $response = array('status' =>'OK','statusCode'=>200, 'message'=>'Product removed from cart.');
                        }
                    }
                }
            } 
            if(!$productExist){
                $response = array('status' =>'ERROR','statusCode'=>300, 'message'=>'Product not found in the cart');    
            }
        }
         
        echo json_encode($response,JSON_UNESCAPED_SLASHES); */

        $method = $_SERVER['REQUEST_METHOD'];
        $inputs = json_decode(file_get_contents('php://input'),true);
        foreach($inputs as $key => $value){
            if($key == 'customerId'){
                    $customerId = $value;
            
            }else if($key == 'langId'){
                    $langId = $value;
              
            }else if($key == 'curId'){
                    $curId = $value;
               
            }
            else if($key == 'productQuery'){
                $productQuery = $value;
                foreach($productQuery as $product){
                    $productId= $product['productId'];
                    $installationId= $product['installationId'];
                    $warrantyId= $product['warrantyId'];
                    $cartFlag= $product['cartFlag'];

                    $productCollection = array('productId' =>$productId,
                                               'installationId'=>$installationId,
                                               'warrantyId'=>$warrantyId,
                                               'cartFlag'=>$cartFlag);
                 
                }  

            }
        }


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
        $quoteFactory = $objectManager->create('\Magento\Quote\Model\QuoteFactory');
        $quote = $objectManager->create('\Magento\Quote\Model\Quote');

        $quotes = $quoteFactory->create()->getCollection()
                  ->addFieldToFilter('customer_id',$customerId)
                  ->addFieldToFilter('is_active',1);

        if ($cartFlag == 1){ // Add Product to Cart
            if($quotes->getSize()){ // Old Customer 
                $quoteData = $quotes->getData();
                if(isset($quoteData[0]['entity_id'])){
                    $quoteId = $quoteData[0]['entity_id'];
                    $quote = $quote->load($quoteId);
                    foreach ($productQuery as $product) {
                    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productCollection); 
                    if($product->getId()){
                        $quote->addProduct($product); 
                        $quote->collectTotals();
                        $quote->save();
                        $response = array('status' =>'OK','statusCode'=>200, 'message'=>'Product Added To cart.');
                    }else{
                        $response = array('status' =>'ERROR','statusCode'=>300, 'message'=>'Product not found in the catalog');
                    }
                    }
                }
            }else{ // New Customer 
                $customer = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface')->getById($customerId);
                $quote->assignCustomer($customer);
                $quote->setIsActive(1);            
                $quote->setStoreId($storeManager->getStore()->getId());
                $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
                foreach ($productQuery as $product) {
                    if($product->getId()){
                        $quote->addProduct($product);
                        $quote->collectTotals();
                        $quote->save();
                        $response = array('status' =>'OK','statusCode'=>200, 'message'=>'Product Added To cart.');
                    }else{
                        $response = array('status' =>'ERROR','statusCode'=>300, 'message'=>'Product not found in the catalog');
                    }
                }
            } 
        }

        if($cartFlag == 0){ // Remove Product from Cart
            $productExist = false;
            if($quotes->getSize()){
                $quoteData = $quotes->getData();
                if(isset($quoteData[0]['entity_id'])){
                    $quoteId = $quoteData[0]['entity_id'];
                    $quote = $quote->load($quoteId);
                    $items = $quote->getAllVisibleItems(); 
                    foreach ($items as $item) {
                        if($item['product_id'] == $productId){
                            $productExist = true;
                            $item->delete(); 
                            $quote->collectTotals();
                            $quote->save();
                            $response = array('status' =>'OK','statusCode'=>200, 'message'=>'Product removed from cart.');
                        }
                    }
                }
            } 
            if(!$productExist){
                $response = array('status' =>'ERROR','statusCode'=>300, 'message'=>'Product not found in the cart');    
            }
        }
         
        echo json_encode($response,JSON_UNESCAPED_SLASHES);
    } 
}    
