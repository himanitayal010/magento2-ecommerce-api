<?php 
namespace Magneto\Ecommerceapi\Controller\Addtowishlist;

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
    	
		$customerId = $_POST["customerId"];  
		$productId = $_POST["productId"];   
		$wishlistFlag = $_POST["flag"]; 
		$langid =$_POST["langId"];
		$currencyId =$_POST["curId"]; 

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

		$website_id = $storeManager->getStore()->getWebsiteId();

		$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');

   		$customer_check = $objectManager->get('Magento\Customer\Model\Customer');
		$customer_check->setWebsiteId($website_id);
		$customer_check->load($customerId);
		if ($customer_check->getId()) {

			$collection = $productCollection->addAttributeToSelect('*')->load(); 

				if ($wishlistFlag == 1) { //Add to wishlist
					
					foreach($collection as $product){ 	
						$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);  
						$wishList = $objectManager->get('\Magento\Wishlist\Model\WishlistFactory');
						$wishlistAdd = $wishList->create()->loadByCustomerId($customerId, true); 
						$wishlistAdd->addNewItem($productId);  
						$wishlistAdd->save();  
					}
						$message1 = 'Registered User'; 
						$message = 'Your Product added to wishlist';
						$response  = array('status' =>'OK', 
							            'statusCode'=>200,
							            'id' =>$customerId,
							            'langId'=>$langid, 
							            'message'=>$message, 
							            'message1'=>$message1); 

						echo json_encode($response,JSON_UNESCAPED_SLASHES);
				}
			if($wishlistFlag == 0) { // Remove from wishlist

				$wishlistObj = $objectManager->get('\Magento\Wishlist\Model\WishlistFactory');
				$wishlistObj = $wishlistObj->create()->loadByCustomerId($customerId, true); 
				
				$items = $wishlistObj->getItemCollection(); 
				foreach ($items as $item) { 
					if($item->getProductId() == $productId){
						$item->delete(); 
						$wishlistObj->save();
					}
				}
					$message = 'Your product removed to wishlist'; 
					$response = array('status' =>'OK', 
						              'statusCode'=>200, 
						              'id' =>$customerId,
						              'langId'=>$langid, 
						              'message'=>$message); 

					echo json_encode($response,JSON_UNESCAPED_SLASHES);
			} 
		}elseif(!$customer_check->getId()){
				$message1 = 'Guest User';
				$response = array('status' =>'OK',  
				                'statusCode'=>200,
				                'message1'=>$message1); 
				echo json_encode($response,JSON_UNESCAPED_SLASHES);
		}else{ 
					$response = array('status' =>'OK',  
				                    'statusCode'=>300,
				                    'message1'=>'No Data Found'); 
					echo json_encode($response,JSON_UNESCAPED_SLASHES);         
		}		             
	}   
}