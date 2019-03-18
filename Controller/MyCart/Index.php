<?php 
namespace Magneto\Ecommerceapi\Controller\MyCart;

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
  public function execute()  {   


            $generalUISettings = array('mediaType' =>'1',
                                       'navDividerColor'=>'#DEDEDE',
                                       'backgroundMediaData'=>'#f6f6f6',
              'checkoutButton'=> array('textColor' =>'#ffffff',
                                       'font'=>'2',
                                       'fontSize'=>'18',
                                       'borderPixel'=>'2',
                                       'backgroundColor'=>'#3db75e',
                                       'borderColor'=>'#DEDEDE'),
                  'totalLable'=> array('textColor' =>'#666666',
                                        'font'=>'2',
                                        'fontSize'=>'18',
                                        'borderPixel'=>'2',
                                        'borderColor'=>'#666666',
                                        'backgroundColor'=>'#ffffff'));    


            $priceDetailsUISetting = array('backgroundColor' =>'#ffffff',
                                           'dividerColor'=>'#dedede',
                                           'isShadow'=>'1',
                            'title'=>array('textColor' =>'#666666',
                                           'font'=>'2',
                                           'fontSize'=>'16'),
                  'leftTextDetails'=>array('textColor' =>'#666666',
                                           'font'=>'1',
                                           'fontSize'=>'14'),
                 'rightTextDetails'=>array('textColor' =>'#666666',
                                           'font'=>'1',
                                           'fontSize'=>'14'), 
                'payableAmountText'=>array('textColor' =>'#212121',
                                           'font'=>'2',
                                           'fontSize'=>'16'));       
    
            $customerId = $_REQUEST["customerId"]; 
            $langId = $_REQUEST["langId"];
            $currencyId = $_REQUEST["curId"];
            $installationId = $_POST["installationId"];
            $warrantyId = $_POST["warrantyId"];
            $storeId = $langId; 

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
            $activeShipping = $objectManager->create('Magento\Shipping\Model\Config')->getActiveCarriers();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
            $baseUrl= $storeManager->getStore()->getBaseUrl();
            $currencysymbol = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
            $currency = $currencysymbol->getStore()->getCurrentCurrencyCode();
 

            /*-------------------Product Extend Warranty start----------------------*/

            /*$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);

            if($warrantyId ==1) {
               echo  $price = $product->getData('extend_warranty_coverage_for_1') + $product->getPrice(); exit();

            } elseif ($warrantyId == 2) {
                $price = $product->getData('extend_warranty_coverage_for_2') + $product->getPrice();

            } elseif ($warrantyId == 3) {
                $price = $product->getData('extend_warranty_coverage_for_3') + $product->getPrice();

            } else {
                $price = $product->getPrice();
            }


            if($installationId ==1) {
                $price1 = $product->getData('installation'); 

            } else {
               
            }*/

            /*-------------------Product Extend Warranty End----------------------*/
            $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
            $customer_check->load($customerId);

        if (!$customer_check->getId()){ //For guest user

          $productId = $_POST["productId"];
          $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
          if($product->getId()){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->setStoreId($storeId);
          $collection = $productCollection->addAttributeToSelect('*')
          ->addFieldToFilter('entity_id', array('in' => $productId));  

          $purchasedProductUISettings= array('isShadow' =>'1',
                                             'mediaType'=>'1',
                                             'scratchLineWidth'=>'1',
                                             'backgroundMediaData'=>'#ffffff',
                                             'dividerColor'=>'#dedede',
                      'productName' => array('textColor' =>'#212121',
                                             'font'=>'2', 
                                             'fontSize'=>'16'),
                         'subTitle' => array('textColor' =>'#666666',
                                             'font'=>'2',
                                             'fontSize'=>'10'),
                            'price' => array('textColor' =>'#666666',
                                             'font'=>'2',
                                             'fontSize'=>'14'),
                     'productImage' => array('isShadow' =>'1',
                                             'backgroundColor'=>'#ffffff'),
                    'discountPrice' => array('textColor' =>'#e6201a',
                                             'font'=>'2',
                                             'fontSize'=>'14'),
                   'additionalText' => array('textColor' =>'#3db75e',
                                             'font'=>'2',
                                             'fontSize'=>'14'),
                     'buttonRemove' => array('textColor' =>'#9c9c9c',
                                             'font'=>'2',
                                             'fontSize'=>'14'),
              'buttonAddToWishList' => array('textColor' =>'#666666',
                                             'font'=>'2',
                                             'fontSize'=>'14'), 
                        'buttonQty' => array('textColor' =>'#666666',
                                             'font'=>'2',
                                             'fontSize'=>'12',
                                             'backgroundColor'=>'#ffffff',
                                             'isShadow'=>'1',
                                             'arrowColor'=>'#666666'));     

          foreach($collection as $product)
          { 
            $id =$product->getId();
            $sku = $product->getSku();
            $qty = '1';
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
            }else{
                 $price = $price;
            }

          $offeredPrice = $product->getSpecialPrice();
          if ($offeredPrice == '') {
                  $offeredPrice = "0"; 
          }else{
            $offeredPrice = $offeredPrice; 
          }
          /*echo $price;
          echo 'hello';
          echo $offeredPrice;*/

          $additonalText = $price - $offeredPrice; 
          if($offeredPrice == '0'){
           $additonalText ="";
         }else{
           $additonalText ="";
         }

         $image = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
         $product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
          $qty1 = array('count' =>'1');
                      $qty2 = array('count' =>'2'); 
                      $qty3 = array('count' =>'3');
                      $qty4 = array('count' =>'4');
                      $qty5 = array('count' =>'5');  
                      $qty6 = array('count' =>'More');
        $qty1 = array($qty1,$qty2,$qty3,$qty4,$qty5,$qty6); 
         $purchasedProductall[] = array('id' =>$id, 
                                        'image'=>$image,
                                        'title'=>$title,
                                        'subtitle'=>$sku,
                                        'selectedQuantity'=>$qty, 
                                        'price'=>$price.' '.$currency, 
                                        'discountedPrice'=>"", 
                                        'additionalText'=>$additonalText, 
                                        'qty'=>$qty1);  
          $key = 'price';
          $sum = array_sum(array_column($purchasedProductall,$key));   
 
       }
       $items = count($purchasedProductall);

       $purchasedProductData = array('list' =>$purchasedProductall);

       $componentProduct = array('componentId' =>'purchasedProduct',
                                 'sequenceId'=>'1',
                                 'isActive'=>'1',
                                 'purchasedProductUISettings'=>$purchasedProductUISettings,
                                 'purchasedProductData'=>$purchasedProductData);

       $promoCodeUISettings = array('isShadow' =>'1',
                                    'mediaType'=>'1', 
                                    'backgroundMediaData'=>'#ffffff',
                                    'arrowColor'=>'#666666',
                      'text'=>array('textColor' =>'#666666',
                                    'font'=>'2',
                                    'fontSize'=>'14'));  

       $promoCode = array('componentId' =>'promoCode',
                          'sequenceId'=>'1',
                          'isActive'=>'1',
                          'promoCodeUISettings'=>$promoCodeUISettings);

       $percentage = 5;
       $shipping_amount =($percentage / 100) * $sum; 
       $price =array('leftText' =>'Price '. ($items).' Items','rightText'=>"".$sum." ".$currency); 

       $tax =array('leftText' =>'Tax','rightText'=>$shipping_amount.' '.$currency); 

       $delivery =array('leftText' =>'Delivery','rightText'=>'Free');

       $option = array($price,$tax,$delivery); 

       $totalPrice = /*$price1 + $shipping_amount;*/ "";

       $priceDetailsData = array('title' =>'PriceDetails', 
                                 'payableAmountLable'=>'Amount Payable',
                                 'payableAmount'=>''.$sum.' '.$currency,    
                                 'listData'=>$option); 

       $priceDetails = array('componentId' =>'priceDetails',
        'sequenceId'=>'1',
        'isActive'=>'1', 
        'priceDetailsUISetting'=>$priceDetailsUISetting,
        'priceDetailsData'=>$priceDetailsData);

       $component = array($componentProduct,$promoCode,$priceDetails);

       $response = array('status' =>'OK', 
         'statusCode'=>200,
         'langId'=>$storeId,
         'message'=>'Success', 
         'isUpdateUISettingFlag'=>'0',
         'generalUISettings'=>$generalUISettings,
         'component'=>$component);  

       echo json_encode($response,JSON_UNESCAPED_SLASHES);
            } else {
                    $response = array('status' =>'Error',
                                      'statusCode'=>300,
                                      'message'=>'Poduct is not found in catalog!'); 

                              echo json_encode($response,JSON_UNESCAPED_SLASHES);
            }
          }else{ // For registered user

            $addressRepository = $objectManager->get('\Magento\Customer\Api\AddressRepositoryInterface');
            $customerRepository =    $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');

            $customer = $customerRepository->getById($customerId);

            $shippingAddressId = $customer->getDefaultShipping(); 

            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $baseUrl= $storeManager->getStore()->getBaseUrl();

            $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
            $cart  = $objectManager->get('\Magento\Checkout\Model\Cart');

            $quote = $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId)->setStoreId($storeId); // quote details by customerId
            $selectPromocode =$quote->getCouponCode();
            if($selectPromocode == ''){
                $selectPromocode = "";
            }
 
            $applyPromocode = array('list' =>$selectPromocode);
            $quoteItems = $quote->getAllVisibleItems(); // No of Items in cart

            $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
            $grandTotal = $quote->getGrandTotal();   //Grand Total of cart

            if($grandTotal == ''){ 
              $grandTotal = ""; 
            }else{
              $grandTotal = $grandTotal.' '.$currency;
            }

                      $totalQuantity = $quote->getItemsCount(); //No of items in cart

                      $customerAddress = array();
                      $qty1 = array('count' =>'1');
                      $qty2 = array('count' =>'2'); 
                      $qty3 = array('count' =>'3');
                      $qty4 = array('count' =>'4');
                      $qty5 = array('count' =>'5');  
                      $qty6 = array('count' =>'More'); 

                      $shipping_amount=$quote->getShippingAddress()->getShippingAmount();
                      $shipping_type = $quote->getShippingAddress()->getShippingMethod();
                      $qty1 = array($qty1,$qty2,$qty3,$qty4,$qty5,$qty6); 

                      /*---------------Quote Items with customer Id Start------------------------*/

                      foreach($quoteItems as $oneItem){ 

                          $tax = $oneItem->getTaxAmount();
                          $itemId = $oneItem->getProductId();
                          $productName = $oneItem->getName();
                          $sku = $oneItem->getSku();
                          $qty = $oneItem->getQty();
                          $price11 = $oneItem->getPrice();

                          if($price11 == ''){
                            $price11 = "";
                          }else{
                            $price11 = $price11;
                          }

                          /*$price1 = $price11 * $qty;*/
                          $discountedPrice = $oneItem->getSpecialPrice();

                          if($discountedPrice == ''){
                            $discountedPrice = "";
                          }else{
                            $discountedPrice = $discountedPrice; 
                          }

                          $subtotalprice = $oneItem->getBaseTaxAmount();
                          $product = $objectManager->create('Magento\Catalog\Model\Product')->load($itemId);
                          $proImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();  
                          $subtotalprice = $oneItem->getBaseTaxAmount();

                          $additonalText  = '' /*$price11 - $discountedPrice*/;

                          if ($discountedPrice == '') {
                                    $discountedPrice = "";
                                    $additonalTextMessage = "";
                          }else{
                                    $additonalTextMessage = 'You Save' .$additonalText.' '.$currency.''; 
                          }

                          $purchasedProductall[] = array('id' =>$itemId, 
                                                         'image'=>$proImage,
                                                         'title'=>$productName,
                                                         'subtitle'=>$sku,
                                                         'selectedQuantity'=>''.$qty.'', 
                                                         'price'=>$price11.' '.$currency, 
                                                         'discountedPrice'=>$discountedPrice, 
                                                         'additionalText'=>$additonalTextMessage, 
                                                         'qty'=>$qty1);      
                      }  

        /*Quote Items with customer Id End*/  

              if(empty($purchasedProductall)){

                    $response  = array('status' =>'OK' ,
                                       'statusCode'=>300,
                                       'message'=>'No data Found!');

                    echo json_encode($response);

              }else{
                          $purchasedProductData = array('list' =>$purchasedProductall);
                          $purchasedProductUISettings= array('isShadow' =>'1',
                                                             'mediaType'=>'1',
                                                             'scratchLineWidth'=>'1',
                                                             'backgroundMediaData'=>'#ffffff',
                                                             'dividerColor'=>'#dedede',
                                      'productName' => array('textColor' =>'#212121',
                                                             'font'=>'2', 
                                                             'fontSize'=>'16'),
                                         'subTitle' => array('textColor' =>'#666666',
                                                             'font'=>'2',
                                                             'fontSize'=>'10'),
                                            'price' => array('textColor' =>'#666666',
                                                             'font'=>'2',
                                                             'fontSize'=>'14'),
                                     'productImage' => array('isShadow' =>'1','backgroundColor'=>'#ffffff'),
                                    'discountPrice' => array('textColor' =>'#e6201a',
                                                             'font'=>'2',
                                                             'fontSize'=>'14'),
                                   'additionalText' => array('textColor' =>'#3db75e',
                                                             'font'=>'2',
                                                             'fontSize'=>'14'),
                                     'buttonRemove' => array('textColor' =>'#9c9c9c',
                                                             'font'=>'2',
                                                             'fontSize'=>'14'),
                              'buttonAddToWishList' => array('textColor' =>'#666666',
                                                             'font'=>'2',
                                                             'fontSize'=>'14'), 
                                        'buttonQty' => array('textColor' =>'#666666',
                                                             'font'=>'2',
                                                             'fontSize'=>'12',
                                                             'backgroundColor'=>'#ffffff',
                                                             'isShadow'=>'1',
                                                             'arrowColor'=>'#666666'));   

                          $purchasedProduct = array('componentId' =>'purchasedProduct',
                                                    'sequenceId'=>'1',
                                                    'isActive'=>'1',
                                                    'purchasedProductUISettings'=>$purchasedProductUISettings,
                                                    'purchasedProductData'=>$purchasedProductData); 

                          /*Promo code Start*/
                          $promoCodeUISettings = array('isShadow' =>'1',
                                                       'mediaType'=>'1', 
                                                       'backgroundMediaData'=>'#ffffff',
                                                       'arrowColor'=>'#666666',
                                                       'text'=>array('textColor' =>'#666666',
                                                         'font'=>'2',
                                                         'fontSize'=>'14')); 

                          $promoCode = array('componentId' =>'promoCode',
                                             'sequenceId'=>'1',
                                             'isActive'=>'1',
                                             'promoCodeUISettings'=>$promoCodeUISettings,
                                             'promoCodeData'=>$applyPromocode);

                          /*Promo code End*/

                          /*Third Component Price Details Start*/

                          $activeCarriers = $objectManager->create('\Magento\Shipping\Model\Config')->getActiveCarriers($store);
                            $shippingMethodsArray = array();
                            foreach ($activeCarriers as $shippigCode => $shippingModel) {
                                      $shippingTitle = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface')->getValue('carriers/'.$shippigCode.'/title');
                                      $shippingMethodsArray[$shippigCode] = array('label' => $shippingTitle,
                                                                                  'value' => $shippigCode);
                            }
                            
                          $price =array('leftText' =>'Price '. ($totalQuantity).' Items','rightText'=>$grandTotal); 

                          $tax =array('leftText' =>'Tax','rightText'=>$tax.' '.$currency); 

                          $delivery =array('leftText' =>$shippingTitle,'rightText'=>$shippigCode);

                          $option = array($price,$tax,$delivery); 

                          $priceDetailsData = array('title' =>'PriceDetails', 
                                                    'payableAmountLable'=>'Amount Payable',
                                                    'payableAmount'=>$grandTotal,    
                                                    'listData'=>$option); 

                          $priceDetails = array('componentId' =>'priceDetails',
                                                'sequenceId'=>'1',
                                                'isActive'=>'1', 
                                                'priceDetailsUISetting'=>$priceDetailsUISetting,
                                                'priceDetailsData'=>$priceDetailsData);

                          /*---------------Third Component Price Details END-------------------*/

                          /*-------------------Customer Address Component Start---------------------*/
                          $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
                          $customerAddress = array();

                                foreach ($customerObj->getAddresses() as $address){ //customer address
                                  $customerAddress[] = $address->toArray();
                                }

                                foreach ($customerAddress as $customerAddres){ 

                                  $addressId = $customerAddres['entity_id'];

                                  if($shippingAddressId == $addressId){

                                   $street = $customerAddres['street'];
                                   $city =  $customerAddres['city'];
                                   $postCode = $customerAddres['postcode'];
                                   $region = $customerAddres['region'];
                                   $firstname = $customerAddres['firstname'];
                                   $lastname = $customerAddres['lastname'];
                                   $country = $customerAddres['country_id'];
                                   $telephone = $customerAddres['telephone'];
                                   $firstname1 = array('text' => $firstname);
                                   $lastname1 = array('text' => $lastname);
                                   $street1 = array('text' => $street);
                                   $region1 = array('text' => $region);
                                   $city1 = array('text' => $city);
                                   $country1 = array('text' => $country);
                                   $postCode1 = array('text' => $postCode);
                                   $telephone1 = array('text' => $telephone); 
                                   $addressdataAll = array($firstname1,$lastname1,$street1,$region1,$city1,$country1,$postCode1,$telephone1);
                                   $addressdataAll1 = array('address'=>$addressdataAll);
                                   $addressdataAll2 = array($addressdataAll1);
                                 }
                               }

                               if(empty($addressdataAll2)){
                                $addressdataAll2  = array(); 
                              } 

                              $addressData = array('list' =>$addressdataAll2);

                              $address = array('componentId' =>'address', 
                                               'sequenceId'=>'1',
                                               'isActive'=>'1', 
                                               'addressUISettings'=>array(),
                                               'addressData'=>$addressData); 

                              /*------------------Customer Address Component END-----------------------*/

                              $componentall = array($address,$purchasedProduct,$promoCode,$priceDetails);
                              $response = array('status' =>'OK',
                                                'statusCode'=>200,
                                                'isUpdateUISettingFlag'=>'0',
                                                'message'=>'Success',
                                                'generalUISettings'=>$generalUISettings,
                                                'component'=>$componentall); 

                              echo json_encode($response,JSON_UNESCAPED_SLASHES); 
              }
      }       
  }        
} 