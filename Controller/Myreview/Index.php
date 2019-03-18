<?php 
namespace Magneto\Ecommerceapi\Controller\Myreview; 

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
      //$productId = $_POST["productId"];     
		$customerId = $_POST["customerId"]; 
		$langId = $_POST["langId"];
		$currencyId =$_POST["curId"];  
		$pageSize = $_POST["pageSize"]; 
        $page = $_POST["page"];
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$storeId =$storeManager->getStore()->getStoreId();


		$generalUISettings = array('mediaType' =>'1',
								   'backgroundMediaData'=>'#F6F6F6',
								   'navDividerColor'=>'#DEDEDE'); 

		$myReviewsUISettings = array('isShadow'=>'1',
									 'shadowColor'=>'#a2a2a2',
									 'mediaType'=>'1',
									 'backgroundMediaData'=>'#FFFFFF',
									 'deviderColor'=>'#DEDEDE',
									 'selectedRating'=>'#2a8afb',
									 'unselectedRating'=>'#f0f0f0',
									 'deleteIconColor'=>'#666666',
									 'editIconColor'=>'#666666', 
			  'productName' => array('textColor' =>'#666666',
									 'font'=>'1',
									 'fontSize'=>'15'),
			  'reviewTitle' => array('textColor' =>'#212121',
									 'font'=>'1',
									 'fontSize'=>'15'),
	    'reviewDescription' => array('textColor' =>'#666666',
									 'font'=>'1',
									 'fontSize'=>'13'),
			   'reviewDate' => array('textColor' =>'#9E9E9E',
									 'font'=>'1',
									 'fontSize'=>'12'));   

		$reviewCollectionFactory= $objectManager->get('\Magento\Review\Model\ResourceModel\Review\CollectionFactory'); 
		$result = $items = $starData = [];

		$rating = $objectManager->get('\Magento\Review\Model\ResourceModel\Review\CollectionFactory');
		$collection = $rating->create()->addCustomerFilter(
							$customerId
						)->addStatusFilter(
							\Magento\Review\Model\Review::STATUS_APPROVED
						)->setDateOrder(); 
		$collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $total_pages = $collection->getLastPageNumber();
		foreach ($collection as $item){
			$idArray=$item['entity_pk_value']; 
			$product = $objectManager->create('Magento\Catalog\Model\Product')->load($idArray);
			$proname=$product->getName(); 
			$proImage =$store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();

			$reviewsCollection = $reviewCollectionFactory->create()->addCustomerFilter(
				$customerId)
			->addFieldToFilter('entity_pk_value',array("in" => $idArray))
			->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
			->addRateVotes();
			$reviewsCollection->getSelect();
			foreach ($reviewsCollection->getItems() as $review) {
				$date = $review->getCreatedAt();
                $createDate = new \DateTime($date);
        		$created_at = $createDate->format('Y-m-d');
				$reviewTitlekk = $item->getTitle();
				$reviewdetail = $item->getDetail();
				foreach( $review->getRatingVotes() as $_vote ) {


					$rating = [];
					$percent = $_vote->getPercent();
					$star = ($percent/20);
					$productId = $_vote->getEntityPkValue();
					$product=$objectManager->get('\Magento\Catalog\Model\ProductFactory'); 
					$productModel = $product->create();
					$product = $productModel->load($productId);  

					$reviewFactory = $objectManager->get('\Magento\Review\Model\ReviewFactory'); 


					$countReview = $reviewFactory->create()->getTotalReviews($productId,false);
					$review_id = $_vote->getReviewId();

					$rating['review_id'] = $review_id; 
					$rating['product_id'] = $productId;    
					$rating['percent']   = $percent;  
					$rating['star']      = $star;    
					$rating['nickname']  = $review->getNickname(); 
					$reviewNickname = $review->getNickname();   
					$items[] = $rating;
					$starData[$star][] = $rating;

					$reviewallDataCombine1[] = array('reviewID' =>$review_id,
													 'productName'=>$proname,
													 'image'=>$proImage,
													 'reviewDescription'=>$reviewdetail,
													 'myRating'=>''.$star.'',
													 'reviewTitle'=>$reviewTitlekk,
													 'reviewDate'=>$created_at);
				}

			}
		}
		if(empty($reviewallDataCombine1)){
            $status = array('status' =>'Error',
			            	'statusCode'=>300,
			            	'message'=>'No Data Found!'); 

            echo json_encode($status); 

        }elseif($page > $total_pages){
            $status = array('status' =>'Error',
                            'statusCode'=>300,
                            'message'=>'No data found!');  

            echo json_encode($status);
        }else{
        	$reviewData = array('list'=>$reviewallDataCombine1); 

			$productAllReview = array('componentId' =>'myReviews',
									  'sequenceId'=>'1',
									  'isActive'=>'1', 
									  'myReviewsUISettings'=>$myReviewsUISettings,
									  'myReviewsData'=>$reviewData);   

			$component1 = array($productAllReview);


			$status = array('status' =>'OK',
							'statusCode'=>200,
							'customerId'=>$customerId,
							'isUpdateUISettingFlag'=>'0',
							'message'=>'Success',
							'generalUISettings'=>$generalUISettings,
							'component'=>$component1);   
        	
        	echo json_encode($status,JSON_UNESCAPED_SLASHES);
        } 
	} 
}  
