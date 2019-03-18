<?php 
namespace Magneto\Ecommerceapi\Controller\Categories;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute() 
    { 
        
        $id = $_REQUEST["customerId"];
        $langId = $_REQUEST["langId"]; 
        $currencyId = $_REQUEST["curId"]; 

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl= $storeManager->getStore()->getBaseUrl();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $website_id = $storeManager->getWebsite()->getWebsiteId();
        $siuisettings = array('isShadow' =>'1',
                              'mediaType'=>'1',
                              'backgroundMediaData'=>'#ffffff'); 

        $categoryCollection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categories = $categoryCollection->create();
        $categories->addAttributeToSelect('*');
        $categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
        $categoryObj = $objectManager->create('Magento\Catalog\Model\Category');
        $categories = $categoryHelper->getStoreCategories();

        $subtitle = array('textColor' =>'#546266',
                          'font'=>'1',  
                          'fontSize'=>'12');

        $title = array('textColor' =>'#546266',
                       'font'=>'2',
                       'fontSize'=>'17');

        $level1 = array('downArrowColor' =>'#666666',
                        'upArrowColor'=>'#666666',
                        'levelOneMediaType'=>'1',
                        'levelOneBackgroundMediaData'=>'#ffffff',
                        'title'=>$title,
                        'subtitle'=>$subtitle);

        $titlelevel2 = array('textColor' =>'#626262',
                             'font'=>'2',
                             'fontSize'=>'15');

        $level2 = array('downArrowColor' =>'#666666',
                        'upArrowColor'=>'#666666',
                        'levelTwoBackgroundColor'=>'#f6f6f6',
                        'title'=>$titlelevel2);

        $titlelevel3 = array('textColor' =>'#666666',
                             'font'=>'1',
                             'fontSize'=>'15');

        $level3 = array('levelThreeBackgroundColor' =>'#ffffff',
                        'title'=>$titlelevel3);

        $categoryTabUISettings = array('level1'=>$level1,
                                       'level2'=>$level2,
                                       'level3'=>$level3);
       
        
        $categoryTabData=array();

            //----------------------------- Level 1 Start

        foreach ($categories as $category) 
        {

                $categoryObj = $categoryObj->load($category->getEntityId());
                $catid1=  $category->getId();
                $catname1 =  $category->getName();
                $catimage = $categoryObj->getImageUrl('thumbnail');   
                if($catimage == false){
                   $catimage = "";
                } else {
                   $catimage = $catimage;
                }

                $secondcategoryObj= $categoryObj->load($category->getId()); 
                $subcategories = $secondcategoryObj->getChildrenCategories($catid1);


                $level2catdata1 = array();

                //--------------------Level 2 Start
                foreach($subcategories as $subcategorie) {
                   $level2CatDataName = $subcategorie->getName();  
                   $level2CatDataId = $subcategorie->getId(); 
                
                   $subcate1 = $subcategorie->getChildrenCategories($level2CatDataId);   
                   $level3catdata1=array();

              //----------------------------Level 3 Start

                    foreach($subcate1 as $subcateg)  {
                        $level3CatDataName = $subcateg->getName();
                        $level3CatDataId =   $subcateg->getId();
                        $level3catdata1[] = array('title' =>$level3CatDataName,
                                                  'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$level3CatDataId&customerId=$id&langId=$langId&curId=$currencyId", 
                                                  'type'=>'1', 
                                                  'navigationFlag'=>'1');           
                    }
              //---------------------------------Level 3 End

                          if(count($level3catdata1) != 0){
                              $level2catdata1[] = array('title' =>$level2CatDataName,    
                                                        'level3'=>$level3catdata1); 
                          } else {
                              $level2catdata1 = array();
                          }
                         
                }
         //------------------------------Level 2 END
         if(count($level2catdata1) != 0){
                $categoryTabData = array('title' =>$catname1,  
                                         'backgroundImage'=>$catimage,
                                         'subTitle'=>$level2CatDataName, 
                                         'level2'=> $level2catdata1); 

                $componentId[] = array('componentId' =>'categoryTab',
                                       'sequenceId'=>'1',
                                       'isActive'=>'1',
                                       'categoryTabUISettings'=>$categoryTabUISettings,
                                       'categoryTabData'=>$categoryTabData);

            } else {
               $categoryTabData  = array();
            }  
         
              
       
         }
         //--------------------------------Level 1 End
         
          $singleImageData[] = array('id' =>$catid1,
                                   'image'=>$catimage,
                                   'type'=>'1',  
                                   'navigationFlag'=>'1',
                                   'query'=>$baseUrl."ecommerceapi/productlist?categoryId=$catid1&customerId=$id&langId=$langId&curId=$currencyId");      

        $componentSingleImage[] = array('componentId' => 'singleImage',
                                      'sequenceId' => '3', 
                                      'isActive' => '1',
     'singleImageUISettings' => array('isShadow' => '1',
                                      'mediaType' => '1',
                                      'backgroundMediaData' => '#ffffff', 
                                      'imageHeight'=>'352', 
                                      'imageWidth'=>'1080'),
            'singleImageData' =>array('list' => $singleImageData));

        $componentData  =  array_merge($componentSingleImage,$componentId);
        $componentData  =  array_filter($componentData);
        $componentData = (array_values($componentData));
        //$componentDatamerge =($componentData);

        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        $customer_check = $objectManager->get('Magento\Customer\Model\Customer');
        $customer_check->setWebsiteId($website_id);
        $id1  = $customer_check->load($id); 
        if ($customer_check->getId() ) { 

        $status = array('status' =>'OK',
                        'statusCode'=>200,
                        'id'=>$id,
                        'langId'=>$langId, 
                        'isUpdateUISettingFlag'=>'0',
                        'message'=>'Success',
                        'generalUISettings'=>array('mediaType'=>'1',
                                                   'backgroundMediaData'=>'#f6f6f6',
                                                   'navDividerColor'=>'#DEDEDE'),
                        'component'=>$componentData);  
       
        
        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);  
        }else{
        $status = array('status' =>'OK',
                        'statusCode'=>200,
                        'langId'=>$langId,
                        'isUpdateUISettingFlag'=>'0',
                        'message'=>'Success',
                        'generalUISettings'=>array('mediaType'=>'1',
                                                   'backgroundMediaData'=>'#f6f6f6',
                                                   'navDividerColor'=>'#DEDEDE'),
                        'component'=>$componentData);  
              
        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
        } 
    } 
}  
 
    
      