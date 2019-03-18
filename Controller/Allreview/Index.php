<?php
namespace Magneto\Ecommerceapi\Controller\Allreview;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
	protected $storeManager;
    protected $ratingFactory;

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException {
        return null;
    }
    
    public function validateForCsrf(RequestInterface $request): ?bool{
        return true;
    }
    
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Review\Model\Rating $ratingFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->ratingFactory = $ratingFactory;
        $this->storeManager = $storeManager;

        parent::__construct($context);
    }

	public function execute() {
		$customerId = $_POST["customerId"];  
        $productId = $_POST["productId"]; 
        $langId = $_POST["langId"];
        if(empty($langId)){
        	$langId == '1';
        }
        $currencyId = $_POST["curId"];
        $pageSize = $_POST["pageSize"]; 
        $page = $_POST["page"];
        

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $this->storeManager->getStore();
        $storeId =$storeManager->getStoreId();

        $generalUISettings = array('mediaType' =>'1','backgroundMediaData'=>'#f6f6f6','navDividerColor'=>'#DEDEDE');

        $allReviewsUISettings = array('backgroundColor'=>'#ffffff',
                                	  'isShadow'=>'1',
                                	  'dividerColor'=>'#dedede',
                                	  'selectedRating'=>'#2a8afb',
                                	  'unselectedRating'=>'#f0f0f0',
                                	  'reviewTitle' => array('textColor' =>'#212121','font'=>'2','fontSize'=>'15'),
                                	  'reviewContent' => array('textColor' =>'#666666','font'=>'1','fontSize'=>'14'),
                                	  'reviewFromText' => array('textColor' =>'#212121','font'=>'2','fontSize'=>'13'),
                                	  'reviewDate' => array('textColor' =>'#666666','font'=>'2','fontSize'=>'13')
        );

        $collectionFactory = $objectManager->get('\Magento\Review\Model\ResourceModel\Review\CollectionFactory');
        $collection = $collectionFactory->create()->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)->addEntityFilter('product',$productId)->setDateOrder(); 
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $total_pages = $collection->getLastPageNumber();

        $ratingCollection = $objectManager->get('Magento\Review\Model\ResourceModel\Rating\Option\Vote\Collection')->addFieldToFilter('entity_pk_value', $productId);

        foreach ($collection as $item){
        	$reviewId =$item->getReviewId();
            foreach ($ratingCollection as $value) {
                if($value->getReviewId() == $reviewId){
                    $star = $value->getValue();
                }
            }
            $reviewFactory = $objectManager->get('\Magento\Review\Model\ReviewFactory');

        	$review = $reviewFactory->create()->load($reviewId);

        	if($review->getStoreId() == $langId){
        		$nickname = $review->getNickname();
        		$title = $review->getTitle();
        		$detail = $review->getDetail();
                $date = $review->getCreatedAt();
                $createDate = new \DateTime($date);
        		$created_at = $createDate->format('Y-m-d');
        	} else {
                $nickname = $review->getNickname();
                $title = $review->getTitle();
                $detail = $review->getDetail();
                $date = $review->getCreatedAt();
                $createDate = new \DateTime($date);
                $created_at = $createDate->format('Y-m-d');
            }

            $reviewallDataCombine1[] = array('rating' =>''.$star.'',
                                             'reviewTitle'=>$title,
                                             'reviewContent'=>$detail,
                                             'reviewFromText'=>$nickname,
                                             'reviewDate'=>$created_at);
            $reviewData = array('list'=>$reviewallDataCombine1);
            $productAllReview = array('componentId' =>'allReview',
            	'sequenceId'=>'1',
            	'isActive'=>'1',
            	'allReviewUISettings'=>$allReviewsUISettings,
            	'allReviewData'=>$reviewData); 
            $component1 = array($productAllReview);
        } 

        if(empty($component1)){
            $status = array('status' =>'OK',
            	'statusCode'=>300,
            	'message'=>'No Data Found!'); 
            echo json_encode($status); 
        }elseif($page > $total_pages){
            $status = array('status' =>'Error',
                              'statusCode'=>300,
                              'message'=>'No data found!');    
            echo json_encode($status);
        }else{
        	$status = array('status' =>'OK',
                    		'statusCode'=>200,
                    		'id'=>$customerId,
                    		'langId'=>$storeId,
                    		'message'=>'Success',
                    		'generalUISettings'=>$generalUISettings,
                    		'component'=>$component1); 
        	echo json_encode($status,JSON_UNESCAPED_SLASHES);
        } 
	}
}