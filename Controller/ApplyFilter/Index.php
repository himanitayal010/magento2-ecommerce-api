<?php
namespace Magneto\Ecommerceapi\Controller\ApplyFilter;
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
    $filterEncode = json_decode(file_get_contents('php://input'),true);

    /*-----------   UISETTINGS*/
    $filterTitle = array('textColor' =>'#666666',
                             'font'=>'2',
                             'fontSize'=>'15',
                             'filterIconColor'=>'#666666');
       
 
    $title = array('textColor' =>'#666666',
                       'font'=>'2', 
                       'fontSize'=>'15'); 

    $sortText = array('Title'=>$title,
                          'sortSelectionDotColor'=>'#008BFF',
                          'dividerColor'=>'#e6e6e6'/*,
                          'sortTitleData'=>$sortall*/); 

    $sortTitle = array('textColor' =>'#666666',
                           'font'=>'2',
                           'fontSize'=>'15',
                           'sortIconColor'=>'#666666');

    $screenUISettings = array('position' =>'1',
                                  'sortAndFilterbgColor'=>'#ffffff',
                                  'sortTitle'=>$sortTitle,
                                  'sortText'=>$sortText,
                                  'filterTitle'=>$filterTitle,
                                  'dividerColor'=>'#e6e6e6'); 
    $discountPrice = array('textColor' =>'#e6201a',
                               'font'=>'2',
                               'fontSize'=>'14');

    $productPrice = array('textColor' =>'#666666',
                              'font'=>'2',
                              'fontSize'=>'14');

    $productName = array('textColor' =>'#212121',
                             'font'=>'1',
                             'fontSize'=>'16');

    $componentTitle = array('textColor' =>'#666666',
                                 'font'=>'2',
                                 'fontSize'=>'15'); 
  
    $productListUISettings = array('isShadow' =>'1',
                                       'mediaType'=>'1', 
                                       'likeCircleImageColor'=>'#f6f6f6',
                                       'backgroundMediaData'=>'#ffffff',
                                       'selectedRating'=>'#2a8afb',
                                       'unselectedRating'=>'#f0f0f0',
                                       'imageViewBackgroundColor'=>'#ffffff',
                                       'likeColor'=>'#2a8afb',
                                       'disLikeColor'=>'#666666',
                                       'scratchLineWidth'=>'1',
                                       'title'=>$componentTitle,
                                       'productName'=>$productName,
                                       'price'=>$productPrice,
                                       'discountPrice'=>$discountPrice); 
/*UI SETTINGS*/

    $langId = $filterEncode['langId'];
    $storeId = $langId;
    $customerId = $filterEncode['customerId'];
    $curId = $filterEncode['curId'];
    $sortId = $filterEncode['sortId'];
    $pageSize = $filterEncode['pageSize'];
    $page = $filterEncode['page'];
    $categoryId = $filterEncode['categoryId'];
    $clearFlag = $filterEncode['clearFlag'];
    $filterQuery = $filterEncode['filterQuery'];
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    $storeId =$storeManager->getStore()->getStoreId();
    $category = $objectManager->create('Magento\Catalog\Model\Category')->load($categoryId)->setStore($storeId);
    $currencysymbol = $objectManager->get('Magento\Directory\Model\Currency')->getCurrencySymbol();
    $baseUrl= $storeManager->getStore()->getBaseUrl();
   	$catCount =$category->getProductCount();
    $catName=$category->getName();
    $catCount =$category->getProductCount();
    //$proCount = $catCount / $pageSize;
    //$proCount  = ceil($proCount); 
    if($clearFlag == "1"){  
            
      $productCollectionData = $objectManager->create('Magento\Catalog\Model\Product')->getCollection();
      $productCollectionData->addCategoryFilter($category);
    }
    $productCollectionData = $objectManager->create('Magento\Catalog\Model\Product')->getCollection();
    $productCollectionData->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
    $productCollectionData->addCategoryFilter($category);
        /* Sorting Start */
    if($sortId == "1"){
          $productCollectionData = $objectManager->create('Magento\Catalog\Model\Product')->getCollection();
          $productCollectionData->addCategoryFilter($category);   
      } 
      if($sortId == "2"){  
          $productCollectionData->addAttributeToSort('entity_id','desc');   
      }   
      if($sortId == "3"){  
          $productCollectionData = $productCollectionData->addAttributeToFilter('special_price', array('neq' => ''));
      }if($sortId == "4"){   
          $productCollectionData = $productCollectionData->addAttributeToSort('price','ASC');
      }if($sortId == "5"){  
          $productCollectionData = $productCollectionData->addAttributeToSort('price','desc');
      }/* Sorting End */   

      foreach($filterQuery as $filter){
      $attributeFilterId = $filter['filterId'];
      $optionFilterId = $filter['categoryId'];
      if($attributeFilterId == $categoryId ){
          $productCollectionData->addCategoriesFilter(array('eq' => $optionFilterId));
      }else{
          $optionArray = explode('-', $optionFilterId);
          $fromPrice = $toPrice = 0;
          if(count($optionArray) > 1){ // Price Range

              if($optionArray[0] == ''){ // -1000
                      $fromPrice = 0;
                      $toPrice = $optionArray[1];
              }else if($optionArray[1] == ''){ // 2000-
                      $fromPrice = $optionArray[0];
                      $toPrice = 999999999999999999;
              }else{ // 1000-2000
                      $fromPrice = $optionArray[0];
                      $toPrice = $optionArray[1];
              }  
              $productCollectionData->addAttributeToFilter('price', ['from' => $fromPrice, 'to' => $toPrice]);
              }else{  
                $optionArray = explode(',', $optionFilterId);
                $attributeId = $attributeFilterId;
                $eavModel = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
                $attribute = $eavModel->load($attributeId);
                $attributeFilterCode=$eavModel->getAttributeCode();
                $productCollectionData->addAttributeToFilter($attributeFilterCode,$optionArray);   
              }
            }  
        } 
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->load($customerId); 
        $wishlistItems = array();
        $cartItems = array();
        if ($customer_check->getId()){
            $wishlist = $objectManager->get('\Magento\Wishlist\Model\Wishlist');
            $wishlist_collection = $wishlist->loadByCustomerId($customerId, true)->getItemCollection();
            foreach($wishlist_collection as $newItem){
                  $wishlistItems[] = $newItem->getProductId();
            }
            $quote= $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId);   
            $quoteItems=$quote->getAllVisibleItems();
            foreach($quoteItems as $oneItem){
                 $cartItems[] = $oneItem->getProductId();
            }
          } 
        $productCollectionData->setPageSize($pageSize)->setCurPage($page); 
        $totalPages=$productCollectionData->getLastPageNumber();
        foreach($productCollectionData as $product)
        {
          $productId= $product['entity_id']; 
          $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
          
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
          $product =$product->load($productId);
          $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
          $name = $product->getName();
          $url =  $product->getProductUrl();
          $id = $product->getEntityId();
          if($configProduct->getTypeId() == "configurable")
            {
              $price = $childPriceLowest;
          }else{
              $price = $product->getPrice();
          }  
          $price = $price.' '.$currencysymbol;
          $specialPrice = $product->getSpecialPrice();
          if($specialPrice == null){
                $specialPrice = "";
          }else{
                $specialPrice = $specialPrice.' '.$currencysymbol;
          }
          $likeFlag = '0';
            if(in_array($productId, $wishlistItems)){
                      $likeFlag = '1';
            }
            $cartFlag = '0';
            if(in_array($productId, $cartItems)){
                      $cartFlag = '1';
            }
            $reviewFactory = $objectManager->create('Magento\Review\Model\Review');     
            $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
            $storeId = $storeManager->getStore()->getStoreId();
            $reviewFactory->getEntitySummary($product, $storeId);
            $ratingSummary = $product->getRatingSummary()->getRatingSummary();
            $reviewCount = $product->getRatingSummary()->getReviewsCount();
            $ratingSummary = ($ratingSummary / 20); 
            $productData[] = array( 
                              'id'=>$id,
                              'image'=>$image,
                              'type'=>'2',
                              'navigationFlag'=>'1',
                              'query'=>$baseUrl."ecommerceapi/productdetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$curId", 
                              'title'=>$name,
                              //'$url'=>$url,
                              'price'=>$price,
                              'discountedPrice'=>$specialPrice,
                              'rating'=>"".$ratingSummary."",  
                              'likeFlag'=>$likeFlag
                             ); 
            
        }
       $productCount=count($productCollectionData);

       if($page > $totalPages){
         
            $response = array('status' =>'Error','statusCode'=>300,'message'=>'No data found!');    
            echo json_encode($response);
        }elseif(empty($productData))
          {
            $status = array('status' =>'Error',
                            'statusCode'=>300, 
                            'message'=>'No Data Found!');                          
            echo $statusErrorMessage = json_encode($status); 
          }
      else
      {
        $productListData = array('title' =>'ProductList','list'=>$productData);
        $componentProduct = array('componentId' =>'productList',
                                  'sequenceId'=>'2',
                                  'isActive'=>'1',
                                  'categoryId'=>''.$categoryId.'',
                                  'productListUISettings'=>$productListUISettings,
                                  'productListData'=>$productListData);
        //Banner Images start

        $connectionUrl  = $objectManager->get('Mageplaza\BannerSlider\Model\ResourceModel\Data\Collection');
        foreach ($connectionUrl as $connection) 
        {
          $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
		      $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
		      $currentStore = $storeManager->getStore();
		      $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
          $id = $connection->getBannerId(); 
          $image = $connection->getImage(); 
          $bannerall[] = array('id' =>$id ,
                               'image'=>$mediaUrl."mageplaza/bannerslider/banner/image/".$image,
                               'type'=>'1',
                               'navigationFlag'=>'1',
                               'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$categoryId&customerId=$customerId&langId=$langId&curId=$curId");
        }

        $bannerAllData = array('list' =>$bannerall);
        $singleImageUISettings = array('isShadow' =>'1',
                                       'mediaType'=>'1',
                                       'backgroundMediaData'=>'#ffffff',
                                       'imageHeight'=>'900',
                                       'imageWidth'=>'1650'); 

        $componentBanner = array('componentId'=>'singleImage',
                                 'sequenceId'=>'3',
                                 'isActive'=>'1',
                                 'singleImageUISettings'=>$singleImageUISettings,
                                 'singleImageData'=>$bannerAllData); 
        ////Banner Images End
        $component = array($componentProduct,$componentBanner);
        $status = array('status' =>'OK',
                        'statusCode'=>200,
                        'isUpdateUISettingFlag'=>'0',
                        'message'=>'Success',
                        'id'=>$customerId,
                        'langId'=>$langId,
                        'titleNavigationBar'=>$catName, 
                       // 'noOfItems'=>"".$catCount."", 
                        'noOfItems'=>"".$productCount."", 
                        'repeatAfter'=>'4',
                        'changeBanner'=>'1',
                        'generalUISettings'=>$screenUISettings,
                        'component'=>$component);   
        echo $statusSuccessMessage = json_encode($status,JSON_UNESCAPED_SLASHES);   
      }
    }
}