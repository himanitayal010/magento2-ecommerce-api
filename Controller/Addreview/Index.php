<?php 
namespace Magneto\Ecommerceapi\Controller\Addreview;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
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

	 public function __construct(
        Context $context,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_reviewFactory = $reviewFactory;
        $this->_ratingFactory = $ratingFactory;
        $this->_storeManager = $storeManager;
    }

    public function execute()  
    {

     	$productId=$_POST["productId"]; 
		$customerId=$_POST["customerId"]; //customerId
		$langId=$_POST["langId"];         //languageId
		$currencyId=$_POST["curId"];       //currencyId
		$customerNickName=$_POST["nickname"]; //customername
		$reviewTitle= $_POST["title"];		//review Title
		$reviewDetail=$_POST["detail"];	
		// review Detail
		//$StoreId=1;  
		$optionIds = $_POST['optionIds']; //Rating
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$StoreId=$storeManager->getStore()->getStoreId();

		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);

		$customer_check = $objectManager->get('Magento\Customer\Model\Customer');

		// Note
		// Entity Id:- Rating for Product, Customers, Category, we selected product i.e. 1
		// Rating Id:- Ratng on the basis of Quantity, Value, Price , we selected Value i.e. 2
		$customer_check->load($customerId); 
		if ($customer_check->getId())
		{
			$reviewFinalData['ratings'][2] = $optionIds;
		    $reviewFinalData['nickname'] = $customerNickName;
		    $reviewFinalData['title'] = $reviewTitle;
		    $reviewFinalData['detail'] = $reviewDetail;
		    $review = $this->_reviewFactory->create()->setData($reviewFinalData);
		    $review->unsetData('review_id');
		    $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
            	->setEntityPkValue($productId)
            	->setStatusId(\Magento\Review\Model\Review::STATUS_PENDING)//By default set approved
            	->setCustomerId($customerId)
            	->setStoreId($this->_storeManager->getStore()->getId())
            	->setStores([$this->_storeManager->getStore()->getId()])
            	->save();
 
		    foreach ($reviewFinalData['ratings'] as $ratingId => $optionId) {
		        $this->_ratingFactory->create()
		            ->setRatingId($ratingId)
		            ->setReviewId($review->getId())
		            ->addOptionVote($optionId, $productId);
		    }

		    $review->aggregate();
		    $message = "You submitted your review for moderation."; 
			$response=array('status' =>'OK',
				'statusCode'=>200,
				'customerId'=>$customerId,
				'productId'=>$productId,
				'customerNickName'=>$customerNickName,
				'reviewTitle'=>$reviewTitle,
				'reviewDetail'=>$reviewDetail, 
				'message'=>$message); 
		} else {
			$reviewFinalData['ratings'][2] = $optionIds;
		    $reviewFinalData['nickname'] = $customerNickName;
		    $reviewFinalData['title'] = $reviewTitle;
		    $reviewFinalData['detail'] = $reviewDetail;
        	$review = $this->_reviewFactory->create()->setData($reviewFinalData);
        	$review->unsetData('review_id');
        	$review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
            	->setEntityPkValue($productId)
            	->setStatusId(\Magento\Review\Model\Review::STATUS_PENDING)//By default set approved
            	->setCustomerId()
            	->setStoreId($this->_storeManager->getStore()->getId())
            	->setStores([$this->_storeManager->getStore()->getId()])
            	->save();
 
		    foreach ($reviewFinalData['ratings'] as $ratingId => $optionId) {
		        $this->_ratingFactory->create()
		            ->setRatingId($ratingId)
		            ->setReviewId($review->getId())
		            ->addOptionVote($optionId, $productId);
		    }
		    $review->aggregate();
        	$message = "You submitted your review for moderation.";  
			$response =   array('status' =>'OK',
				'statusCode'=>'526',
				'productId'=>$productId,
				'customerNickName'=>$customerNickName,
				'reviewTitle'=>$reviewTitle,
				'reviewDetail'=>$reviewDetail,
				'message'=>$message);
		}
		echo json_encode($response,JSON_UNESCAPED_SLASHES); 
	}
}