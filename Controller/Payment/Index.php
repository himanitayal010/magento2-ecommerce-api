<?php 
namespace Magneto\Ecommerceapi\Controller\Payment;

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
  
  public function execute() { 

    /*ORDER DETAIL DATA*/

        $lastyear = date('Y-m-d', strtotime("-1 year"));
        $customerId = $_POST["customerId"]; 
        if($customerId ==''){
          $response  = array('status' =>'Error',
                             'statusCode'=>300,
                             'message'=>'Please Enter customerId!');
          echo json_encode($response);
        }else{
        $langId = $_POST["langId"];
        $currencyId = $_POST["curId"];    

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();

        $currencysymbol = $objectManager->get('Magento\Directory\Model\Currency')->getCurrencySymbol();

        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $cart  = $objectManager->get('\Magento\Checkout\Model\Cart');
        $quote = $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId); 
        $quoteItems = $quote->getAllVisibleItems(); 
        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $grandTotal = $quote->getGrandTotal();   // Total amount
        $totalItems = $quote->getItemsCount();  // no of items in cart
        $totalPice = $quote->getSubtotal();     // cart price without tax
        $grandTotal = $quote->getGrandTotal();  //grand total
        $totalQuantity = $quote->getItemsQty(); // total quanity
        
        $shipping_amount=$quote->getShippingAddress()->getShippingAmount(); // Shipping amount

        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $orderCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Collection');
        $orderData = $orderCollection->addAttributeToFilter('customer_id',$customerId)
        ->addAttributeToFilter('status','complete')
        ->addAttributeToFilter('created_at', array('gteq'  => $lastyear))->load();    

        $shipping_type = $cart->getQuote()->getShippingAddress()->getShippingMethod();

        $generalUISettings = array('mediaType' =>'1',
                                   'backgroundMediaData'=>'#f6f6f6',
                                   'navDividerColor'=>'#DEDEDE',
                                   'navDividerColorForButtons'=>'#DEDEDE',
         'priceBottomLabel'=>array('textColor' =>'#666666',
                                   'font'=>'2',
                                   'fontSize'=>'22',
                                   'borderPixel'=>'2',
                                   'borderColor'=>'#666666',
                                   'backgroundColor'=>'#ffffff'),
     'continueBottomButton'=>array('textColor' =>'#ffffff',
                                   'font'=>'2',
                                   'fontSize'=>'18',
                                   'borderPixel'=>'2',
                                   'borderColor'=>'#666666',
                                   'backgroundColor'=>'#3db75e'));

        $paymentData1 = array('id'=>'1','title' =>'Credimax','cardImage'=>$baseUrl."pub/media/brand/d/o/card.png"); 

        $paymentData2 = array('id'=>'2','title' =>'Benefit','cardImage'=>$baseUrl."pub/media/brand/d/o/card2.png"); 

        $paymentData3 = array('id'=>'3','title' =>'Cash On Delivery','cardImage'=>$baseUrl."pub/media/brand/d/o/Cash-on-delivery.png");   

        $paymentData = array($paymentData1,$paymentData2,$paymentData3);
        $paymentData1 = array('list'=>$paymentData);

        $selectCardColor=   array('textColor' =>'#666666','font'=>'2','fontSize'=>'16');

        $paymentUISetting =  array('backgroundColor' =>'#ffffff',
                                   'unSelectCardColor'=>'#dedede',
                                   'selectCardColor' =>'#dedede',
                                   'dividerColor'=>'#dedede',
                                   'isShadow' =>'1',
                                   'shadowColor'=>'#a4a4a4',
                                   'title'=> $selectCardColor);  

        $paymentdetails = array('componentId' =>'payment',
                                'sequenceId'=>'1',
                                'isActive'=>'1',
                                'paymentUISetting'=>$paymentUISetting,
                                'paymentData'=>$paymentData1);  


        $priceDetailsUISetting = array('backgroundColor' =>'#ffffff',
                                       'dividerColor'=>'#dedede',
                                       'isShadow' =>'1',
                                       'shadowColor'=>'#a4a4a4',
                        'title'=>array('textColor' =>'#666666',
                                       'font'=>'2',
                                       'fontSize'=>'16'),
              'leftTextDetails'=>array('textColor' =>'#666666',
                                       'font'=>'1',
                                       'fontSize'=>'14'),
             'rightTextDetails'=>array('textColor' =>'#666666',
                                       'font'=>'1',
                                       'fontSize'=>'14'),
             'rightTextDetails'=>array('textColor' =>'#212121',
                                       'font'=>'2',
                                       'fontSize'=>'16'),
            'payableAmountText'=>array('textColor' =>'#212121',
                                       'font'=>'2',
                                       'fontSize'=>'16')); 

          $data1 = array('leftText' =>'Price '.($totalItems).' Item', 'rightText'=>$totalPice.' '.$currencysymbol); 
          $data2 = array('leftText' =>'Tax','rightText'=>$shipping_amount.' '.$currencysymbol); 
          $data3 = array('leftText' =>'Delivery','rightText'=>'FREE');

          $data = array($data1,$data2,$data3); 

          $priceDetailsData = array('title' =>'Price Details',
                                    'payableAmountLable'=>'Amount Payable',
                                    'payableAmount'=>$grandTotal.' '.$currencysymbol,
                                    'listData'=>$data);

          $pricedetails = array('componentId' =>'priceDetails',
                                'sequenceId'=>'1',
                                'isActive'=>'1',
                                'priceDetailsUISetting'=>$priceDetailsUISetting,
                                'priceDetailsData'=>$priceDetailsData);



          $component1 = array($paymentdetails,$pricedetails); 

          $response = array('status' =>'OK',
                            'statusCode'=>200,
                            'message'=>'Success', 
                            'isUpdateUISettingFlag'=>'0',
                            'generalUISettings'=>$generalUISettings,
                            'component'=>$component1);   

          echo json_encode($response,JSON_UNESCAPED_SLASHES);
      }
  } 
}
