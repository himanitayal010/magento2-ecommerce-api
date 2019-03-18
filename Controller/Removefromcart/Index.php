<?php 
namespace Magneto\Ecommerceapi\Controller\Removefromcart;

class Index extends \Magento\Framework\App\Action\Action 
{
    public function execute()  
    { 
 			

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $quote= $objectManager->create('Magento\Quote\Model\Quote')->loadByCustomer(1);
        $quoteId = $cart->getQuote()->getId();
		$quoteModel = $objectManager->create('Magento\Quote\Model\Quote');

		$itemsCollection = $cart->getQuote()->getItemsCollection();
		echo "hi";
		$itemsVisible = $cart->getQuote()->getAllVisibleItems();
		$items = $cart->getQuote()->getAllItems();

		foreach($items as $item)  
		{	
	    	$itemId = 5;
	    	if($itemId == "5") 
	    	{
	        $quoteItem = $quoteModel->load($quoteId);

	        $quoteItem->delete();

	        
    		} 
		} 
		/*echo('');*/ 
        
		$status = array('status' =>'OK',
			            'statusCode'=>200,
			            'message'=>'Your product has been removed from cart.'); 
   		
   		echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
    } 
} 









