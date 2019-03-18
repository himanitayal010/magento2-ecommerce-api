<?php 
namespace Magneto\Ecommerceapi\Controller\SingleItemOrderDetailsScreen;

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
        $customerId = $_REQUEST["customerId"];
        $langId = $_REQUEST["langId"];
        $curId= $_REQUEST["curId"]; 
        $generalUISettings = array(  'mediaType' =>'1',
                                     'backgroundMediaData'=>'#f6f6f6',
                                     'navDividerColor'=>'#DEDEDE',
                                     'orderIdBlockBackgroundColor'=>'#ffffff',
                                     'orderIdText'=> array('textColor' =>'#666666',
                                     'font'=>'1',
                                     'fontSize'=>'36px'));
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $currencysymbol = $objectManager->get('Magento\Directory\Model\Currency')->getCurrencySymbol();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $lastyear = date('Y-m-d', strtotime("-1 year"));
        $orderCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Collection');
        /*$orderData  = array();*/
        $orderData = $orderCollection->addAttributeToFilter('customer_id',$customerId)
                    ->addAttributeToFilter('status','pending') 
                    ->addAttributeToFilter('created_at', array('gteq'  => $lastyear))->load();
                    //echo "outside";
                    /*echo "hi";
        echo "<pre>";
        print_r($orderData->getData());
        die();*/
        if(!empty($orderData)){
            foreach ($orderData as $_order){
                    $oId =  $_order->getEntityId();
                    $orderIdText = "ORDER ID:".$oId."";
					$CreatedAt = date("M-d-y",strtotime($_order->getCreatedAt()));
                    $subText = $_order->getTotalQtyOrdered()." Items "." Bill Amount: ".$_order->getTotalPaid()."";
                    $shippingaddress = $_order->getShippingAddress()->getData();
                    $totalPrice = $_order->getGrandTotal(); 
                    $taxamount = $_order->getBaseTaxAmount();
                    $subtotalprice = $_order->getSubtotal();
                    $qty = $_order->gettotal_qty_ordered();

                            foreach ($_order->getAllVisibleItems() as $_item) {                    
                                    $itemId = $_item->getProductId(); 
                                    $productName = $_item->getName();
                                           /*$status = $_order->getStatus();*/
                                    $proImage = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$_item->getProduct()->getImage();
                                           $sku = $_item->getSku();
                                           $price1 = $_item->getPrice();
                                           if($price1 == ''){
                                            $price1 == "";
                                           }else{
                                            $price1 = $price1.' '.$currencysymbol;
                                           }
                                           $priceoffered = $_item->getSpecialPrice(); 
                                           if($priceoffered == ''){
                                            $priceoffered = "";
                                           }else{
                                            $priceoffered = $priceoffered.' '.$currencysymbol;
                                           }
                                           $additionalText =''/* $price1 - $priceoffered*/;
                                           if($priceoffered == ''){
                                            $additionalText = "";
                                           }else{
                                            $additionalText = 'You Save'.' '.$additionalText.' '.$currencysymbol;
                                           } 
                                $orderId = array('componentId' =>'orderId',
                                                 'sequenceId'=>'1',
                                                 'isActive'=>'1',
                                                 'orderIdUISettings'=> array('isShadow' =>'1',
                                                 'shadowColor'=>'#a4a4a4',
                                                 'backgroundColor'=>'#ffffff'),
                                                 'orderIdData'=>array('orderId' =>$orderIdText,'CreatedAt'=>$CreatedAt));

                                $purchasedProductall[] = array('id' =>$itemId, 
                                                                'image'=>$proImage,
                                                                'title'=>$productName,
                                                                'subTitle'=>$sku,
                                                                'price'=>$price1,
                                                                'discountPrice'=>$priceoffered,
                                                                'additionalText'=>$additionalText,
                                                                'qty'=>$qty);
                                
                                $purchasedProductData = array('list' =>$purchasedProductall);
                                
                                $purchasedProductUISettings= array('isShadow' =>'1',
                                                                    'mediaType'=>'1',
                                                                    'scratchLineWidth'=>'1',
																	'divBackgroundColor'=>'#ffffff',
                                                                    'backgroundMediaData'=>'#ffffff',
                                                                    'dividerColor'=>'#dedede',
                                                                    'productName'=>array('textColor' =>'#212121',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',		
                                                                    'fontSize'=>'42px'),
                                                                    'subTitle'=>array('textColor' =>'#666666',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'30px'),
                                                                    'price'=>array('textColor' =>'#666666',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'42px'),
                                                                    'productImage'=>array('isShadow' =>'1',
                                                                    'backgroundColor'=>'#ffffff'),
                                                                    'discountPrice'=>array('textColor' =>'#e6201a',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'42px'),
                                                                    'additionalText'=>array('textColor' =>'#3db75e',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'42px'),
                                                                    'buttonQty'=>array('textColor' =>'#666666',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'30px',
                                                                    'backgroundColor'=>'#ffffff',
                                                                    'isShadow'=>'1',
                                                                    'arrowColor'=>'#666666')); 

                                $purchasedProduct = array('componentId' =>'purchasedProduct',
                                                        'sequenceId'=>'1',
                                                        'isActive'=>'1',
                                                        'purchasedProductUISettings'=>$purchasedProductUISettings,
                                                        'purchasedProductData'=>$purchasedProductData); 

                                $priceDetailsUISetting = array('backgroundColor' =>'#ffffff',
                                                                'dividerColor'=>'#dedede',
                                                                'isShadow'=>'1',
                                                                'title'=>array('textColor' =>'#666666',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'42px'),
                                                                'leftTextDetails'=>array('textColor' =>'#666666',
                                                                    'font'=>'1',
                                                                    'fontSize'=>'36px'),
                                                                'rightTextDetails'=>array('textColor' =>'#666666',
                                                                    'font'=>'1',
                                                                    'fontSize'=>'36px'), 
                                                                'payableAmountText'=>array('textColor' =>'#212121',
                                                                    'font'=>'2',
																	'fontWeight'=>'600',
                                                                    'fontSize'=>'36px'));
                                $price =array('leftText' =>'Price (1 item)','rightText'=>$subtotalprice);

                                $tax =array('leftText' =>'Tax','rightText'=>$taxamount); 

                                $delivery =array('leftText' =>'Delivery','rightText'=>'FREE');

                                $option = array($price,$tax,$delivery);

                                $priceDetailsData = array('title' =>'Price Details',
                                                            'payableAmountLable'=>'Amount Payable',
                                                            'payableAmount'=>$totalPrice,
                                                            'listData'=>$option);

                                $priceDetails = array('componentId' =>'priceDetails',
                                                        'sequenceId'=>'1',
                                                        'isActive'=>'1', 
                                                        'priceDetailsUISetting'=>$priceDetailsUISetting,
                                                        'priceDetailsData'=>$priceDetailsData);

                                $componentall = array($orderId,$purchasedProduct,$priceDetails);
                                $status = array('status' =>'OK',
                                                'statusCode'=>200,
                                                'message'=>'Success',
                                                'isUpdateUISettingFlag'=>'0',
                                                'generalUISettings'=>$generalUISettings,'component'=>$componentall);

                                 echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
                                /*echo "<pre>";
                                print_r($componentall);*/
                               
                                  
                            } 
                
            }
        }
        /*else{
            $one = array('status' =>'OK',
                            'statusCode'=>300,
                            'message'=>'No Data Found!');

            echo $onetwo = json_encode($one);
            echo "hi";  
        }*/
    

    }    
}