<?php 
namespace Magneto\Ecommerceapi\Controller\Allreview; 

class Index extends \Magento\Framework\App\Action\Action
{
	public function execute() 
    { 
        $product_id1 = 4;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $generalUISettings = array('mediaType' =>'1',
                                 'backgroundMediaData'=>'#f6f6f6',
                                 'navDividerColor'=>'#DEDEDE'); 

        $allReviewUISettings = array('backgroundColor' =>'#ffffff',
                                    'isShadow'=>'1',
                                    'dividerColor'=>'#dedede',
                                    'selectedRating'=>'#2a8afb',
                                    'unselectedRating'=>'#f0f0f0',
                                    'reviewTitle' => array('textColor' =>'#212121',
                                        'font'=>'2',
                                        'fontSize'=>'15'),
                                    'reviewContent' => array('textColor' =>'#666666',
                                        'font'=>'1',
                                        'fontSize'=>'14'),
                                    'reviewFromText' => array('textColor' =>'#212121',
                                        'font'=>'2',
                                        'fontSize'=>'13'),
                                    'reviewDate' => array('textColor' =>'#666666',
                                        'font'=>'2',
                                        'fontSize'=>'13')); 

        $rating = $objectManager->get("Magento\Review\Model\ResourceModel\Review\CollectionFactory");

    	$collection = $rating->create()
            ->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product', 
                 $product_id1
            )->setDateOrder();
    		/*echo "<pre>";
    		print_r($collection->getData());*/
    		/*$reviewallDataCombine1[] = array();*/
    		foreach ($collection as $item)      
    		  {
        	     $reviewid =$item->getReviewId();
        	     $reviewTitlekk = $item->getTitle();
        	     $reviewDetail = $item->getDetail();
        	     $reviewFrom = $item->getNickname();
        	     $reviewDatedata = $item->getCreatedAt();
                 $reviewallDataCombine1 = array('rating' =>'4.2',
                    'reviewTitle'=>$reviewTitlekk,
                    'reviewContent'=>$reviewDetail,
                    'reviewFromText'=>$reviewFrom,
                    'reviewDate'=>'15 Jan 2019'); 
                 $reviewallDataCombine = array($reviewallDataCombine1);
                 $reviewData = array('list'=>$reviewallDataCombine); 
         
                 $productAllReview = array('componentId' =>'allReview',
                    'sequenceId'=>'1',
                    'isActive'=>'1',
                    'allReviewUISettings'=>$allReviewUISettings,
                    'allReviewData'=>$reviewData);
                 $component1 = array($productAllReview);
            }
            $status = array('status' =>'OK',
                        'statusCode'=>200, 
                        'message'=>'Success',
                        'generalUISettings'=>$generalUISettings,
                        'component'=>$component1);

            echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
     } 
 }
 