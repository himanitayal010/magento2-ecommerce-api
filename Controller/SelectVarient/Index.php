<?php 
namespace Magneto\Ecommerceapi\Controller\SelectVarient; 
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
    public function execute()  
    { 
      $productId = $_POST["productId"];
      $customerId = $_POST["customerId"];
      $langId = $_POST["langId"];
      $storeId= $langId;
      $curId= $_POST["curId"];
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
      $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId)->setStore($storeId);
      $productTypeInstance = $objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
      $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($configProduct);
      $productCount = count($productAttributeOptions);
      $generalUISettings=array('mediaType' =>'1',
                                'backgroundMediaData'=>'#ffffff',
                                'navDividerColor'=>'#dedede',
                                'selectedRating'=>'#2a8afb',
                                'unselectedRating'=>'#f0f0f0', 
                                'scratchLineWidth'=>'1',
                                'productName'=> array('textColor' =>'#212121',
                                              'font'=>'1',
                                              'fontSize'=>'16'), 
                                'price'=> array('textColor' =>'#666666',
                                        'font'=>'2',
                                        'fontSize'=>'12'),
                                'discountPrice'=> array('textColor' =>'#e6201a',
                                              'font'=>'2',
                                              'fontSize'=>'14'),
                                'additionalText'=> array('textColor' =>'#3db75e',
                                                'font'=>'2',
                                                'fontSize'=>'14'),
                                'buttonCancel'=> array('font' =>'2',
                                                'textColor'=>'#666666',
                                                'fontSize'=>'16',
                                                'backgroundColor'=>'#ffffff',
                                                'borderPixel'=>'2',
                                                'borderColor'=>'#666666'),
                                'buttonApply'=> array('font' =>'2',
                                                'textColor'=>'#008BFF',
                                                'fontSize'=>'16',
                                                'backgroundColor'=>'#ffffff',
                                                'borderPixel'=>'2',
                                                'borderColor'=>'#666666'));
      $attributesUISettings = array('backgroundColor' =>'#ffffff',
                              'isShadow'=>'1',
                              'multiAttributeFlag'=>'0', 
                              'title'=>array('textColor' =>'#212121',
                                      'font'=>'2',
                                      'fontSize'=>'14'),
                              'selectedOptionText'=>array('textColor' =>'#298afc',
                                              'font'=>'2',
                                              'fontSize'=>'14'),
                              'selectedOption'=>array('textColor' =>'#298afc',
                                              'font'=>'2',
                                              'fontSize'=>'14',
                                              'borderColor'=>'#298afc'),
                              'unselectedOption'=>array('textColor' =>'#dedede',
                                              'font'=>'2',
                                              'fontSize'=>'14',
                                              'borderColor'=>'#212121'));  
      if($configProduct->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE){
          foreach($productAttributeOptions as $productAttributeOption)
          {
            $attributeId = $productAttributeOption['attribute_id'];
            $attributeLabel = $productAttributeOption['label'];
            $attributeOptions =array();
            foreach ($productAttributeOption['values'] as $attribute) {
            $swatchHelper=$objectManager->get("Magento\Swatches\Helper\Media");
            $swatchCollection = $objectManager->create('Magento\Swatches\Model\ResourceModel\Swatch\Collection');
            $swatchCollection->addFieldtoFilter('option_id',$productAttributeOption['values']);
            //echo "<pre>";print_r(get_class_methods($swatchCollection));die;
            $items=$swatchCollection->getItems();
              foreach($items as $item) {
               // echo $item->getValue();
                if($item->getValue() == '')
                {
                 
                  $imageValue = '';
                }
                else
                {
                 $imageValue = $swatchHelper->getSwatchAttributeImage('swatch_thumb', $item->getValue());
                }
              }

            $attributeOptions[] = array('optionId'=>$attribute['value_index'],
                                          'value' =>$attribute['label'],
                                          'defaultSelected'=>'0',
                                         'image'=>$imageValue);  
   
            }
                    
            $attributesData[] = array('attributeId'=>$attributeId,
                            'title' =>$attributeLabel,
                            'type'=> 'SS',
                            'options'=>$attributeOptions);        
          }
        $attributesSelectedData = array('list' =>$attributesData); 
        $attributes = array('componentId' =>'attributes',
                          'sequenceId'=>'1',
                          'isActive'=>'1',
                          'attributesUISettings'=>$attributesUISettings,
                          'attributesData'=>$attributesSelectedData);
        $component = array($attributes); 
        $status = array(  'status' =>'OK',
                          'statusCode'=>200,
                          'message'=>'Success',
                          'generalUISettings'=>$generalUISettings,
                          'component'=>$component);    
      echo $selectedStatus = json_encode($status,JSON_UNESCAPED_SLASHES);
    }
    else
    {
       $status = array('status' =>'OK',
                        'statusCode'=>300, 
                        'message'=>'No data found!'); 

      echo $selectedStatus = json_encode($status,JSON_UNESCAPED_SLASHES); 
    }
    }
}
