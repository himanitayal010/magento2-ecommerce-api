<?php 
namespace Magneto\Ecommerceapi\Controller\ProductDetail;  /*235*/

class Index extends \Magento\Framework\App\Action\Action 
{
public function execute() 
{ 
$customerId = $_REQUEST["customerId"];
$productId  =$_REQUEST["productId"];  
$curId = $_REQUEST["curId"];
$langId = $_REQUEST["langId"]; 
$recentProductId  = $_REQUEST["recentId"];
$recentProductId = explode(',', $recentProductId);



$generalUISettings =  array('mediaType' =>'1',
  'backgroundMediaData'=>'#f6f6f6',
  'navDividerColor'=>'#DEDEDE',
  'buttonAddToCart'=> array('textColor' =>'#666666',
    'font'=>'2',
    'fontSize'=>'16',
    'backgroundColor'=>'#ffffff',
    'borderColor'=>'#DEDEDE'),
  'buttonBuyNow'=> array('textColor' =>'#ffffff',
    'font'=>'2',
    'fontSize'=>'16',
    'backgroundColor'=>'#3db75e',
    'borderColor'=>'#ffffff'));

$productImageUISettings = array('isShadow' =>'1',
  'backgroundColor'=>'#f6f6f6',
  'imageWidth'=>'500',
  'imageHeight'=>'500',
  'likeColor'=>'#2a8afb',
  'disLikeColor'=>'#666666',
  'activeImageDotColor'=>'#008BFF',
  'inactiveImageDotColor'=>'#DEDEDE',
  'similarProductImageColor'=>'#666666',
  'likeProductImageColor'=>'#008BFF',
  'likeProductCircleImageColor'=>'#f6f6f6',
  'dislikeProductImageColor'=>'#666666',
  'similarProductCircleImageColor'=>'#f6f6f6');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
if($product->getId()){

  /*----------------------------Custom Option component start -------------------------- */
  $id= $productId;
  $customComponent = array();
  $custom  = array();
  $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
  $product = $_objectManager->get('\Magento\Catalog\Model\Product')->load($id);
  $customOptions = $_objectManager->get('Magento\Catalog\Model\Product\Option')->getProductOptionCollection($product);
  if(count($customOptions) != 0){
    foreach($customOptions as $optionKey => $optionVal):
     $defaultTitle = $optionVal->getDefaultTitle();
     $fileType = $optionVal->getType();
     $files = $optionVal->getFileExtension();
     $custom   = array('printServices'=>$defaultTitle,
       'fileType'=>$fileType,
       'Compatible file extensions to upload'=>$files);
   endforeach;


   $customComponent   = array('componentId' =>'customComponent',
     'sequenceId'=>'12',
     'isActive'=>'1',
     'componentData'=>$custom);
 }  
 /*----------------------------Custom Option component End -------------------------- */



 $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
 $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
 $storeId = $storeManager->getStore()->getStoreId();

 $repository = $objectManager->create('Magento\Catalog\Model\ProductRepository');

 $currencysymbol = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
 $currency = $currencysymbol->getStore()->getCurrentCurrencyCode();

 $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
 $store = $storeManager->getStore();

 $baseUrl= $storeManager->getStore()->getBaseUrl();

 $galleryReadHandler = $objectManager->get('Magento\Catalog\Model\Product\Gallery\ReadHandler');
 $galleryReadHandler->execute($product);
 $images = $product->getMediaGalleryImages(); 
 $imageHelper = $objectManager->get('\Magento\Catalog\Helper\Image');
 $width = 150; 
 $height = 150;
 $imageUrl = $imageHelper->init($product,'product_page_image_small')->setImageFile($product->getFile())->resize($width,$height)->getUrl();

 $shortDescription = $product->getShortDescription();
 $productInfo = strip_tags($shortDescription);
 

 if($productInfo == ''){
  $productInfo = "";
}
if($images == ''){
  $images = ""; 
}

$customer_check = $objectManager->get('Magento\Customer\Model\Customer');
$customer_check->load($customerId); 

$wishlistItems = array();
$cartItems = array();
$orderItems[] = array();

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

 $orderDatamodel = $objectManager->get('Magento\Sales\Model\Order')->getCollection();
 foreach($orderDatamodel as $orderDatamodel1){
  $orderItems[] = $orderDatamodel1->getProductId();

}
}

/*---------------similar Product component start-------------------------*/

$productCollectionFactory = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
$categories = $product->getCategoryIds();
$relatedProducts = $product->getRelatedProducts();
$countRelated = count($relatedProducts); 
$getProductCount = 8 - $countRelated;
$productModelObj = '';
if(!count($relatedProducts)){
$productModelObj = $objectManager->create('Magento\Catalog\Model\Product')
->getCollection()
->addFieldToSelect('*')
->addCategoriesFilter(['in' => $categories])
->addFieldToFilter('status', array('eq'=>'1'))
->addFieldToFilter('visibility', array('eq'=>'4'))
->setPageSize('8');

} 
if(count($productModelObj)== 0) {
$isSimilerProduct ='0';
} else {
$isSimilerProduct ='1';
}
if($imageUrl == $baseUrl."pub/media/catalog/product/placeholder/default/placeholder_3.jpg"){
$productImages1 = $baseUrl."/pub/media/catalog/product/placeholder/default/images_3.png";
$productImage = array('image' =>$productImages1);
$productImageData  = array('isSimilerProduct'=>$isSimilerProduct,
'id'=>''.$productId.'',
'list'=>$productImage);
} 

if($images != ''){
if($isSimilerProduct == '1') {
foreach($images as $image){
$temp =  $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$image->getFile();
$likeFlag = '0';
if(in_array($productId, $wishlistItems)){
  $likeFlag = '1';
}
$productImages[] = array('image'=>$temp); 

$productImageData = array('likeFlag' =>$likeFlag,
  'isSimilerProduct'=>$isSimilerProduct,
  'id'=>''.$productId.'',
  'list'=>$productImages ); 

}

foreach ($productModelObj as $similar) {
$id = $similar->getEntityId(); 
                    /*$configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
                    if($configProduct->getTypeId() == "configurable"){ 
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
                      }*/

                      $likeFlag = '0';
                      if(in_array($id, $wishlistItems)){
                        $likeFlag = '1';
                      }  

                      $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$similar->getImage();

                      $title = $similar->getName();
                      if($title == ''){
                        $title = "";
                      }

                   /* if($configProduct->getTypeId() == "configurable"){
                        $price = $childPriceLowest;
                      }else{*/
                        $price = $similar->getPrice();
                        /* }*/

                        if($price == ''){
                          $price == "0";
                        }

                        $discountPrice = $similar->getSpecialPrice();
                        if($discountPrice == ''){
                          $discountPrice = "";
                        }else{  
                          $discountPrice = $discountPrice.' '.$currency;
                        }
                        $ratingSummary1 = '4';
                        $similarPro[] = array('id' =>$id,
                          'image'=>$image,
                          'title'=>$title,
                          'price'=>$price.' '.$currency,
                          'discountPrice'=>$discountPrice,
                          'type'=>'2',
                          'rating'=>$ratingSummary1,
                          'navigationFlag'=>'1',
                          'likeFlag'=>$likeFlag,
                          'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$id&customerId=$customerId&langId=$langId&curId=$curId&recentId="); 

                      }

                      $similarProductsData = array('title' =>'SIMILAR PRODUCTS',
                       'list'=>$similarPro);

                      $similarProductsUISettings = array('isShadow' =>'1',
                       'mediaType'=>'1',
                       'backgroundMediaData'=>'#ffffff',
                       'selectedRating'=>'#2a8afb',
                       'unselectedRating'=>'#f0f0f0',
                       'imageViewBackgroundColor'=>'#ffffff',
                       'likeColor'=>'#2a8afb',
                       'disLikeColor'=>'#666666',
                       'scratchLineWidth'=>'1',
                       'likeCircleImageColor'=>'#f6f6f6',
                       'addToCartIconColor'=>'#2a8afb',
                       'addToCartCircleColor'=>'#ffffff',
                       'title' => array('textColor' =>'#212121',
                         'font'=>'2', 
                         'fontSize'=>'16'),
                       'productName' => array('textColor' =>'#212121',
                         'font'=>'1',
                         'fontSize'=>'16'), 
                       'price'  => array('textColor' =>'#666666',
                         'font'=>'2',
                         'fontSize'=>'14'),
                       'discountPrice' => array('textColor' =>'#e6201a',
                         'font'=>'2',
                         'fontSize'=>'14'));

                      $similarProducts = array('isActive' =>'1',
                       'componentId'=>'similarProducts',
                       'similarProductsUISettings'=>$similarProductsUISettings,
                       'similarProductsData'=>$similarProductsData); 

                      $componentFirst = array('componentId' =>'productImage', 
                                              'sequenceId'=>'1',
                                              'isActive'=>'1',
                                              'productImageUISettings'=>$productImageUISettings,
                                              'productImageData'=>$productImageData,
                                              'similarProducts'=>$similarProducts);
                    } else {
                      if($imageUrl == $baseUrl."/pub/media/catalog/product/placeholder/default/placeholder_3.jpg"){
                        $productImages1 = $baseUrl."/pub/media/catalog/product/placeholder/default/images_3.png";

                        $productImage = array('image' =>$productImages1);
                        $productImageData  = array('isSimilerProduct'=>$isSimilerProduct,
                          'id'=>''.$productId.'',
                          'list'=>$productImage);
                      } 
                      foreach($images as $image){
                        $temp =  $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$image->getFile();

                        $likeFlag = '0';
                        if(in_array($productId, $wishlistItems)){
                          $likeFlag = '1';
                        }

                        $productImages[] = array('image'=>$temp); 
                        if($productImages == ''){
                          $productImages = "";
                        }

                        $productImageData = array('likeFlag' =>$likeFlag,
                          'isSimilerProduct'=>$isSimilerProduct,
                          'id'=>''.$productId.'',
                          'list'=>$productImages ); 

                      }

                      $componentFirst = array('componentId' =>'productImage', 
                        'sequenceId'=>'1',
                        'isActive'=>'1',
                        'productImageUISettings'=>$productImageUISettings,
                        'productImageData'=>$productImageData); 

                    }

                  }else{
                    $productImages = $baseUrl."/pub/media/catalog/product/placeholder/default/images_3.png";
                    $productImageData = array('likeFlag' =>$likeFlag,
                      'isSimilerProduct'=>$isSimilerProduct,
                      'id'=>''.$productId.'',
                      'list'=>$productImages );

                    $componentFirst = array('componentId' =>'productImage', 
                                            'sequenceId'=>'2',
                                            'isActive'=>'1',
                                            'productImageUISettings'=>$productImageUISettings,
                                            'productImageData'=>$productImageData);
                  }    



                  /*----------------------Similar Product and Image Componrnt End-------------------------------*/



                  /*-----------------------Similar Product Component 2 Start------------------------*/

                  foreach ($productModelObj as $similar) {
                    $id = $similar->getEntityId(); 
                    /*$configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
                    if($configProduct->getTypeId() == "configurable"){ 
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
                      }*/

                      $likeFlag = '0';
                      if(in_array($id, $wishlistItems)){
                        $likeFlag = '1';
                      }  

                      $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$similar->getImage();

                      $title = $similar->getName();
                      if($title == ''){
                        $title = "";
                      }

                   /* if($configProduct->getTypeId() == "configurable"){
                        $price = $childPriceLowest;
                      }else{*/
                        $price = $similar->getPrice();
                        /* }*/

                        if($price == ''){
                          $price == "0";
                        }

                        $discountPrice = $similar->getSpecialPrice();
                        if($discountPrice == ''){
                          $discountPrice = "";
                        }else{  
                          $discountPrice = $discountPrice.' '.$currency;
                        }

                        $ratingSummary1 = '4';

                        $similarPro[] = array('id' =>$id,
                                              'image'=>$image,
                                              'title'=>$title,
                                              'price'=>$price.' '.$currency,
                                              'discountPrice'=>$discountPrice,
                                              'type'=>'2',
                                              'rating'=>$ratingSummary1,
                                              'navigationFlag'=>'1',
                                              'likeFlag'=>$likeFlag,
                                              'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$id&customerId=$customerId&langId=$langId&curId=$curId&recentId="); 

                      } 

                      $similarProductsData = array('title' =>'SIMILAR PRODUCTS','list'=>$similarPro);

                      $similarProductsUISettings = array('isShadow' =>'1',
                                                         'mediaType'=>'1',
                                                         'backgroundMediaData'=>'#ffffff',
                                                         'selectedRating'=>'#2a8afb',
                                                         'unselectedRating'=>'#f0f0f0',
                                                         'imageViewBackgroundColor'=>'#ffffff',
                                                         'likeColor'=>'#2a8afb',
                                                         'disLikeColor'=>'#666666',
                                                         'scratchLineWidth'=>'1',
                                                         'likeCircleImageColor'=>'#f6f6f6',
                                                         'addToCartIconColor'=>'#2a8afb',
                                                         'addToCartCircleColor'=>'#ffffff',
                                        'title' => array('textColor' =>'#212121',
                                                         'font'=>'2', 
                                                         'fontSize'=>'16'),

                       'productName' => array('textColor' =>'#212121',
                                              'font'=>'1',
                                              'fontSize'=>'16'), 

                       'price'  => array('textColor' =>'#666666',
                                         'font'=>'2',
                                         'fontSize'=>'14'),

                       'discountPrice' => array('textColor' =>'#e6201a',
                                                 'font'=>'2',
                                                 'fontSize'=>'14'));

                      $similarProducts1 = array('componentId'=>'similarProducts',
                                                'isActive' =>'1',
                                                'sequenceId'=>'3',
                                                'similarProductsUISettings'=>$similarProductsUISettings,
                                                'similarProductsData'=>$similarProductsData); 


                      /*-----------------------Similar Product Component 2 End------------------------*/

                      /*----------------------attributes component start-------------------------------*/

                      $attributesUISettings = array('backgroundColor' =>'#ffffff',
                        'isShadow'=>'1',
                        'multiAttributeFlag'=>'0', 
                        'title'=>array('textColor' =>'#212121',
                          'font'=>'2', 
                          'fontSize'=>'16'),
                        'selectedOptionText'=>array('textColor' =>'#298afc',
                          'font'=>'2',
                          'fontSize'=>'14'),
                        'selectedOption'=>array('textColor' =>'#298afc',
                          'font'=>'2',
                          'fontSize'=>'14',
                          'borderColor'=>'#298afc'),
                        'unselectedOption'=>array('textColor' =>'#dedede',
                          'font'=>'2',
                          'fontSize'=>'14',
                          'borderColor'=>'#212121'));


                      if($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE){

                        $data = $product->getTypeInstance()->getConfigurableOptions($product);
                        $options = array();

                        foreach($data as $attr){ 
                          foreach($attr as $p){
                            $options[$p['sku']][$p['attribute_code']] = $p['option_title'];
                          }
                        }

                        $attributeOption = array();
                        $attributesData = array();

                        foreach($options as $sku =>$d){

                          $pr = $repository->get($sku);
                          $productId = $objectManager->get('Magento\Catalog\Model\Product')->getIdBySku($sku);

                          foreach($d as $label => $value){
                            $label = $label;
                            $vaule = $value;

                            $attributeOption[]  = array('value' =>$value,
                                                        'defaultSelected'=>'0',
                                                        'image'=>'',
                                                        'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",
                                                        'type'=>'2',
                                                        'navigationFlag'=>'1');
                          }
                        } 


                        $attributesData[]   = array('title' =>$label,
                                                    'type'=>'SS',
                                                    'options'=>$attributeOption);

                        $attributesDatamerge = array('list' =>$attributesData); 

                        $attributes = array('componentId' =>'attributes', 
                                            'sequenceId'=>'4',
                                            'isActive'=>'1',
                                            'attributesUISettings'=>$attributesUISettings,
                                            'attributesData'=>$attributesDatamerge);

                      } else {
                        $attributes = array();
                      }


                      /*----------------attributes component end---------------------------------------*/



                      /*----------------Product Deatil Component Start-----------------*/


                      $productDetailsUISettings = array('backgroundColor' =>'#ffffff',
                        'selectedRating'=>'#2a8afb',
                        'unselectedRating'=>'#f0f0f0',
                        'scratchLineWidth'=>'1',
                        'isShadow'=>'1',
                        'titleText' => array('textColor' =>'#212121',
                          'font'=>'2',
                          'fontSize'=>'16'),
                        'subTitleText' => array('textColor' =>'#666666',
                          'font'=>'2',
                          'fontSize'=>'14'),
                        'price' => array('textColor' =>'#666666',
                          'font'=>'2',
                          'fontSize'=>'14'),
                        'discountPrice' => array('textColor' =>'#e6201a',
                          'font'=>'2',
                          'fontSize'=>'14'),
                        'additionalText' => array('textColor' =>'#3db75e',
                          'font'=>'2',
                          'fontSize'=>'14'));

                      $productName = $product->getName();
                      $sku = 'SKU#'.$product->getSku().'';
                      $price = $product->getPrice();
                      $offeredPrice = $product->getSpecialPrice();

                      if($offeredPrice == ''){
                       $offeredPrice = "";
                     } else {
                       $offeredPrice = $offeredPrice;
                     }

                     $cartFlag = '0';
                     if(in_array($productId, $cartItems)){
                      $cartFlag = '1';
                    }

                    $additional = ""/*$price - $offeredPrice*/;
                    $additional1 = 'You Save'.$additional.' '.$currency;

                    $ratingSummary1= '4';

                    $productDetailsData = array('productName' =>$productName,
                      'productId'=>''.$productId.'',  
                      'subTitleText'=>$sku,
                      'cartFlag'=>$cartFlag,
                      'price'=>$price.' '.$currency,
                      'discountedPrice'=>$offeredPrice,
                      'rating'=>$ratingSummary1,
                      'additionalText'=>$additional);

                    $productdetailcomponent = array('componentId' =>'productDetails',
                                                    'sequenceId'=>'5',
                                                    'isActive'=>'1',
                                                    'productDetailsUISettings'=>$productDetailsUISettings,
                                                    'productDetailsData'=>$productDetailsData); 

                    /*----------------------Product Deatil Component End---------------------------------*/






                    /*-------------Product Description Components Start ------------------------------*/

                    $bulletPointText =array('textColor' =>'#515151',
                      'font'=>'1',
                      'fontSize'=>'14',
                      'bulletPointColor'=>'#d8d8d8'); 

                    $buttonAllDetails = array('textColor' =>'#212121',
                      'font'=>'2',
                      'fontSize'=>'15',
                      'arrowColor'=>'#666666'); 

                    $poductDescTitle = array('textColor' =>'#212121',
                     'font'=>'2',
                     'fontSize'=>'16');

                    $productDescriptionUISettings= array('backgroundColor' =>'#ffffff',
                     'isShadow'=>'1',
                     'dividerColor'=>'#dedede',
                     'title'=>$poductDescTitle,
                     'buttonAllDetails'=>$buttonAllDetails,
                     'bulletPointText'=>$bulletPointText);

                    $descriptiondatadescall[] = array('text'=>$productInfo);

                    $productDescriptionData = array('title' =>'Highlights',
                      'acticonText'=>'All Details',
                      'type'=>'4',
                      'navigationFlag'=>'1',
                      'query'=>$baseUrl."ecommerceapi/productdescription?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId", 
                      'list'=>$descriptiondatadescall); 

                    $productDescription = array('componentId' =>'productDescription', 
                      'sequenceId'=>'6',
                      'isActive'=>'1', 
                      'productDescriptionUISettings'=>$productDescriptionUISettings,
                      'productDescriptionData'=>$productDescriptionData);


                    /*-----------Product Description Components End------------------------------*/


                    /*-------------Extended Warranty Component Start------------------------------*/
                    $installationFee = '0';
                    $extendedWarrantyData = array();
                    $extendedWarrantyComponent = array();
                    $extendedWarrantyUISettings = array('backgroundColor' =>'#ffffff',
                      'isShadow'=>'1',
                      'checkboxUnselectedColor'=>'#666666',
                      'checkboxselectedColor'=>'#666666',
                      'image'=>$baseUrl.'pub/media/ExtWar.png',
                      'titleText'=>array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'15'),
                      'subTitleText'=>array('textColor' =>'#666666',
                        'font'=>'2',
                        'fontSize'=>'10'),
                      'price'=>array('textColor' =>'#666666', 
                        'font'=>'2',
                        'fontSize'=>'14'));

                    $customTitle = 'Extended Warranty by Ashrafs Bahrain';
                    $subtitleOneYear = 'Extend Warranty coverage for 1 Year';
                    $subtitleTwoYear = 'Extend Warranty coverage for 2 Years';
                    $subtitleThreeYear = 'Extend Warranty coverage for 3 Years';
                    $installationTitle = 'Installation by Ashrafs Bahrain';
                    $installationSubtitle= 'Standard Installation';

                    $extendedWarranty = $product->getData('is_warranty');
                    if($extendedWarranty != 0){

                     $oneYearWarranty = $product->getData('extend_warranty_coverage_for_1'); 
                     $twoYearWarranty = $product->getData('extend_warranty_coverage_for_2');
                     $threeYearWarranty = $product->getData('extend_warranty_coverage_for_3');
                     $installationFee = $product->getData('installation');

                     $additional1 = array();
                     $additional2 = array();
                     $additional3 = array();

                     if($oneYearWarranty != ''){
                      $additional1 = array('warrantyId' =>'1',
                       'title'=>$subtitleOneYear,
                       'price'=>$oneYearWarranty.' '.$currency,
                       'type'=>'ExtendedWarrenty');

                    } else {
                     $additional1 = '';   
                   }

                   if($twoYearWarranty != ''){
                    $additional2 = array('warrantyId' =>'2',
                     'title'=>$subtitleTwoYear,
                     'price'=>$twoYearWarranty.' '.$currency,
                     'type'=>'ExtendedWarrenty');   
                  } else {
                    $additional2 = '';
                  }

                  if($threeYearWarranty != ''){
                    $additional3 = array('warrantyId' =>'3',
                     'title'=>$subtitleThreeYear,
                     'price'=>$threeYearWarranty.' '.$currency,
                     'type'=>'ExtendedWarrenty');     
                  } else {
                   $additional3 = '';
                 }  

                 if (!empty($additional1) && !empty($additional2) && !empty($additional3)) {
                  $additional = array($additional1,$additional2,$additional3);
                } elseif (!empty($additional1) && !empty($additional2)) {
                  $additional = array($additional1,$additional2);
                } elseif (!empty($additional1) && !empty($additional3)) {
                  $additional = array($additional1,$additional3);
                } elseif (!empty($additional2) && !empty($additional3)) {
                  $additional = array($additional2,$additional3);
                }  elseif (!empty($additional1)) {
                  $additional = array($additional1);
                } elseif (!empty($additional2)) {
                  $additional = array($additional2);
                } elseif (!empty($additional3)) {
                  $additional = array($additional3);
                } 


                $extendedWarrantyData = array('title' =>$customTitle,
                  'subTitleText'=>$subtitleOneYear,
                  'additional'=>$additional); 
                
                $extendedWarrantyComponent = array('componentId' =>'extendedWarranty',
                 'sequenceId'=>'7',
                 'isActive'=>'1',
                 'extendedWarrantyUISettings'=>$extendedWarrantyUISettings,
                 'extendedWarrantyData'=>$extendedWarrantyData);

              }else{

              }
              
              /*-------------Extended Warranty Component End------------------------------*/



              /*-----------------Extended Installation Component Start-----------------------------*/
              $extendedInstallationComponent = array();

              if($extendedWarranty != 0){

                $extendedInstallationUISettings = array('backgroundColor' =>'#ffffff',
                  'isShadow'=>'1',
                  'checkboxUnselectedColor'=>'#666666',
                  'checkboxselectedColor'=>'#666666',
                  'image'=>$baseUrl.'pub/media/ExtWar.png',
                  'titleText'=>array('textColor' =>'#212121',
                    'font'=>'2',
                    'fontSize'=>'15'), 
                  'subTitleText'=>array('textColor' =>'#666666',
                    'font'=>'2',
                    'fontSize'=>'10'),
                  'price'=>array('textColor' =>'#666666',       
                    'font'=>'2',
                    'fontSize'=>'14'));
                if($installationFee != ''){

                  $additionalInstallation  = array('installationId' =>'1',
                   'title'=>$installationSubtitle,
                   'price'=>$installationFee.' '.$currency,
                   'type'=>'Installation');

                } else {
                 $additionalInstallation = '';    
               }
               
               $additionalInst = array($additionalInstallation);

               $extendedInstallationData = array('title' =>$installationTitle,
                 'subTitleText'=>$installationSubtitle,
                 'additional'=>$additionalInst);


               $extendedInstallationComponent = array('componentId' =>'extendedInstallation',
                'sequenceId'=>'8',
                'isActive'=>'1',
                'extendedInstallationUISettings'=>$extendedInstallationUISettings,
                'extendedInstallationData'=>$extendedInstallationData);

             }else{

             }


             /*-----------------Extended Installation Component Start-----------------------------*/


             /*-----------------Product Review Component Start-----------------------------*/

             $productReview  = array();

             $reviewUISettings= array('backgroundColor' =>'#ffffff',
               'isShadow'=>'1',
               'dividerColor'=>'#dedede',
               'selectedRating'=>'#2a8afb',
               'addReviewFlag'=>'0',
               'unselectedRating'=>'#f0f0f0',
               'starRatingText'=>array('textColor' =>'#212121',
                 'font'=>'2',
                 'fontSize'=>'18'),
               'title'=>array('textColor' =>'#212121',
                 'font'=>'2',
                 'fontSize'=>'16'),
               'buttonAddReview'=>array('textColor' =>'#008BFF',
                 'font'=>'2',
                 'fontSize'=>'12',
                 'borderColor'=>'#dedede',
                 'backgroundColor'=>'#ffffff'),
               'reviewTitle'=>array('textColor' =>'#666666',
                 'font'=>'1',
                 'fontSize'=>'14'),
               'reviewContent'=>array('textColor' =>'#212121',
                 'font'=>'2',
                 'fontSize'=>'13'),
               'reviewFromText'=>array('textColor' =>'#212121',
                 'font'=>'2',
                 'fontSize'=>'13'),
               'reviewDate'=>array('textColor' =>'#666666',
                 'font'=>'2',
                 'fontSize'=>'13'),
               'buttonAllReviews'=>array('textColor' =>'#212121',
                 'font'=>'2',
                 'fontSize'=>'15',
                 'arrowColor'=>'#666666'));

             $reviewCollectionFactory= $objectManager->get('\Magento\Review\Model\ResourceModel\Review\CollectionFactory'); 
             $reviewsCollection = $reviewCollectionFactory->create()
             ->addFieldToFilter('entity_pk_value',array("in" => $productId))
             ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
             ->addRateVotes();
             $reviewsCollection->getSelect();

             foreach ($reviewsCollection->getItems() as $review) {

              $reviewDatedata = $review->getCreatedAt();
              $reviewDetail = $review->getDetail();
              $reviewTitlekk = $review->getTitle();
              $reviewFrom = $review->getNickname();

              foreach( $review->getRatingVotes() as $_vote ) {

                $rating = [];
                $percent = $_vote->getPercent();
                $star = ($percent/20);
                $productId = $_vote->getEntityPkValue();
                $product=$objectManager->get('\Magento\Catalog\Model\ProductFactory'); 
                $productModel = $product->create();
                $product = $productModel->load($productId);  

                $reviewFactory = $objectManager->get('\Magento\Review\Model\ReviewFactory'); 

                $countReview = $reviewFactory->create()->getTotalReviews($productId,false);
                $review_id = $_vote->getReviewId();

                $rating['review_id'] = $review_id; 
                $rating['product_id'] = $productId;    
                $rating['percent']   = $percent;  
                $rating['star']      = $star;    
                $rating['nickname']  = $review->getNickname(); 
                $reviewNickname = $review->getNickname();   
                $items[] = $rating;
                $starData[$star][] = $rating; 

                $reviewallDataCombine1[] = array('rating' =>''.$star.'',
                 'reviewTitle'=>$reviewTitlekk,
                 'reviewContent'=>$reviewDetail,
                 'reviewFromText'=>$reviewFrom, 
                 'reviewDate'=>$reviewDatedata);
              }
            }

            if(!empty($reviewallDataCombine1)){

              $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
              $reviewFactory->getEntitySummary($product, $storeId);
              $ratingSummary = $product->getRatingSummary()->getRatingSummary();
              $reviewCount = $product->getRatingSummary()->getReviewsCount();
              $ratingSummary1 = ($ratingSummary / 20);

              $reviewData = array('rating' =>''.$ratingSummary1.'','list'=>$reviewallDataCombine1); 

              $productReview = array('componentId' =>'review',
               'sequenceId'=>'9', 
               'isActive'=>'1',
               'reviewUISettings'=>$reviewUISettings,
               'reviewData'=>$reviewData);

            }
            /*--------------------------Product Review Component End---------------------------*/



            /*--------------------------UpSell Products Component Start---------------------------*/

            $current_product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); 
            if ($current_product) {
              $upSellProducts = $current_product->getUpSellProducts();

              if (!empty($upSellProducts)) {

                foreach ($upSellProducts as $upSellProduct) {

                  $productIdupsell = $upSellProduct->getId();     
                  $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productIdupsell); 
                  $upsellId = $product->getId();
                  $upsellName = $product->getName();
                  if($upsellName == ""){
                   $upsellName = "";
                 }
                 $upsellSku= $product->getSku();
                 $upsellproducut= $product->getProductUrl();
                 $upsellImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();

                 $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
                 $reviewFactory->getEntitySummary($product, $storeId);
                 $ratingSummary = $product->getRatingSummary()->getRatingSummary();
                 $reviewCount = $product->getRatingSummary()->getReviewsCount();
                 $ratingSummary1 = ($ratingSummary / 20);

                 $upsellPrice = $product->getPrice(); 
                 if($upsellPrice ==''){
                   $upsellPrice = "";
                 } else {
                   $upsellPrice = $upsellPrice;
                 } 

                 $likeFlag = '0';
                 if(in_array($productIdupsell, $wishlistItems)){
                   $likeFlag = '1';
                 }

                 $cartFlag = '0';
                 if(in_array($productIdupsell, $cartItems)){ 
                  $cartFlag = '1';
                } 

                $upsellDisprice = $product->getSpecialPrice(); 

                if($upsellDisprice == ''){
                 $upsellDisprice = "";
               } else {
                 $upsellDisprice = $upsellDisprice;
               } 

               $fourComponentDataAll[] = array('id' =>$upsellId,
                'image'=>$upsellImage,
                'type'=>'2',
                'navigationFlag'=>'1',  
                'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",
                'title'=>$upsellName, 
                'price'=>$upsellPrice.' '.$currency,
                'discountedPrice'=>$upsellDisprice,
                'rating'=>''.$ratingSummary1.'',
                'likeFlag'=>$likeFlag,
                'cartFlag'=>$cartFlag); 
             }

             $fourData = array_splice($fourComponentDataAll, 0, 8); 

             $fourComponentUISettings= array('isShadow'=>'1',
              'mediaType'=>'1',
              'addToCartFlag'=>'1',
              'type'=>'2',  
              'navigationFlag'=>'1',
              'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",  
              'viewAllButtonFlag'=>'0',
              'backgroundMediaData'=>'#ffffff',
              'selectedRating'=>'#2a8afb',
              'unselectedRating'=>'#f0f0f0',
              'imageViewBackgroundColor'=>'#ffffff',
              'likeColor'=>'#2a8afb',
              'disLikeColor'=>'#666666',
              'likeCircleImageColor'=>'#f6f6f6',
              'addToCartIconColor'=>'#2a8afb',
              'addToCartCircleColor'=>'#ffffff',
              'scratchLineWidth'=>'1',
              'title'=>array('textColor' =>'#212121',
                'font'=>'2',
                'fontSize'=>'16'), 
              'viewAllButton'=>array('font'=>'2',
                'textColor' =>'#ffffff',
                'fontSize'=>'12',
                'backgroundColor'=>'#298afc'),
              'productName'=>array('textColor' =>'#212121',
                'font'=>'1',
                'fontSize'=>'16'),
              'price'=>array('textColor' =>'#666666',
                'font'=>'2',
                'fontSize'=>'14'),
              'discountPrice'=>array('textColor' =>'#e6201a',
                'font'=>'2',
                'fontSize'=>'14'));

             $fourComponentData = array('title' =>'Bought Together','list'=>$fourData);


             $fourComponent = array('componentId' =>'fourComponent',
              'sequenceId'=>'10',
              'isActive'=>'1',
              'fourComponentUISettings'=>$fourComponentUISettings,
              'fourComponentData'=>$fourComponentData);
           } else {
            $fourComponent = array();
          }

        }

        /*--------------------------UpSell Products Component End---------------------------*/



        /*-------------------------- Component Slider Start---------------------------------*/
        $sliderComponentData = array();
        $componentSlider = array();

        $slidertitle = array('textColor' =>'#212121',
         'font'=>'2',
         'fontSize'=>'16');

        $sliderviewAllButton = array('font'=>'1', 
         'textColor' =>'#666666',
         'fontSize'=>'14',
         'backgroundColor'=>'#ffffff');

        $sliderproductName = array('textColor' =>'#212121',
         'font'=>'1',
         'fontSize'=>'16');

        $sliderprice = array('textColor' =>'#666666',
         'font'=>'2',
         'fontSize'=>'14');

        $sliderdiscountPrice = array('textColor' =>'#e6201a',
         'font'=>'2',
         'fontSize'=>'14'); 

        $componentSliderUISettings= array('isShadow'=>'1',
          'mediaType'=>'1',
          'addToCartFlag'=>'1',
          'sliderType'=>'2',
          'backgroundMediaData'=>'#ffffff',
          'selectedRating'=>'#2a8afb',
          'unselectedRating'=>'#f0f0f0',
          'imageViewBackgroundColor'=>'#ffffff',
          'likeColor'=>'#2a8afb',
          'disLikeColor'=>'#666666',
          'likeCircleImageColor'=>'#f6f6f6',
          'addToCartIconColor'=>'#2a8afb',
          'addToCartCircleColor'=>'#ffffff',
          'scratchLineWidth'=>'1',
          'type'=>'2',
          'navigationFlag'=>'1',
          'viewAllButtonFlag'=> '0',
          'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",
          'title'=>$slidertitle, 
          'viewAllButton'=>$sliderviewAllButton,
          'productName'=>$sliderproductName,
          'price'=>$sliderprice,
          'discountPrice'=>$sliderdiscountPrice); 


        $recentlyViewed = $objectManager->get('\Magento\Reports\Block\Product\Viewed'); 
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->load($customerId); 
                if ($customer_check->getId())  {  // Registered customer

                  $prodMyViewedCol = $objectManager->get('\Magento\Reports\Model\ResourceModel\Event\Collection');
                  $prodMyViewedCol->setOrder('logged_at','DESC');

                  $prodViewedThisEntirelyIdCol = array();  //used to store products viewed by currently logged in user.

                  foreach($prodMyViewedCol as $prod){
                    if($prod->getSubjectId() == $customerId){// guestuser


                     $likeFlag ='0';
                     $cartFlag = '0';
                     $productId = $prod->getObjectId();
                     $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
                     if($product->getId()){
                     $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
                     $title = $product->getName(); 
                     $price = $product->getPrice();
                     $offeredPrice = $product->getSpecialPrice();

                     if($offeredPrice == ''){
                                $offeredPrice ="";
                     } else {
                                $offeredPrice = $offeredPrice.' '.$currency;
                     }

                    $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
                    $reviewFactory->getEntitySummary($product, $storeId);
                    $ratingSummary = $product->getRatingSummary()->getRatingSummary();
                    $reviewCount = $product->getRatingSummary()->getReviewsCount();
                    $ratingSummary1 = ($ratingSummary / 20);
                    $prodViewedThisEntirelyIdCol[] = $productId;

                    $sliderComponentData[] = array('image'=>$image,  
                      'id'=>$productId,
                      'type'=>'2',
                      'navigationFlag'=> '1',    
                      'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",  
                      'title'=>$title,
                      'price'=>$price.' '.$currency,
                      'discountedPrice'=>$offeredPrice, 
                      'rating'=>"".$ratingSummary1."", 
                      'likeFlag'=>$likeFlag, 
                      'cartFlag'=>$cartFlag);  

                    if(!empty($sliderComponentData)){
                     $sliderComponentDataall = array('title' =>'RECENTLY VIEWED PRODUCTS',
                       'list'=>$sliderComponentData); 

                     $componentSlider = array('componentId' =>'componentSlider',
                                              'sequenceId'=>'11',
                                              'isActive'=>'1', 
                                              'componentSliderUISettings'=>$componentSliderUISettings,
                                              'componentSliderData'=>$sliderComponentDataall);
                   }
                   } else {

                   }

                 } else {
                  $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                  $collection = $productCollection->addAttributeToSelect('*')->addFieldToFilter('entity_id', array('in' => $recentProductId));       

                  foreach($collection as $product){

                    $likeFlag = '0';
                    $cartFlag = '0';

                    $id =$product->getId();
                    $storeId =$storeManager->getStore()->getStoreId(); 
                    $StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
                    $quantity = $StockState->getStockQty($id, $storeManager->getStore()->getWebsiteId());

                    if($quantity >= '1'){
                      $stockFlag = '0';
                    }else {
                     $stockFlag = '1';
                   }

                   $title = $product->getName();
                   $price = $product->getPrice();

                   if($price == ''){
                    $price = "0";
                  }

                  $offeredPrice = $product->getSpecialPrice();
                  if ($offeredPrice == '') {

                   $offeredPrice = ""; 
                 }else{
                  $offeredPrice = $offeredPrice.' '.$currency; 
                }


                $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();

                $product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
                $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
                $reviewFactory->getEntitySummary($product, $storeId);
                $ratingSummary = $product->getRatingSummary()->getRatingSummary();
                $reviewCount = $product->getRatingSummary()->getReviewsCount();
                $ratingSummary1 = ($ratingSummary / 20); 
                if($ratingSummary1 != ''){
                  $ratingSummary1 = ''.$ratingSummary1.'';
                } else {
                  $ratingSummary1 = '0';
                }
                $sliderComponentData[] = array('image'=>$image,  
                  'id'=>$id,
                  'type'=>'2',
                  'navigationFlag'=> '1',    
                  'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",  
                  'title'=>$title,
                  'price'=>$price.' '.$currency,
                  'discountedPrice'=>$offeredPrice, 
                  'rating'=>$ratingSummary1, 
                  'likeFlag'=>$likeFlag, 
                  'cartFlag'=>$cartFlag);  

              }
              if(!empty($sliderComponentData)){
               $sliderComponentDataall = array('title' =>'RECENTLY VIEWED PRODUCTS',
                 'list'=>$sliderComponentData); 

               $componentSlider = array('componentId' =>'componentSlider',
                'sequenceId'=>'11',
                'isActive'=>'1', 
                'componentSliderUISettings'=>$componentSliderUISettings,
                'componentSliderData'=>$sliderComponentDataall);
             } else {

             }

           }
         }
                }else{ //Guest User

                  $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                  $collection = $productCollection->addAttributeToSelect('*')->addFieldToFilter('entity_id', array('in' => $recentProductId));       

                  foreach($collection as $product){ 

                    $likeFlag = '0';
                    $cartFlag = '0';

                    $id =$product->getId();
                    $storeId =$storeManager->getStore()->getStoreId(); 
                    $StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
                    $quantity = $StockState->getStockQty($id, $storeManager->getStore()->getWebsiteId());

                    if($quantity >= '1'){
                      $stockFlag = '0';
                    }else {
                     $stockFlag = '1';
                   }

                   $title = $product->getName();
                   $price = $product->getPrice();

                   if($price == ''){
                    $price = "0";
                  }

                  $offeredPrice = $product->getSpecialPrice();
                  if ($offeredPrice == '') {

                   $offeredPrice = ""; 
                 }else{
                  $offeredPrice = $offeredPrice.' '.$currency; 
                }


                $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();

                $product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
                $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
                $reviewFactory->getEntitySummary($product, $storeId);
                $ratingSummary = $product->getRatingSummary()->getRatingSummary();
                $reviewCount = $product->getRatingSummary()->getReviewsCount();
                $ratingSummary1 = ($ratingSummary / 20); 
                if($ratingSummary1 != ''){
                  $ratingSummary1 = ''.$ratingSummary1.'';
                } else {
                  $ratingSummary1 = '0';
                }
                $sliderComponentData[] = array('image'=>$image,  
                  'id'=>$id,
                  'type'=>'2',
                  'navigationFlag'=> '1',    
                  'query'=>$baseUrl."ecommerceapi/Productdeatil?productId=$productId&customerId=$customerId&langId=$langId&currencyId=$curId&recentId=",  
                  'title'=>$title,
                  'price'=>$price.' '.$currency,
                  'discountedPrice'=>$offeredPrice, 
                  'rating'=>$ratingSummary1, 
                  'likeFlag'=>$likeFlag, 
                  'cartFlag'=>$cartFlag);  

              }
              if(!empty($sliderComponentData)){
               $sliderComponentDataall = array('title' =>'RECENTLY VIEWED PRODUCTS',
                 'list'=>$sliderComponentData); 

               $componentSlider = array('componentId' =>'componentSlider',
                'sequenceId'=>'11',
                'isActive'=>'1', 
                'componentSliderUISettings'=>$componentSliderUISettings,
                'componentSliderData'=>$sliderComponentDataall);
             } else {

             }
           }


           /*-------------------------- Component Slider END  ---------------------------------*/



           $component = array($componentFirst,$productdetailcomponent,$similarProducts1,$fourComponent,$componentSlider,$customComponent,$productDescription,$productReview,$extendedInstallationComponent,$extendedWarrantyComponent,$attributes);


           $component = array_filter($component);
           $component = (array_values($component));

           $response = array('status' =>'OK',
            'statusCode'=>200,  
            'message'=>'success',
            'isUpdateUISettingFlag'=>'0',
            'generalUISettings'=>$generalUISettings,
            'component'=>$component); 
           echo json_encode($response ,JSON_UNESCAPED_SLASHES);
         } else {
          $response = array('status' =>'Error',
            'statusCode'=>300,  
            'message'=>'Product not found in the catalog');
          echo json_encode($response ,JSON_UNESCAPED_SLASHES);
        }
      }  
    }
