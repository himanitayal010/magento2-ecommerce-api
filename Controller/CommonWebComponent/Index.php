<?php 
namespace Magneto\Ecommerceapi\Controller\CommonWebComponent; 

class Index extends \Magento\Framework\App\Action\Action
{
	public function execute() 
    { 
   
    $legalPolicies = array('componentId' =>'legalPolicies',
                    'productImageUISettings'=> array('mediaType' =>'1',
                            'backgroundMediaData'=>'#ffffff',
                    'title'=> array('textColor' =>'#212121',
                            'font'=>'2',
                            'fontSize'=>'16'),
                    'subTitle'=> array('textColor' =>'#212121',
                            'font'=>'2',
                            'fontSize'=>'16')));

    $contactUs = array('componentId' =>'contactUs',
                'contactUsUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));

    $shoppingAndDeliveryPolicy = array('componentId' =>'shoppingAndDeliveryPolicy',
                'shoppingAndDeliveryPolicyUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));

    $termsAndConditions = array('componentId' =>'termsAndConditions',
                'termsAndConditionsUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));

    $refundPolicy = array('componentId' =>'refundPolicy',
                'refundPolicyUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));
    $privacyPolicy = array('componentId' =>'privacyPolicy',
                'privacyPolicyUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));
    $cancellationPolicy = array('componentId' =>'cancellationPolicy',
                'cancellationPolicyUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));

    $billingAndPayments = array('componentId' =>'billingAndPayments',
                'billingAndPaymentsUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16')));

    $productDetails = array('componentId' =>'productDetails',
                'productDetailsUISettings'=> array('mediaType' =>'1',
                        'backgroundMediaData'=>'#ffffff',
                'title'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'subTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16'),
                'tabTitle'=> array('textColor' =>'#212121',
                        'font'=>'2',
                        'fontSize'=>'16',
                        'tabSelectedLineColor' =>'#212121',
                        'tabUnSelectedLineColor' =>'#ffffff', 
                        'tabDividerColor' =>'#DEDEDE')));

    $component1 = array($legalPolicies,$contactUs,$shoppingAndDeliveryPolicy,$termsAndConditions,$refundPolicy,$privacyPolicy,$cancellationPolicy,$billingAndPayments,$productDetails);

    $status = array('status' =>'OK',
            'statusCode'=>200, 
            'message'=>'Success',
            'isUpdateUISettingFlag'=>'0',
            'generalUISettings'=>array('mediaType' =>'1',
            'backgroundMediaData'=>'#ffffff',
            'navDividerColor'=>'#DEDEDE') ,
            'component'=>$component1);
        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
    } 
}
