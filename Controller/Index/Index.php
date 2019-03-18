<?php
namespace Magneto\EcommerceApi\Controller\Index;

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
        $cattitle = array('textColor' =>'#666666',
                    'font'=>'2',
                    'fontSize'=>'10');

        $catuiset = array('isShadow' =>'1', 
                    'mediaType'=>'1',
                    'backgroundMediaData'=>'#ffffff',
                    'title'=>$cattitle);

        // Device register
        //$deviceId = $_POST["deviceId"];
        // $customerId = $_POST["customerId"];
 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
        $catalogProductVisibility = $objectManager->get('\Magento\Catalog\Model\Product\Visibility'); 
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $currencysymbol = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currency = $currencysymbol->getStore()->getCurrentCurrencyCode();

        $customerId = $_POST["customerId"]; 
        $langId =   $_POST["langId"]; 
        $currencyId = $_POST["curId"];
        $storeId = $langId ; 

        /* Start: Set Read Notification Data */
        /*$deviceModelCollection = $objectManager->get('\Magneto\AppNotification\Model\ResourceModel\RegisterDevice\Collection')->addFieldToFilter('device_id', $deviceId);
        if(empty($customerId)){
          if($deviceModelCollection->getData()){
            foreach ($deviceModelCollection as $value) {
              $id = $value->getCustomerId();
            }
            $customerId = $id;
            if(empty($customerId)){
              $customerId = '0';
            }
          }else{
              $customerId = '0';
          }         
        }

        $notificationupdateTable = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotificationUpdate\Collection')->addFieldToFilter('device_id', $deviceId);
        $notif_update_count = count($notificationupdateTable); 

        $notificationTable = $objectManager->get('Magneto\AppNotification\Model\ResourceModel\AppNotification\Collection')->addFieldToFilter('customer_id', $customerId);
        $notif_count = count($notificationTable);

        $notificationCount = $notif_count - $notif_update_count;
*/
        // End notification Data

        
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        
        $categoryCollection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');

        $categories = $categoryCollection->create();
        $categories->addAttributeToSelect('*')
                    ->setStore($storeId)
                    ->load(); 
        /*$categories;*/
        $categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
        $categoryObj = $objectManager->create('Magento\Catalog\Model\Category');
        $categories = $categoryHelper->getStoreCategories();
        //echo "<pre>"; print_r(get_class_methods($categories)); exit();
        $categoryData1 = array();  

        foreach ($categories as $category) { 

          $categoryObj = $categoryObj->load($category->getEntityId());
          $catid1=  $category->getId();
          $catname1 =  $category->getName();
          $category = $categoryFactory->create()->load($catid1);
          $catcount = $category->getProductCount();
          if($catname1 == ''){
            $catname1 ="";
          } else {
            $catname1 = $catname1;
          }
          $catimage = $categoryObj->getImageUrl();
          if($catimage == false){
            $catimage = "";
          }else{
            $catimage =''.$catimage.'';
          }
          
          $catimage1 = $categoryObj->getImageUrl('thumbnail'); 
          if($catimage1 == false){
             $catimage1 = "";
          } else {
              $catimage1 = $catimage1;
          }
          $categoryData1[] = array('id' =>$catid1,
                                   'image'=>$catimage,
                                   'title'=>$catname1,
                                   'type'=>'1',
                                   'navigationFlag'=>'1',
                                   'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId");  
                                                                
        } 
        


        $catdata2 = array('list' =>$categoryData1);  

        $component = array('componentId' =>'category',
                           'sequenceId'=>'1',
                           'isActive'=>'1',
                           'categoryUISettings'=>$catuiset,
                           'categoryData'=>$catdata2); 

        
      
        $bannerUi = array('isShadow' =>'1',
                          'mediaType'=>'1',
                          'backgroundMediaData'=>'#ffffff',
                          'imageHeight'=>'900',
                          'imageWidth'=>'1650'); 
         /*-------------------------banner Start----------------------------*/
   
        $connectionUrl  = $objectManager->get('Mageplaza\BannerSlider\Model\ResourceModel\Data\Collection');

        foreach ($connectionUrl as $k){
          $id = $k->getBannerId(); 
          $image = $k->getImage();
          $bannerall[] = array('id' =>$id ,
                               'image'=>$baseUrl.'pub/media/mageplaza/bannerslider/banner/image/'.$image.'',
                               'type'=>'1',
                               'navigationFlag'=>'1',
                               'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId"); 
        }
        
        $banneralldata2 = array('list' =>$bannerall); 

        $componentBanner = array('componentId' =>'banner',
                                 'sequenceId'=>'2',
                                 'isActive'=>'1',
                                 'bannerUISettings'=>$bannerUi,
                                 'bannerData'=>$banneralldata2);  

          /*------------------------------banner End----------------------------------------------*/

              /*-----------------------------brand start----------------------------------------------*/
             
         $object1 = $objectManager->get('Amasty\ShopbyBase\Model\ResourceModel\OptionSetting\Collection')->addFieldToFilter('is_featured','1');  
         foreach ($object1 as $dataBrand) 
         {
          //echo "<pre>"; print_r($dataBrand->getData());exit();
           $id = $dataBrand->getId(); 
           $image  =   $dataBrand->getImage();
           $catid1 = $id;
           $branddara[] = array('id' =>$id ,
                                'image'=>$baseUrl.'pub/media/amasty/shopby/option_images/'.$image.'',
                                'type'=>'1', 
                                'navigationFlag'=>'1',
                                'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId"); 
         } 

        $branddataall2 = array('title'=>'BRANDS','list' =>$branddara); 

        $componentBrand = array('componentId' =>'brand', 
                                'sequenceId'=>'4',
                                'isActive'=>'1',
                                'brandUISettings'=>array('isShadow' =>'1',
                                'mediaType'=>'1',
                                'backgroundMediaData'=>'#ffffff',
                                'imageViewBackgroundColor'=>'#ffffff',
                                'title'=>array('textColor' =>'#666666',
                                'font'=>'2',
                                'fontSize'=>'16')), 
                                'brandData'=>$branddataall2); 

        /*-----------------------------------------brand end------------------------------------------*/

        /*--------------------------------Single image start----------------------------------------*/
        $singleImageData[] = array('id' =>$catid1,
                                   'image'=>$catimage1,
                                   'type'=>'3',  
                                   'navigationFlag'=>'1',
                                   'query'=>$baseUrl."ecommerceapi/subscreen?customerId=$customerId&langId=$langId&curId=$currencyId");  

        $componentSingleImage = array('componentId' => 'singleImage',
                                      'sequenceId' => '3', 
                                      'isActive' => '1',
     'singleImageUISettings' => array('isShadow' => '1',
                                      'mediaType' => '1',
                                      'backgroundMediaData' => '#ffffff',
                                      'imageHeight'=>'352', 
                                      'imageWidth'=>'1080'), 
            'singleImageData' =>array('list' => $singleImageData));  

         /*------------Single image end------------------*/

        $toptitile = array('textColor' =>'#666666',
                            'font'=>'2',
                            'fontSize'=>'15');

        $viewAllButton = array('font' =>'2',
                               'textColor'=>'#ffffff',
                               'fontSize'=>'12',
                               'backgroundColor'=>'#298afc',
                               'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId");

        $productName = array('textColor' =>'#212121',
                             'font'=>'1',
                             'fontSize'=>'16');

        $productprice = array('textColor' =>'#666666',
                              'font'=>'2',
                              'fontSize'=>'14');

        $discountPrice = array('textColor' =>'#e6201a',
                               'font'=>'2',
                               'fontSize'=>'14' );

        //$fullprice = $productprice.''.$currencysymbol;
       

        $fourComponentUISettings = array('isShadow'=>'1',
                                         'mediaType'=>'1', 
                                         'type'=>'1',
                                         'navigationFlag'=>'1',
                                         'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId",  
                                         'addToCartFlag'=>'0',
                                         'addToCartIconColor'=>'#2a8afb',
                                         'addToCartCircleColor'=>'#ffffff',
                                         'viewAllButtonFlag'=>'0', 
                                         'backgroundMediaData'=>'#ffffff',
                                         'selectedRating'=>'#2a8afb',
                                         'unselectedRating'=>'#f0f0f0',
                                         'imageViewBackgroundColor'=>'#ffffff',
                                         'likeColor'=>'#2a8afb',
                                         'disLikeColor'=>'#666666',
                                         'isSortFilter'=>'0',
                                         'likeCircleImageColor'=>'#f6f6f6',
                                         'scratchLineWidth'=>'1',
                                         'title'=>$toptitile,
                                         'viewAllButton'=>$viewAllButton,
                                         'productName'=>$productName,
                                         'price'=>$productprice,  
                                         'discountPrice'=>$discountPrice);   


// --------------------------------------------------------------Four Component Start 


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

                $quote = $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId);

                $quoteItems = $quote->getAllVisibleItems();

                foreach($quoteItems as $oneItem){
                   $cartItems[] = $oneItem->getProductId();
                }

            }


            $productCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Product\CollectionFactory');
            $collection = $productCollection->create()
                          ->addAttributeToSelect('*')
                          ->addAttributeToFilter('status','1')
                          ->addAttributeToFilter('is_featured', '1')
                          ->addFieldToFilter('visibility', $catalogProductVisibility->getVisibleInSiteIds())
                          ->setPageSize(4)
                          ->setStoreId($storeId)
                          ->load(); 



            foreach ($collection as $item) {


                $productId = $item->getId(); 
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
                 //$final_price=$_product->getFinalPrice();
                $likeFlag = '0';
                if(in_array($productId, $wishlistItems)){
                    $likeFlag = '1';
                }

                $cartFlag = '0';
                /*if(in_array($productId, $cartItems)){
                    $cartFlag = '1';
                }*/

            $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
            $storeId = $storeManager->getStore()->getStoreId();
            $reviewFactory->getEntitySummary($product, $storeId);

            $ratingSummary = $product->getRatingSummary()->getRatingSummary();
            $reviewCount = $product->getRatingSummary()->getReviewsCount();
            $ratingSummary1 = ($ratingSummary / 20); 

                $upsellImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$item->getImage(); 
                $upsellName = $item->getName();
                //$upsellPrice = $item->getPrice();  
                if($configProduct->getTypeId() == "configurable"){
                    $upsellPrice = $childPriceLowest;
                }else{
                    $upsellPrice = $item->getPrice();
                } 

                $upsellDisprice = $item->getSpecialPrice();
                if($upsellDisprice == ''){
                  $upsellDisprice = "";
                }
                 $fullprice = $upsellPrice.' '.$currency;
                 //$catid1 = $product->getCategoryIds();
                
                $topselldata1[] = array('id' =>$productId, 
                                        'image'=>$upsellImage,
                                        'type'=>'2',
                                        'navigationFlag'=>'1',
                                        'query'=>$baseUrl."ecommerceapi/productdetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$currencyId&recentId=",  
                                        'title'=>$upsellName,
                                        'price'=>$fullprice,
                                        'discountedPrice'=>$upsellDisprice, 
                                        'rating'=>"".$ratingSummary1."", 
                                        'likeFlag'=>$likeFlag,
                                        'cartFlag'=>$cartFlag); 
            }


                    $topselldataall2 = array('title'=>'FEATURED PRODUCTS','list' =>$topselldata1);  

                    $componenttopsell = array('componentId' =>'fourComponent',
                                              'sequenceId'=>'5',
                                              'isActive'=>'1',
                                              'fourComponentUISettings'=>$fourComponentUISettings,
                                              'fourComponentData'=>$topselldataall2); 

// -------------------------------------------------------------Four Component End 

// -----------------------------------------------------Component SLider Component Start 


              $toptitile1 = array('textColor' =>'#666666',  
                            'font'=>'2',
                            'fontSize'=>'15');

        $viewAllButton1 = array('font' =>'2',
                                'textColor'=>'#ffffff',
                                'fontSize'=>'12',
                                'backgroundColor'=>'#298afc',
                                'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId"); 

        $productName1 = array('textColor' =>'#212121', 
                              'font'=>'1',
                              'fontSize'=>'16');

        $productprice1 = array('textColor' =>'#666666',
                               'font'=>'2',
                               'fontSize'=>'14');

        $discountPrice1 = array('textColor' =>'#e6201a',
                                'font'=>'2',
                                'fontSize'=>'14' );

        $componentSliderUISettings  = array('isShadow'=>'1',
                                            'mediaType'=>'1',
                                            'sliderType'=>'1', 
                                            'type'=>'1',
                                            'navigationFlag'=>'1',
                                            'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$customerId&langId=$langId&curId=$currencyId",
                                            'addToCartFlag'=>'0',  
                                            'addToCartIconColor'=>'#2a8afb',
                                            'addToCartCircleColor'=>'#ffffff',
                                            'viewAllButtonFlag'=>'0', 
                                            'backgroundMediaData'=>'#ffffff',
                                            'selectedRating'=>'#2a8afb', 
                                            'isSortFilter'=>'0',
                                            'unselectedRating'=>'#f0f0f0',
                                            'imageViewBackgroundColor'=>'#ffffff',
                                            'likeColor'=>'#2a8afb',
                                            'disLikeColor'=>'#666666',
                                            'likeCircleImageColor'=>'#f6f6f6',
                                            'scratchLineWidth'=>'1',
                                            'title'=>$toptitile1,
                                            'viewAllButton'=>$viewAllButton1, 
                                            'productName'=>$productName1,
                                            'price'=>$productprice1,
                                            'discountPrice'=>$discountPrice1);       


               $collectionFactory = $objectManager->create('\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory'); 
                $bestSellerProdcutCollection = $collectionFactory->create()
                                               ->setModel('Magento\Catalog\Model\Product')
                                               ->setPeriod('year'); 
                       
                      

              
            foreach ($collection as $product) {
             
                $productId = $product['entity_id']; 
                 
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

                $price = $product['price'];
                if($configProduct->getTypeId() == "configurable"){
                    $price = $childPriceLowest;
                }else{
                    $price = $product['price'];
                }
                $name = $product['name'];  

                $likeFlag = '0';
                if(in_array($productId, $wishlistItems)){
                    $likeFlag = '1';
                }

                $cartFlag = '0'; 

                    $reviewFactory = $objectManager->create('Magento\Review\Model\Review');
                    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
                    $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
                    $storeId = $storeManager->getStore()->getStoreId();
                    $reviewFactory->getEntitySummary($product, $storeId);

                    $ratingSummary = $product->getRatingSummary()->getRatingSummary();
                    $reviewCount = $product->getRatingSummary()->getReviewsCount();
                    $ratingSummary1 = ($ratingSummary / 20); 
                    $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage(); 
                    
                    
                    if($price == ''){
                      $price = "";
                    }else{
                      $price = $price.' '.$currency;
                    }
                    $discountedPrice = $product->getSpecialPrice();
                    if($discountedPrice == ''){
                      $discountedPrice = "";
                    }else{
                      $discountedPrice = $discountedPrice.' '.$currency;
                    }
                    $additional = '';
                    if($discountedPrice == ''){
                      $additional = "";
                    }else{
                      $discountedPrice = '';
                    }


                     $compoSlider[]    = array('id' =>$productId, 
                                               'image'=>$image,
                                               'type'=>'2',
                                               'navigationFlag'=>'1',
                                               'query'=>$baseUrl."ecommerceapi/productdetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$currencyId&recentId=",  
                                               'title'=>$name,
                                               'price'=>$price, 
                                               'discountedPrice'=>$discountedPrice, 
                                               'rating'=>"".$ratingSummary1."", 
                                               'likeFlag'=>$likeFlag,
                                               'cartFlag'=>$cartFlag); 
         
          }  
       
          if($compoSlider != ''){
                  $compoSlider = array('title'=>'TOP SELLING PRODUCTS','list' =>$compoSlider);

                  $componentSlider = array('componentId' =>'componentSlider',
                                         'sequenceId'=>'5',
                                         'isActive'=>'1',
                                         'componentSliderUISettings'=>$componentSliderUISettings,
                                         'componentSliderData'=>$compoSlider); 
          } else {
                  $componentSlider = array();
          }


          // -------------------------------------------Slider Component End   


          /*-----------------------Featured Categories Component Start------------------------*/
          $star ='0';
          $categoryDataAll = array(); 

          foreach ($categories as $category) { 

              $categoryObj = $categoryObj->load($category->getEntityId());
              $categoryId =  $category->getId();
              $catname =  $category->getName();
               
              $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
              $productFactory = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
              $category = $categoryFactory->create()->load($categoryId);
              $collection = $productFactory->create();
              $collection->addAttributeToSelect('*');
              $collection->addCategoryFilter($category);
              $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
              $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
              $collection->addAttributeToFilter('is_featured', '1');
              $collection->setPageSize(5)->load();

              $catProductData  = array();

              foreach($collection as $products){
                $productId = $products->getId(); 
                $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
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
                        } 
                      $likeFlag = '0';
                      if(in_array($productId, $wishlistItems)){
                          $likeFlag = '1';
                      }
                      $cartFlag = '0';
                      $catImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$products->getImage(); 
                      $catName =  $products->getName();
                      $price = $products->getPrice();  
                      if($configProduct->getTypeId() == "configurable"){
                          $price = $childPriceLowest;
                      }else{
                          $price = $products->getPrice(); 
                      }

                      if($price == ''){
                        $price = "";
                      } else {
                        $price = $price.' '.$currency;
                      }

                      $catDisprice = $products->getSpecialPrice();
                      if($catDisprice == ''){
                           $catDisprice = "";
                      }else{
                         $catDisprice = $catDisprice.' '.$currency;
                      }

                      $reviewCollectionFactory= $objectManager->get('\Magento\Review\Model\ResourceModel\Review\CollectionFactory'); 
                      $reviewsCollection = $reviewCollectionFactory->create()
                                            ->addFieldToFilter('entity_pk_value',array("in" => $productId))
                                            ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
                                            ->addRateVotes();
                      $reviewsCollection->getSelect();

            foreach ($reviewsCollection->getItems() as $review) {

                    foreach( $review->getRatingVotes() as $_vote ) {

                            $rating = [];
                            $percent = $_vote->getPercent();
                            $star = ($percent/20);
                            if($star != ''){
                                $star == ''.$star.'';
                            }else{

                            }
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
                  	}
      		}

            if(!empty($categoryDataAll)){
              $catProductData[]  = array('id' =>$productId, 
                                         'image'=>$catImage,
                                         'type'=>'2',
                                         'navigationFlag'=>'1',
                                         'query'=>$baseUrl."ecommerceapi/productdetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$currencyId&recentId=",  
                                         'title'=>$catName,
                                         'price'=>$price,
                                         'discountedPrice'=>$catDisprice, 
                                         'rating'=>"".$star."", 
                                         'likeFlag'=>$likeFlag,
                                         'cartFlag'=>$cartFlag);

            } 
        }
          
                    
                    //$categoryProducts = array_filter($categoryProducts);
               if(count($catProductData) != 0){
                  $categoryProducts = array('title'=>$catname,'list' =>$catProductData); 
               } else{
                  $categoryProducts = array();  
               }

               if(count($catProductData) != 0){
                  $toptitile = array('textColor' =>'#666666',
                                     'font'=>'2',
                                     'fontSize'=>'15');

                  $viewAllButton = array('font' =>'2',
                                         'textColor'=>'#ffffff',
                                         'fontSize'=>'12',
                                         'backgroundColor'=>'#298afc',
                                         'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$categoryId&customerId=$customerId&langId=$langId&curId=$currencyId");

                  $productName = array('textColor' =>'#212121',
                                       'font'=>'1',
                                       'fontSize'=>'16'); 

                  $productprice = array('textColor' =>'#666666',
                                        'font'=>'2',
                                        'fontSize'=>'14');

                  $discountPrice = array('textColor' =>'#e6201a',
                                         'font'=>'2',
                                         'fontSize'=>'14' );


                  $categoryComponentUISettings = array('isShadow'=>'1',
                                                       'mediaType'=>'1',
                                                       'sliderType'=>'1', 
                                                       'type'=>'1',
                                                       'navigationFlag'=>'1',
                                                       'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$categoryId&customerId=$customerId&langId=$langId&curId=$currencyId",
                                                       'addToCartFlag'=>'0',  
                                                       'addToCartIconColor'=>'#2a8afb',
                                                       'addToCartCircleColor'=>'#ffffff',
                                                       'viewAllButtonFlag'=>'1', 
                                                       'backgroundMediaData'=>'#ffffff',
                                                       'selectedRating'=>'#2a8afb', 
                                                       'isSortFilter'=>'0',
                                                       'unselectedRating'=>'#f0f0f0',
                                                       'imageViewBackgroundColor'=>'#ffffff',
                                                       'likeColor'=>'#2a8afb',
                                                       'disLikeColor'=>'#666666',
                                                       'likeCircleImageColor'=>'#f6f6f6',
                                                       'scratchLineWidth'=>'1',
                                                       'title'=>$toptitile,
                                                       'viewAllButton'=>$viewAllButton, 
                                                       'productName'=>$productName,
                                                       'price'=>$productprice,
                                                       'discountPrice'=>$discountPrice);

                              $categoryDataAll[] = array('componentId' =>'componentSlider',
                                                         'sequenceId'=>'6',
                                                         'isActive'=>'1',
                                                         'categoryId'=>$categoryId,
                                                         'componentSliderUISettings'=>$categoryComponentUISettings,
                                                         'componentSliderData'=>$categoryProducts);
                            }else{ 
                                  $categoryDataAll[] = array();
                            }
                       
                            
                    
          
 }

          /*-----------------------Featured Categories Component End------------------------*/


        $mycartCount = '';

           $component =  array($component,$componentBanner,$componentBrand,$componentSingleImage,$componenttopsell,$componentSlider); 

           $component1 =  array_merge($component,$categoryDataAll);

          $component1 = array_filter($component1);
          $component1 = (array_values($component1));
 
             $status = array('status' =>'OK',
                             'statusCode'=>200,
                             'message'=>'Success',
                             'langId'=>$langId,  
                             'mycartCount'=>strval($mycartCount),
                             'appUpdateFlag'=>'0',
                             'notificationFlag'=>'0',
                             'appUnderMaintainenceFlag'=>'0',
                             'pageLoadUrl'=>'http://103.51.153.235/ecommerce/Coming%20Soon/07-comming-soon.html',
  'generalUISettings'=>array('mediaType'=>'1',  
                             'backgroundMediaData'=>'#f6f6f6',
                             'navDividerColor'=>'#DEDEDE',
                             'notificatioDotColor'=>'#308af6'), 
                             'component'=>$component1); 
             
               
          
           echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
          
          
    } 
}   
 