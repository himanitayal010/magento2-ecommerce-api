<?php 
namespace Magneto\Ecommerceapi\Controller\Filter;

use Magento\Framework\View\Element\Template;
use Magento\LayeredNavigation\Block\Navigation;
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
   protected $_layerResolver;
   public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        array $data = []
    )
    {
        $this->_layerResolver = $layerResolver->get();
        parent::__construct($context);
    }
    public function execute()  
    {   
      $customerId = $_POST["customerId"];
      $langId = $_POST["langId"];
      $currencyId = $_POST["curId"];  
      $categoryId = $_POST["categoryId"];

    
      $generalUISettings = array('mediaType' =>'1',
                                   'backgroundMediaData'=>'#ffffff',
                                   'navDividerColor'=>'#DEDEDE',
                'navTitle'=>array('textColor' =>'#666666',
                                  'font'=>'2',
                                  'fontSize'=>'22'));
      $filterUISetting = array('mediaType' =>'1',
                                'backgroundMediaData'=>'#ffffff',
                                'dividorColorForButtons'=>'#DEDEDE',
      'buttonClearAll'=>array('font' =>'2',
                                'textColor'=>'#008BFF',
                                'fontSize'=>'16',
                                'backgroundColor'=>'#ffffff'),
      'buttonClose'=>array('textColor'=>'#666666',
                                'font'=>'2',
                                'fontSize'=>'16',
                                'backgroundColor'=>'#ffffff'),
      'buttonApply'=>array('textColor'=>'#008BFF',
                                'font'=>'2',
                                'fontSize'=>'16',
                                'backgroundColor'=>'#ffffff'),
      'leftPart'=>array('backgroundUnselected'=>'#f6f6f6',
                                'backgroundSelected'=>'#ffffff',
                                'dividerColor'=>'#DEDEDE',
      'textFilterType'=>array('font'=>'2',
                                'textColor'=>'#666666',
                                'fontSize'=>'16')), 
      'rightPart'=>array('backgroundUnselected'=>'#ffffff',
                                'dividerColor'=>'#DEDEDE',
                                'selectedCheckMarkColor'=>'#008BFF',
                                'unselectedCheckMarkColor'=>'#9e9e9e',
      'textFilterSselected'=>array('font'=>'2', 
                                'textColor'=>'#666666',
                                'fontSize'=>'16'),
      'textFilterUnselected'=>array('font'=>'1',
                                'textColor'=>'#666666',
                                'fontSize'=>'15'),
      'textItemCount'=>array('font'=>'2',
                                'textColor'=>'#666666',
                                'fontSize'=>'12')));
      
      $this->_layerResolver->setCurrentCategory($categoryId);
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $fill = $objectManager->create('Magento\Catalog\Model\Layer\Category\FilterableAttributeList');
      $filterList = new \Magento\Catalog\Model\Layer\FilterList($objectManager,$fill);
      $filterAttributes = $filterList->getFilters($this->_layerResolver);
      $filterArray = array();
      foreach($filterAttributes as $filter)
      {
           $availablefilter = $filter->getRequestVar();
           if($availablefilter =="cat")
           {
             $filterId= $categoryId;
           }else {
                $attributemode = $filter->getAttributeModel();
                if($attributemode){
                $filterId = $attributemode->getAttributeId();
                } 
           }
           $availablefilter = (string)$filter->getName(); 
           $items = $filter->getItems();
           $filterValues = array();
           foreach($items as $item)
           {  
              $filterValues[] =array('id' =>$item->getValue(),
                                'title'=>strip_tags($item->getLabel()),
                                'count'=>$item->getCount(),
                                'type'=>'1',
                                'itemSelectedFlag'=>'0');        
           }
           if(!empty($filterValues))
           {
              $fiterData[]= array('filterId'=>$filterId,
                              'filterTypeName'=>$availablefilter,
                              'selectedFlag'=>'0',
                              'filtertype'=>'SS',
                              'data' =>$filterValues);
           } 
       }
      if(empty($fiterData))
      {
              $status = array('status' =>'OK',
                        'statusCode'=>300, 
                        'message'=>'No data found!'); 

              echo $filterStatus = json_encode($status,JSON_UNESCAPED_SLASHES); 
      } 
      else{

        $filterData = array('list' =>$fiterData);  
        $component[] = array('componentId' =>'filter',
                           'sequenceId'=>'1',
                           'isActive'=>'1',
                           'filterUISetting'=>$filterUISetting,
                           'filterData'=>$filterData);
        $status = array('status' =>'OK',
                      'statusCode'=>200,
                      'isUpdateUISettingFlag'=>'0',
                      'message'=>'message',
                      'titleNavigationBar'=>'FILTER',
                      'generalUISettings'=>$generalUISettings,
                      'component'=>$component);
        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);   
      }
    }
}