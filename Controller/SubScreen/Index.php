<?php
namespace Magneto\Ecommerceapi\Controller\SubScreen;
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
    public function execute() {

    		$customerId = $_REQUEST["customerId"];
            $langId = $_REQUEST["langId"];
            $currencyId=$_REQUEST["curId"];
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $baseUrl= $storeManager->getStore()->getBaseUrl();
            $subCategoryUISettings = array('isShadow' =>'1',
        								   'shadowColor'=>'#a4a4a4',
        								   'mediaType'=>'1',
        								   'backgroundMediaData'=>'#ffffff',
        								   'imageHeight'=>'352',
        								   'imageWidth'=>'1080',
        				    'title'=>array('textColor'=>'#666666',
        								   'font'=>'1',
        								   'fontSize'=>'16'),
        				 'subTitle'=>array('textColor'=>'#666666',
        								   'font'=>'1',
        								   'fontSize'=>'12'));  
            $banner1 = array('title' =>'Homeware',
							 'subTitle'=>'Homeware',
                             'imageHeight'=>'352',
                             'imageWidth'=>'1080',
							 'image'=>$baseUrl."pub/media/catalog/category/Homeware_1.jpg",
							 'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=115&customerId=$customerId&langId=$langId&curId=$currencyId", 
							 'navigationFlag'=>'1', 
							 'type'=>'1');
            $banner2 = array('title' =>'Electronics',
							 'subTitle'=>'Electronics',
                             'imageHeight'=>'352',
                             'imageWidth'=>'1080',
							 'image'=>$baseUrl."pub/media/catalog/category/Elecronics.jpg",
							 'query'=>$baseUrl."ecommerceapi/ProductList?categoryId=4&customerId=$customerId&langId=$langId&curId=$currencyId",
							 'navigationFlag'=>'1',
							 'type'=>'1');
            $banner3 = array('title' =>'FMCG',
							 'subTitle'=>'FMCG',
                             'imageHeight'=>'352',
                             'imageWidth'=>'1080',
							 'image'=>$baseUrl."pub/media/catalog/category/FMCG.jpg",
							 'query'=>$baseUrl."ProductList?categoryId=5&customerId=$customerId&langId=$langId&curId=$currencyId",
							 'navigationFlag'=>'1',
							 'type'=>'1');
            $banner4 = array('title' =>'Cosmetics',
							 'subTitle'=>'Cosmetics',
                             'imageHeight'=>'352',
                             'imageWidth'=>'1080', 
							 'image'=>$baseUrl."pub/media/catalog/category/Cosmetics.jpg",
							 'query'=>$baseUrl."/productdetail?productId=2&customerId=$customerId&langId=$langId&curId=$currencyId", 
							 'navigationFlag'=>'1',
							 'type'=>'2'); 


            $bannerData = array($banner1,$banner2,$banner3,$banner4);
            $subCategoryData = array('list'=>$bannerData); 
            $component[] = array('componentId'=>'subCategory',
        					   'sequenceId'=>'1',
        					   'isActive'=>'1',
        					   'subCategoryUISettings'=>$subCategoryUISettings,
        					   'subCategoryData'=>$subCategoryData);


             $generalUISettings = array('mediaType' =>'1',
                                        'backgroundMediaData'=>'#f6f6f6',
                                        'navDividerColor'=>'#DEDEDE');

    	       $response = array('status' =>'OK',
                                 'statusCode'=>200,
                                 'message'=>'Success',
                                 'langId'=>$langId,
                                 'appUpdateFlag'=>'0',
                                 'notificationFlag'=>'0',
                                 'appUnderMaintainenceFlag'=>'0',
                                 'generalUISettings'=>$generalUISettings,
                                 'component'=>$component);

    	      echo json_encode($response,JSON_UNESCAPED_SLASHES);
    }

}
