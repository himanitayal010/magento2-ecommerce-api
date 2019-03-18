<?php 
namespace Magneto\Ecommerceapi\Controller\Search;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;

use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Search\Model\PopularSearchTerms;

class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
	public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException {
        return null;
    }
    
    public function validateForCsrf(RequestInterface $request): ?bool{
        return true;
    }

    protected $_catalogSession;
    protected $_storeManager;
    private $_queryFactory;
    private $layerResolver;
    public function __construct(
        Context $context,
        Session $catalogSession,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        Resolver $layerResolver
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_catalogSession = $catalogSession;
        $this->_queryFactory = $queryFactory;
        $this->layerResolver = $layerResolver;
    }

    public function execute()
    {
        $queryValue = $_POST["queryValue"];
        $langId = $_POST["langId"];
        $currencyId = $_POST["curId"];
        $customerId = $_POST["customerId"];

        $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
        $query = $this->_queryFactory->get();
        $storeId = $this->_storeManager->getStore()->getId();
        $query->setStoreId($storeId);
        $queryText = $query->setQueryText($queryValue);
        $queryText = $query->getQueryText($queryText);

      	//$queryText =  $queryValue;
        if ($queryText != '') {
        	$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
            $catalogSearchHelper = $objectManager->get(\Magento\CatalogSearch\Helper\Data::class);

            $getAdditionalRequestParameters = $this->getRequest()->getParams();
            unset($getAdditionalRequestParameters[QueryFactory::QUERY_VAR_NAME]);

            if (empty($getAdditionalRequestParameters) &&
                $productCollection = $objectManager->get(PopularSearchTerms::class)->isCacheable($queryText, $storeId)
            ) {
               $productCollection = $this->getCacheableResult($catalogSearchHelper, $query);
            } else {
                $productCollection = $this->getNotCacheableResult($catalogSearchHelper, $query);
                var_dump($productCollection);
            }
        } else {
            $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
        }
    }

     function getCacheableResult($catalogSearchHelper, $query)
    {
        if (!$catalogSearchHelper->isMinQueryLength()) {
            $redirect = $query->getRedirect();
            if ($redirect && $this->_url->getCurrentUrl() !== $redirect) {
                $this->getResponse()->setRedirect($redirect);
                return;
            }
        }
        $catalogSearchHelper->checkNotes();
        $this->_view->loadLayout();
        //$this->_view->renderLayout();
    }
    function getNotCacheableResult($catalogSearchHelper, $query)
    {
        if ($catalogSearchHelper->isMinQueryLength()) {
            $query->setId(0)->setIsActive(1)->setIsProcessed(1);
           echo "<pre>";print_r($query->getData());
        } else {
        	
            $query->saveIncrementalPopularity();
            $redirect = $query->getRedirect();
            if ($redirect && $this->_url->getCurrentUrl() !== $redirect) {
                $this->getResponse()->setRedirect($redirect);
                return;
            }
        }

        $catalogSearchHelper->checkNotes();

        $this->_view->loadLayout();
        $this->getResponse()->setNoCacheHeaders();
        //$this->_view->renderLayout();
    }
    /*public function execute()  
    { 
        $productName = $_POST["productName"];
    	$langId = $_POST["langId"];
    	$currencyId = $_POST["curId"];
    	$customerId = $_POST["customerId"]; 
    	$generalUISettings  = array('dividerColor' =>'#DEDEDE',
									'isShadow'=>'1',
									'shadowColor'=>'#a4a4a4',
									'backgroundColor'=>'#f1f1f1',
									'navDividerColor'=>'#DEDEDE',
					'title'=> array('typedtextColor' =>'#212121',
									'font'=>'1',
									'fontSize'=>'12'),
					'RecentSearchTitle' => array("textColor" => "#666666",
     				 						"font"=> "2",
      										"fontSize" => "16"),
		 			'yourRecentSearch'=> array('textColor' =>'#666666',
							        'font'=>'2',
							        'fontSize'=>'16'));
		$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
		$productcollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
		                   ->addAttributeToSelect('*') 
		                   ->addAttributeToFilter('name', array('like'=>'%'.$productName.'%'));
 		foreach ($productcollection as $product){
      		 $productName = $product->getName();
      		 $productId = $product->getId();
      		 $component1[] = array('title' =>$productName,
      		 					   'id'=>$productId,
                                   'type'=>'2',
                                   'query'=>"http://27.109.19.235/ashrafs/ecommerceapi/productdetail?productId=$productId&customerId=$customerId&langId=$langId&curId=$currencyId",
                                   'navigationFlag'=>'1')
                                   ; 	    
		}
		if(!empty($component1)){
			$status = array('status' =>'OK',
		                    'statusCode'=>200, 
		                    'message'=>'Success', 
		                    'generalUISettings'=>$generalUISettings,
		                    'componentData'=>$component1);    
         	echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
		}else{
			$status = array('status' =>'OK',
		                    'statusCode'=>300, 
		                    'message'=>'No Related data found!');    
         	echo $status1 = json_encode($status);
		}	
	} */
}