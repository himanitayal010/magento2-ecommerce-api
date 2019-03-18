<?php 
namespace Magneto\Ecommerceapi\Controller\PromoCode;
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
  public function execute() { 


          $customerId = $_POST["customerId"];
          $langId = $_POST["langId"];
          $currencyId = $_POST["curId"];

          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

          $generalUISettings = array('mediaType' =>'1',
                                     'backgroundMediaData'=>'#F6F6F6',
                                     'navDividerColor'=>'#DEDEDE',
                                     'isShadow'=>'1',
                                     'shadowColor'=>'#a4a4a4',
                                     'applyPromoCodeBoxBackgroundColor'=>'#ffffff',
              'buttonApply'=>  array('textColor' =>'#008BFF',
                                     'font'=>'2',
                                     'fontSize'=>'13',
                                     'backgroundColor'=>'#ffffff',
                                     'borderColor'=>'#dedede'),
            'titlePromoList'=> array('textColor' =>'#666666',
                                     'font'=>'1',
                                     'fontSize'=>'13')); 

          $promoCodeListUISettings = array('mediaType' =>'1',
                                           'backgroundMediaData'=>'#FFFFFF',
                                           'isShadow'=>'1',
                                           'shadowColor'=>'#a4a4a4',
                                           'selectedRadioColor'=>'#008BFF',
                                           'unSelectedRadioColor'=>'#666666',
                      'promoTitle'=> array('textColor' =>'#666666',
                                           'font'=>'2',
                                           'fontSize'=>'15'),
                'promoDescription'=> array('textColor' =>'#666666',
                                           'font'=>'1',
                                           'fontSize'=>'13'));

          

          $couponCollection = $objectManager->create('Magento\SalesRule\Model\Rule')->getCollection();

              foreach ($couponCollection as $rule) {

                $promoTitle = $rule->getName();
                $promoId = $rule->getId();
                $description = $rule->getDescription();

                $promoCode[]  = array('promoTitle'=>$promoTitle,
                                      'promoDescription'=> $description);
              }
                    if(empty($promoCode)){ 
                         $status = array('status' =>'Error',
                                         'statusCode'=>300, 
                                         'message'=>'No Data found!');

                         echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);

                    }else{
                          $promo =array('list'=>$promoCode);

                         $promoCodeList = array('componentId' =>'promoCodeList',
                                                'sequenceId'=>'1',
                                                'isActive'=>'1',
                                                'promoCodeListUISettings'=>$promoCodeListUISettings,
                                                'promoCodeListData'=>$promo); 

                         $component1 = array($promoCodeList);  

                         $status = array('status' =>'OK',
                                         'statusCode'=>200, 
                                         'message'=>'Success',
                                         'isUpdateUISettingFlag'=>'0',
                                         'generalUISettings'=>$generalUISettings,
                                         'component'=>$component1); 

                         echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES);
                    }
            }  
      }
