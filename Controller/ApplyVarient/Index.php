<?php 
namespace Magneto\Ecommerceapi\Controller\ApplyVarient; 

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

      $method = $_SERVER['REQUEST_METHOD'];
  		$inputs = json_decode(file_get_contents('php://input'),true);
      	foreach($inputs as $key => $value){
	      	if($key == 'productId'){
	      	  	$productId = $value;
	      	}else if($key == 'customerId'){
	      		$customerId = $value;
	      	}else if($key == 'langId'){
	      		$langId = $value;
	      	}else if($key == 'curId'){
	      		$curId = $value;
	      	}
	      	else if($key == 'varientQuery'){
				$varientQuery = $value;
	      	 	foreach($varientQuery as $varient){
		           	$attributeVarientId= $varient['attributeId'];
		          	$optionVarientId= $varient['optionId'];
		        }		
	      	}
	    }
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $currencysymbol = $objectManager->get('Magento\Directory\Model\Currency')->getCurrencySymbol(); 
      $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
      $productTypeInstance = $objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
      $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($configProduct);
      if($configProduct->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE){
           foreach($productAttributeOptions as $productAttributeOption)
            {
                $attributeId = $productAttributeOption['attribute_id'];
                $attributeConfig = $objectManager->get('\Magento\Eav\Model\Config');
                $optionValues = $attributeConfig->getAttribute('catalog_product', $attributeVarientId);
                $options = $optionValues->getSource()->getAllOptions();
               // echo "<pre>";print_r($options);
                    foreach ($options as $option)
                    {
                      //echo "<pre>";print_r($option);
                      $optionValue=$option['value'];
                    }
            }
          $_children = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
          foreach ($_children as $child){
            $childSimpleIds= $child->getID();
            //$simpleProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($childSimpleId);
          $simpleProducts = $objectManager->get('Magento\Catalog\Model\Product')
            ->getCollection()
        	->addAttributeToSelect($attributeId, $optionValue)->addAttributeToFilter('entity_id',$childSimpleIds);
        foreach  ($simpleProducts as $simpleProduct){
            $childSimpleId = $simpleProduct['entity_id'];
            $value = $simpleProduct['value'];
            if(($value == $optionVarientId)) {
            $simpleProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($childSimpleId);
            //$productURL = $simpleProduct->getProductUrl();
            $productName = $simpleProduct->getName();
            $price = $simpleProduct->getPrice();
            $discountedPrice = $simpleProduct->getSpecialPrice();
            $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
            $storeId =$storeManager->getStore()->getStoreId(); 
            $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
            $reviewFactory->getEntitySummary($simpleProduct, $storeId);
            $ratingSummary = $simpleProduct->getRatingSummary()->getRatingSummary();
            $reviewCount = $simpleProduct->getRatingSummary()->getReviewsCount();
            $ratingSummary = ($ratingSummary / 20); 
            $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
            $productImage = $simpleProduct->getImage();
            if($productImage == ""){
                $productImage = "";
            }else
            {
              $productImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$simpleProduct->getImage();
            }
            $additonalText = $price-$discountedPrice;
            if ($discountedPrice == '') {
                $discountedPrice = "";
                $additonalTextMessage = "";
            }else{
                  $discountedPrice=$discountedPrice.' '.$currencysymbol; 
                  $additonalTextMessage = "You Save" .$additonalText."".$currencysymbol."";
            }
            $varienData = array(
                                  //'productId' =>$childSimpleId,
                                  //'productURL' =>$productURL,
                                  'productImage' => $productImage,
                                  'productName' =>$productName,
                                  'price' =>$price.' '.$currencysymbol,
                                  'discountedPrice' => $discountedPrice,
                                  "rating" => "".$ratingSummary."",
                                  "additionalText" => $additonalTextMessage
                                  );
             }   }
      }
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    $baseURL= $storeManager->getStore()->getBaseUrl()."productDetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$curId&recentId=";


             if(empty($varienData)){
               $status = array('status' =>'OK',
                      'statusCode'=>300,
                      'message'=>'No data found!');
    echo $status1 = json_encode($status);
             } 

             else {
              $status = array('status' =>'OK',
                      'statusCode'=>200,
                      'message'=>'Success',
                      'query'=>"$baseURL",
                      'productDetails'=> $varienData);    
    echo $statusStatus = json_encode($status,JSON_UNESCAPED_SLASHES);
             }
    
    } 
    else
    {
    $status = array('status' =>'OK',
                      'statusCode'=>300,
                      'message'=>'No data found!');
    echo $status1 = json_encode($status);
    }
    }  
}