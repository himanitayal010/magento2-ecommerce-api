<?php
namespace Magneto\EcommerceApi\Controller\CheckQuantity;

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
        $stockItem = $objectManager->create('\Magento\CatalogInventory\Model\Stock\StockItemRepository');

        $website_id = $storeManager->getWebsite()->getWebsiteId(); 
       // $customerId = 2;
        $customerId = $_REQUEST["customerId"];
        $productId =$_REQUEST["productId"];
        $qty = $_REQUEST["qty"];
        $productQty = $stockItem->get($productId)->getQty();
        if($productQty >= $qty){
            if($customerId){
                $quote = $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer($customerId);
                foreach($quote->getAllVisibleItems() as $itemq){
                    $itemId = $itemq->getProductId();
                     if($itemId == $productId){
                                $itemq->setQty($qty);
                                
                    }
                }       
                
                    $quote->collectTotals()->save();
                    $response  = array('status' =>'OK',
                                       'statusCode'=>200,
                                       'message'=>'Quantity Updated');
                    echo json_encode($response);    
            }else{
                    if($productQty >= $qty){
                        $response  = array('status' =>'OK',
                                           'statusCode'=>200,
                                           'message'=>'Quanity Available in inventory!');
                        echo json_encode($response);
                    }else{
                        $response  = array('status' =>'Error',
                                           'statusCode'=>300,
                                           'message'=>'Qunatity not available in Inventory!');
                        echo json_encode($response);
                    }
            }
        }else{
            $response  = array('status' =>'Error',
                               'statusCode'=>300,
                               'message'=>'Qunatity not available in Inventory!');
            echo json_encode($response);
        }
    } 

}