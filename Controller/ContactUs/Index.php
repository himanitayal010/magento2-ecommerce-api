<?php 
namespace Magneto\Ecommerceapi\Controller\ContactUs;

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
        public function execute()  {    


        $customerId = $_REQUEST["customerId"];
        $currencyId = $_REQUEST["curId"];
        $langId = $_REQUEST["langId"];
 
        $contact = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
        
        $contactUsUISettings  = array('mediaType'=>'1',
                                      'backgroundMediaData'=>'#ffffff',
                       'title'=>array('textColor'=>'#212121',
                                      'font'=>'2',
                    'fontWeight'=>'600',
                                      'fontSize'=>'36px'),
                    'subTitle'=>array('textColor'=>'#666666',
                    'font'=>'1',
                                      'fontSize'=>'36px'));

        $contactUsContent = array('description'=>$contact); 
        $contactData = array($contactUsContent); 

        //$component  = array($contactUsContent);


        $component[] = array('componentId' =>'contactUs',
                             'sequenceId'=>'1',
                             'isActive'=>'1',
                             'contactUsUISettings'=>$contactUsUISettings,
                             'componentData'=>$contactData); 

        $response = array('status' =>'OK',
                          'statusCode'=>200,
                          'message'=>'Success',
                          'component'=>$component);      
        
        echo json_encode($response,JSON_UNESCAPED_SLASHES); 
    } 
}  

/* "componentId": "contactUs",
      "contactUsUISettings": {
        "mediaType": "1",
        "backgroundMediaData": "#ffffff",
        "title": {
          "textColor": "#212121",
          "font": "2",
          "fontSize": "16"
        },
        "subTitle": {
          "textColor": "#212121",
          "font": "2",
          "fontSize": "16"
        }*/