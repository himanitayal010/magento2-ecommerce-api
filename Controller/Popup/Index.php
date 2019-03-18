<?php 
namespace Magneto\Ecommerceapi\Controller\Popup; 

class Index extends \Magento\Framework\App\Action\Action
{
	public function execute() 
    { 

         

        
        $generalUISettings = array(''=>'');
        $status = array('status' =>'OK',
                'statusCode'=>200, 
                'message'=>'Success'/*,
                'generalUISettings'=>$generalUISettings, 
                'component'=>$component1*/); 
            echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);  
    } 
}
