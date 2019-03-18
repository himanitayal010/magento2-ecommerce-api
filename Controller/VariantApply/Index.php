<?php 
namespace Magneto\Ecommerceapi\Controller\VariantApply;

class Index extends \Magento\Framework\App\Action\Action 
{
    public function execute()  
    { 
      $query = ''; 
      $status = array('status' =>'OK', 
                      'statusCode'=>200, 
                      'message'=>'Success',
                      'query'=>'',
            /*'component'=>$component1*/);  

       // echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
      $product_ids = 4;
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $product_data = $objectManager->create('Magento\Catalog\Model\Product')->load($product_ids);
                    echo $nrv = $product_data->getData('product_url'); 


    } 
   
}
