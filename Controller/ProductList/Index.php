<?php

namespace Magneto\Ecommerceapi\Controller\ProductList;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute() 
    {    
        
        $categoryId = $_REQUEST["categoryId"]; // YOUR CATEGORY ID 
        $customerId = $_REQUEST["customerId"]; 
        $langId =  $_REQUEST["langId"];
        $currencyId = $_REQUEST["curId"];  
        $pageSize = $_REQUEST["pageSize"];
        $page = $_REQUEST["page"];
        $storeId  = $langId;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

       // $currencysymbol = $objectManager->get('Magento\Directory\Model\Currency')->getCurrencySymbol();
        $currencysymbol = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currency = $currencysymbol->getStore()->getCurrentCurrencyCode(); 
        $baseUrl= $storeManager->getStore()->getBaseUrl();

        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
        $category = $categoryFactory->create()->load($categoryId);
        $catname = $category->getName();
        $catcount =$category->getProductCount();
        /*$catcount =$category->getProductCount();
        $procount = $catcount / $pageSize; 
        $procount1  = ceil($procount);*/
         
        

        $categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
        $categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');

        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection = $productCollection->addAttributeToSelect('*')->setStoreId($storeId)->load(); 

        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeId =$storeManager->getStore()->getStoreId();

        $categoryProducts = $category->getProductCollection()->addAttributeToSelect('*'); 
        $categoryProducts->setPageSize($pageSize);
        $categoryProducts->setCurPage($page);
        $totalPages=$categoryProducts->getLastPageNumber();

        /*
         $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 1;

        $newsCollection = $this->newscollectionFactory->create();
        $newsCollection->addFieldToFilter('is_active',1);
        $newsCollection->setOrder('title','ASC');
        $newsCollection->setPageSize($pageSize);
        $newsCollection->setCurPage($page);
        return $newsCollection;*/

        // echo "<pre>";print_r(get_class_methods($categoryProducts));exit();

        $productCollectionFactory = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToSort('entity_id','asc');
        $collection->addAttributeToSort('price','asc');
        $collection->addAttributeToSort('price','desc');
        $collection->setPageSize(10); // selecting only 5 products

        foreach ($collection as $product) {
               $productId = $product->getId();    
            
        }
       
        $filterTitle = array('textColor' =>'#666666',
          				        	 'font'=>'2',
          				        	 'fontSize'=>'15',
          				        	 'filterIconColor'=>'#666666');
        //Sorting Start

        $sort1 = array('id'=>'1',
                       'sort' =>'All',
                       'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=$categoryId&customerId=$customerId&curId=currencyId&langId=langId&productId=$productId");

        $sort2 = array('id'=>'2',
                       'sort' =>'Most Recent',
                       'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=$categoryId&customerId=$customerId&curId=currencyId&langId=langId&productId=$productId");

        $sort3 = array('id'=>'3',
                       'sort' =>'On Sale',
                       'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=$categoryId&customerId=$customerId&curId=currencyId&langId=langId&productId=$productId");

        $sort4 = array('id'=>'4',
                       'sort' =>'Price - Low to High',
                       'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=$categoryId&customerId=$customerId&curId=$currencyId&langId=langId&productId=$productId");  
        $sort5 = array('id'=>'5',
                       'sort' =>'Price - High to Low',
                       'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=$categoryId&customerId=$customerId&curId=$currencyId&langId=1&productId=$productId"); 

        $sortall = array($sort1,$sort2,$sort3,$sort4,$sort5);

        //Sorting End
 
        $title = array('textColor' =>'#666666',
                       'font'=>'2', 
                       'fontSize'=>'15'); 

        $sortText = array('Title'=>$title,
                          'sortSelectionDotColor'=>'#008BFF',
                          'dividerColor'=>'#e6e6e6',
                          'sortTitleData'=>$sortall); 

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

        $componentTitile = array('textColor' =>'#666666',
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
                                       'title'=>$componentTitile,
                                       'productName'=>$productName,
                                       'price'=>$productPrice,
                                       'discountPrice'=>$discountPrice); 

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

        /*Category wise products start*/
        foreach ($categoryProducts as $product) {

             $productId = $product->getId();

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

             $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
             $title = $product->getName();
             $price = $product->getPrice(); 

             if($configProduct->getTypeId() == "configurable"){
                    $price = $childPriceLowest;
                }else{
                    $price = $product->getPrice();
                } 
                
             $fullPrice = $price.' '.$currency;
             if($price == ''){
              $price = "0";
             }              
             $offerdPrice = $product->getSpecialPrice();
             if($offerdPrice == ''){
              $offerdPrice = "";
             }else{
              $offerdPrice = $offerdPrice.' '.$currency;
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
          		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
          		$storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
          		$storeId = $storeManager->getStore()->getStoreId();
          		$reviewFactory->getEntitySummary($product, $storeId);

          		$ratingSummary = $product->getRatingSummary()->getRatingSummary();
          		$reviewCount = $product->getRatingSummary()->getReviewsCount();
          		$ratingSummary1 = ($ratingSummary / 20); 

            	    $data1[] = array('id' =>$productId ,
    	                            'image'=>$image,
    	                            'type'=>'2',
    	                            'navigationFlag'=>'1',
    	                            'query'=>$baseUrl."ecommerceapi/productdetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$currencyId&recentId=",  
    	                            'title'=>$title,
    	                            'price'=>$fullPrice, 
    	                            'discountedPrice'=>$offerdPrice, 
    	                            'rating'=>"".$ratingSummary1."",  
    	                            'likeFlag'=>$likeFlag);  

                  $productListData = array('title' =>'','list'=>$data1);
          }
          $item = count($data1);
          $items = count($data1);
          if($items > '1'){
            $items = $items.' Items';
          }else{ 
            $items =  $items .' Item';
          }
 
          

/*Category wise products end*/

        if (empty($productListData)) {   
                                   $status = array('status' =>'OK',
                                                   'statusCode'=>300,
                                                   'message'=>'No data found!');
                                   echo $status1 = json_encode($status);
        } else {

        $componentProduct = array('componentId' =>'productList',
                                  'sequenceId'=>'2',
                                  'isActive'=>'1',
                                  'categoryId'=>''.$categoryId.'', 
                                  'productListUISettings'=>$productListUISettings,
                                  'productListData'=>$productListData);  


        //Banner Images start

        $connectionUrl  = $objectManager->get('Mageplaza\BannerSlider\Model\ResourceModel\Data\Collection');
        foreach ($connectionUrl as $k) 
        {
          $id = $k->getBannerId(); 
          $image = $k->getImage(); 
          $bannerall[] = array('id' =>$id ,
                               'image'=>$baseUrl."pub/media/mageplaza/bannerslider/banner/image/".$image,
                               'type'=>'1',
                               'navigationFlag'=>'1',
                               'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$categoryId&customerId=$customerId&langId=$langId&curId=$currencyId");
        }

        $banneralldata2 = array('list' =>$bannerall);
 
        $singleImageUISettings = array('isShadow' =>'1',
                                       'mediaType'=>'1',
                                       'backgroundMediaData'=>'#ffffff',
                                       'imageHeight'=>'900',
                                       'imageWidth'=>'1650'); 

        $componentBanner = array('componentId'=>'singleImage',
                                 'sequenceId'=>'3',
                                 'isActive'=>'1',
                                 'singleImageUISettings'=>$singleImageUISettings,
                                 'singleImageData'=>$banneralldata2); 
        ////Banner Images End

        /*Component Start*/

        $componentProduct2 = array($componentProduct,$componentBanner); 

         /*Component End*/
        
          if($item > '1'){
            $item = $item.' Items';
          }else{ 
            $item =  $item .' Item';
          }

          if($page > $totalPages){
         
                      $response = array('status' =>'Error','statusCode'=>300,'message'=>'No data found!');  

                      echo json_encode($response);
          } else {
                      $response = array('status' =>'OK',
                                        'statusCode'=>200,
                                        'isUpdateUISettingFlag'=>'0',
                                        'message'=>'Success',
                                        'id'=>$customerId,
                                        'langId'=>$storeId,
                                        'titleNavigationBar'=>$catname, 
                                        'noOfItems'=>"".$item."",  
                                        'repeatAfter'=>'4',
                                        'changeBanner'=>'1',
                                        'generalUISettings'=>$screenUISettings,
                                        'component'=>$componentProduct2);      
        
                      echo json_encode($response,JSON_UNESCAPED_SLASHES); 
          }
            
         
        
    }
  } 
}
   