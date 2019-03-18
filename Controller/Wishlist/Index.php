<?php 
namespace Magneto\Ecommerceapi\Controller\Wishlist;

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
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $currencysymbol = $objectManager->get('Magento\Directory\Model\Currency')->getCurrencySymbol();
        $langId = $_POST["langId"];
        $currencyId = $_POST["curId"];
        $customerId = $_POST["customerId"];
        $pageSize = $_POST["pageSize"]; 
        $page = $_POST["page"];
        $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
        $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        $storeId = $storeManager->getStore()->getStoreId();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $additionalText = array('textColor' =>'#40b75e',
                                'font'=>'1',
                                'fontSize'=>'12'); 

        $discountPrice = array('textColor' =>'#e6201a',
                               'font'=>'2',
                               'fontSize'=>'16');
 
        $price = array('textColor' =>'#666666',
                       'font'=>'2',
                       'fontSize'=>'14',
                       'scratchLineWidth'=>'1');

        $outOfStock = array('textColor' =>'#e6201a',
                            'font'=>'1',
                            'fontSize'=>'10');

        $productName = array('textColor' =>'#666666',
                             'font'=>'2',
                             'fontSize'=>'16');
        $generalUISettings  = array('mediaType' =>'1',
                                    'backgroundMediaData'=>'#f6f6f6',
                                    'isShadow'=>'1',
                                    'shadowColor'=>'#a4a4a4',
                                    'navDividerColor'=>'#DEDEDE');

        $productWishListUISettings = array('deleteButtonColor' =>'#666666',
                                            'backgroundColor'=>'#ffffff',
                                            'dividerColor'=>'#dedede',
                                            'imageIsShadow'=>'1',
                                            'imageShadowColor'=>'#a4a4a4',
                                            'selectedRating'=>'#2a8afb',
                                            'unselectedRating'=>'#f0f0f0',
                                            'productName'=>$productName,
                                            'outOfStock'=>$outOfStock,
                                            'price'=>$price, 
                                            'discountPrice'=>$discountPrice,   
                                            'additionalText'=>$additionalText); 
         
        $wishlist = $objectManager->get('\Magento\Wishlist\Model\Wishlist');
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->load($customerId); 
        if ($customer_check->getId())  
        {
        $wishlist_collection = $wishlist->loadByCustomerId($customerId, true)-> getItemCollection();
        $wishlist_collection->setPageSize($pageSize);
        $wishlist_collection->setCurPage($page);
        $total_pages = $wishlist_collection->getLastPageNumber();

        foreach ($wishlist_collection as $item) 
        {
            $wishproName =$item->getProduct()->getName();
            $wishproId =$item->getProduct()->getId();

             $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($wishproId);
          
                  if($configProduct->getTypeId() == "configurable")
                  { 
                    $productTypeInstance = $objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
                    $_children = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
                    $childPriceLowest = "";    
                    $childPriceHighest = "";       
                    foreach($_children as $child){
                      $_child = $objectManager->get('Magento\Catalog\Model\Product')->load($child->getId());
                      if($childPriceLowest == '' || $childPriceLowest > $_child->getPrice())
                        $childPriceLowest =  $_child->getPrice();

                        if($childPriceHighest == '' || $childPriceHighest < $_child->getPrice())
                        $childPriceHighest =  $_child->getPrice();
                    }         
                  } 

            $storeId =$storeManager->getStore()->getStoreId(); 
            $StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
            $quantity = $StockState->getStockQty($wishproId, $storeManager->getStore()->getWebsiteId());
            if($quantity >= '1'){
              $stockFlag = '0';
            }else {
             $stockFlag = '1'; 
            }
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($wishproId);
            $reviewFactory->getEntitySummary($product, $storeId);
            $ratingSummary = $product->getRatingSummary()->getRatingSummary();
            $reviewCount = $product->getRatingSummary()->getReviewsCount();
            $ratingSummary1 = ($ratingSummary / 20); 
            $wishproPrice =$item->getProduct()->getPrice();

            if($configProduct->getTypeId() == "configurable"){
                    $wishproPrice = $childPriceLowest;
                }else{
                    $wishproPrice =$item->getProduct()->getPrice();
                }
            $wishproImage =$store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$item->getProduct()->getImage();
            $wishprospeciaPrice =$item->getProduct()->getSpecialPrice();
            if ($wishprospeciaPrice == '') {
              $wishprospeciaPrice = ""; 
            }else{
              $wishprospeciaPrice = $wishprospeciaPrice.' '.$currencysymbol; 
            } 
            $additonalText = ''/*$wishproPrice-$wishprospeciaPrice*/;
            $wishlistData1[] = array('id' =>$wishproId,
                                    'image'=>$wishproImage,
                                    'type'=>'2',
                                    'navigationFlag'=>'1',
                                    'query'=>$baseUrl."ecommerceapi/Productdetail?productId=$wishproId&customerId=$customerId&langId=$langId&curId=$currencyId&recentId=",
                                    'title'=>$wishproName,
                                    'price'=>$wishproPrice.' '.$currencysymbol, 
                                    'discountedPrice'=>$wishprospeciaPrice,
                                    'rating'=>"".$ratingSummary1."",  
                                    'outOfStockFlag'=>$stockFlag,
                                    'additionalText'=>""); 
            $productWishListData = array('list' =>$wishlistData1);    
            
        } 
          if (empty($productWishListData)) {   
                                         $status = array('status' =>'OK',
                                                         'statusCode'=>300,
                                                         'message'=>'No data found!');
                                         echo $status1 = json_encode($status);
          }elseif($page > $total_pages){
            $status = array('status' =>'Error',
                            'statusCode'=>300,
                            'message'=>'No data found!');  

            echo json_encode($status);
          } else {
          
                $component[] = array('componentId' =>'productWishList',
                                        'sequenceId'=>'1',
                                        'isActive'=>'1',
                                        'productWishListUISettings'=>$productWishListUISettings,
                                        'productWishListData'=>$productWishListData); 
         
                $status = array('status' =>'OK',
                                'statusCode'=>200,
                                'id'=>$customerId,
                                'langId'=>$storeId,
                                'message'=>'Success',
                                'isUpdateUISettingFlag'=>'0',
                                'generalUISettings'=>$generalUISettings,
                                'component'=>$component); 
                 echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
          }
       
}else{ 
        $productId = $_POST["productId"];
        //$pro1 = json_encode($productId,JSON_FORCE_OBJECT);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection = $productCollection->addAttributeToSelect('*')
                    ->addFieldToFilter('entity_id', array('in' => $productId));       
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $total_pages = $collection->getLastPageNumber();
        foreach($collection as $product)
        {
            
            $id =$product->getId();

            $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
          
                  if($configProduct->getTypeId() == "configurable")
                  { 
                    $productTypeInstance = $objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
                    $_children = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
                    $childPriceLowest = "";    
                    $childPriceHighest = "";       
                    foreach($_children as $child){
                      $_child = $objectManager->get('Magento\Catalog\Model\Product')->load($child->getId());
                      if($childPriceLowest == '' || $childPriceLowest > $_child->getPrice())
                        $childPriceLowest =  $_child->getPrice();

                        if($childPriceHighest == '' || $childPriceHighest < $_child->getPrice())
                        $childPriceHighest =  $_child->getPrice();
                    }         
                  } 

            $storeId =$storeManager->getStore()->getStoreId(); 
            $StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
            $quantity = $StockState->getStockQty($id, $storeManager->getStore()->getWebsiteId());
            if($quantity >= '1'){
              $stockFlag = '0';
            }else {
             $stockFlag = '1';
            }
            $title = $product->getName();
            if($configProduct->getTypeId() == "configurable"){
                        $price = $childPriceLowest;
            }else{
                        $price = $product->getPrice();
            }
        
            if($price == ''){
              $price = 0; 
            }
            $offeredPrice = $product->getSpecialPrice();
            if ($offeredPrice == '') {
             $offeredPrice = ""; 
            }else{
              $offeredPrice = $offeredPrice; 
            }
            $additonalText = ""; 
            $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
        
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
            $reviewFactory->getEntitySummary($product, $storeId);
            $ratingSummary = $product->getRatingSummary()->getRatingSummary();
            $reviewCount = $product->getRatingSummary()->getReviewsCount();
            $ratingSummary1 = ($ratingSummary / 20); 
            $wishlistData1[] = array('image'=>$image,  
                                    'id'=>$id,
                                    'type'=>'2',
                                    'navigationFlag'=> '1',    
                                    'query'=>$baseUrl."ecommerceapi/Productdetail?productId=$id&customerId=$customerId&langId=$langId&curId=$currencyId&recentId=",  
                                    'title'=>$title,
                                    'price'=>$price.' '.$currencysymbol,
                                    'discountedPrice'=>$offeredPrice, 
                                    'rating'=>"".$ratingSummary1."", 
                                    'outOfStockFlag'=>$stockFlag, 
                                    'additionalText'=>$additonalText);  
            
         
        }
        
    
         /*$component[] = array('componentId' =>'productWishList',
                              'sequenceId'=>'1',
                              'isActive'=>'1',
                              'productWishListUISettings'=>$productWishListUISettings,
                              'productWishListData'=>$productWishListData);

         $status = array('status' =>'OK', 
                         'statusCode'=>200,
                         'langId'=>$storeId,
                         'message'=>'Success', 
                         'isUpdateUISettingFlag'=>'0',
                         'generalUISettings'=>$generalUISettings,
                         'component'=>$component);   
         echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);*/
         if (empty($wishlistData1)) {   
                                         $status = array('status' =>'OK',
                                                         'statusCode'=>300,
                                                         'message'=>'No data found!');
                                         echo $status1 = json_encode($status);
          }elseif($page > $total_pages){
            $status = array('status' =>'Error',
                            'statusCode'=>300,
                            'message'=>'No data found!');  

            echo json_encode($status);
          } else {
               $productWishListData = array('list' =>$wishlistData1);
                $component[] = array('componentId' =>'productWishList',
                                        'sequenceId'=>'1',
                                        'isActive'=>'1',
                                        'productWishListUISettings'=>$productWishListUISettings,
                                        'productWishListData'=>$productWishListData); 
         
                $status = array('status' =>'OK',
                                'statusCode'=>200,
                                'id'=>$customerId,
                                'langId'=>$storeId,
                                'message'=>'Success',
                                'isUpdateUISettingFlag'=>'0',
                                'generalUISettings'=>$generalUISettings,
                                'component'=>$component); 

                 echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
          }
         }   

    }  
} 
