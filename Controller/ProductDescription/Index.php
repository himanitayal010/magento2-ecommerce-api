<?php 
namespace Magneto\Ecommerceapi\Controller\ProductDescription; 
 
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
    	  $customerId = $_POST["customerId"];
        $langId = $_POST["langId"];
        $currencyId = $_POST["curId"];
        $productId = $_POST["productId"];

        /*$productId = 2;*/
       
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); 
        $productInfo = $product->getDescription();
        $productInfo = str_ireplace('<p>','',$productInfo);
        $productInfo=str_ireplace('</p>','',$productInfo);
        $productInfo = str_ireplace('<br />','',$productInfo);
        $productdescriptionData = array('description'=>$productInfo);

          $attributes = $product->getAttributes();
         //$eavModel = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute')->getData();
         //echo "<pre>"; print_r(get_class_methods($eavModel)); exit();
          $productcollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->addAttributeToSelect('*'); 
          foreach ($productcollection as $collection) {
               /*echo "<pre>";
               print_r($collection->getData());*/
                $warranty = $collection->getData('warranty');
                $featured = $collection->getData('is_featured');
                $size = $collection->getData('display_size');
                if($size == ''){
                  $size = '0';
                }

                $attributes = array('warranty'=>$warranty,
                                    'isFeatured'=>$featured,
                                    'size'=>$size);
                $attribute  = array($attributes);

           } 

          /*foreach ($attributes as $attribute) { 
              if(!is_null($attribute->getFrontendLabel()) && ((string)$attribute->getFrontend()->getValue($product) != '')){
                  $attributeLabel = $attribute->getFrontendLabel();
                  $value = $attribute->getFrontend()->getValue($product);         
                  echo $attributeLabel . '-' . $value; echo "<br />";       
                  $attributeData[] = array('attributeLabel'=>$attributeLabel,
                                           'value'=>$value);
              }
          }*/
               $warranty = $product->getData('is_warranty'); 
               $specification = $product->getData('specification');
               $attributeData = array('warranty'=>$warranty,
                                      'specification'=>$specification);
               $shipping = $product->getData('shipping_return');

               $shippingReturn = array('shipping_returndata'=>$shipping);
              

          $productDetailsUISettings = array('mediaType'=>'1',
                                            'backgroundMediaData'=>'#FFFFFF',
                           'title' => array('textColor' =>'#212121',
                                            'font'=>'2',
                                            'fontWeight'=>'600', 
                                            'fontSize'=>'36px'),
                        'subTitle' => array('textColor' =>'#666666',
                                            'font'=>'2',
                                            'fontWeight'=>'600',
                                            'fontSize'=>'36px'),
                        'tabTitle' => array('textColor' =>'#666666',
                                            'font'=>'2',
                                            'fontWeight'=>'600',
                                            'fontSize'=>'36px',
                                            'tabSelectedLineColor'=>'#212121',
                                            'tabUnSelectedLineColor'=>'#ffffff',
                                            'tabDividerColor'=>'#DEDEDE'));

          $productdescription = array('componentId'=>'ProductDescription',
                                      'productDetailsUISettings'=>$productDetailsUISettings,
                                      'productDetailData'=>$productdescriptionData);
          $attributes = array('atributesDt'=>$attribute);
          $productattributes = array('componentId'=>'ProductAttributes',
                                     'productattributesUISettings'=>$productDetailsUISettings,
                                     'productDetailData'=>$attributes);

          $productshipping = array('componentId'=>'shipping',
                                     'productShippingUISettings'=>$productDetailsUISettings,
                                     'productShippingData'=>$shippingReturn);

          $component = array($productdescription,$productattributes,$productshipping);

          $response  = array('status' =>'OK',
                             'statusCode'=>200,
                             'message'=>'success',
                             'component'=>$component);  

          echo json_encode($response,JSON_UNESCAPED_SLASHES);
    
        }
}     