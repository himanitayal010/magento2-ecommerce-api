<?php 
namespace Magneto\Ecommerceapi\Controller\UiSettings; 

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute() 
    { 

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
    $baseUrl= $storeManager->getStore()->getBaseUrl(); 
    $currencyInterface = $objectManager->get('Magento\Framework\Locale\CurrencyInterface');
    $stores = $storeManager->getStores();
    $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
    $locale = [];

//Try to get list of locale for all stores;
    foreach($stores as $store) {
    $locale[] = $scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getStoreId());
    } 

    $availableCurrencies = $storeManager->getStore()->getAvailableCurrencyCodes();
    $currencyNames = array();
    $currencySymbol = array();

    foreach ($availableCurrencies as $currencyCode) {
        $currencyNames[] = $currencyInterface->getCurrency($currencyCode)->getName();
        $currencySymbol[] = $currencyInterface->getCurrency($currencyCode)->getSymbol();
    } 
    foreach ($currencyNames as $curr) {
     $curr1 =  $curr;
    }
    foreach ($currencySymbol as $sym) {
     $sym1 =  $sym;  
    }
    $store = $objectManager->get('Magento\Framework\Locale\Resolver'); 
    $lang = $store->getLocale(); 
    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    $currency = $objectManager->get('\Magento\Directory\Model\Currency');
    $store = $storeManager->getStore();
    $fontSizeAndroid1 = array('rangeMin'=>4,
                            'rangeMax'=>5,
                            'Size'=>0);
    $fontSizeAndroid2 = array('rangeMin'=>5,
                        'rangeMax'=>5.5, 
                        'Size'=>0);
     $fontSizeAndroid3 = array('rangeMin'=>5.5, 
                        'rangeMax'=>6,
                        'Size'=>0);
      $fontSizeAndroid4 = array('rangeMin'=>6,
                        'rangeMax'=>7,
                        'Size'=>0); 
      $fontSizeAndroid5 = array('rangeMin'=>6,
                        'rangeMax'=>10,
                        'Size'=>50); 
      $fontSizeAndroid6 = array('rangeMin'=>10, 
                        'rangeMax'=>99,
                        'Size'=>100);  
      $fontSizeAndroid = array($fontSizeAndroid1,$fontSizeAndroid2,$fontSizeAndroid3,$fontSizeAndroid4,$fontSizeAndroid5,$fontSizeAndroid6);
     

    $font_size1=array('deviceType' =>'small handset',
                        'rangeMin'=>'0',
                        'rangeMax'=>'359',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

    $font_size2=array('deviceType' =>'medium handset',
                        'rangeMin'=>'360',
                        'rangeMax'=>'399',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

    $font_size3=array('deviceType' =>'large handset',
                        'rangeMin'=>'400',
                        'rangeMax'=>'479',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

    $font_size4=array('deviceType' =>'large handset',
                        'rangeMin'=>'480',
                        'rangeMax'=>'599',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

    $font_size5=array('deviceType' =>'small handset',
                        'rangeMin'=>'600',
                        'rangeMax'=>'719',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

    $font_size6=array('deviceType' =>'large tablet',
                        'rangeMin'=>'720',
                        'rangeMax'=>'1024',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

      $font_size7=array('deviceType' =>'large tablet',
                        'rangeMin'=>'840',
                        'rangeMax'=>'959',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

      $font_size8=array('deviceType' =>'X-large tablet',
                        'rangeMin'=>'960',
                        'rangeMax'=>'1023',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

      $font_size9=array('deviceType' =>'XX-large tablet',
                        'rangeMin'=>'1024',
                        'rangeMax'=>'1279',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

     $font_size10=array('deviceType' =>'XXX-large tablet',
                        'rangeMin'=>'1280',
                        'rangeMax'=>'1439',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

     $font_size11=array('deviceType' =>'XXXX-large tablet',
                        'rangeMin'=>'1440',
                        'rangeMax'=>'1599',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

     $font_size12=array('deviceType' =>'XXXXX-large tablet',
                        'rangeMin'=>'1600',
                        'rangeMax'=>'1919',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

     $font_size13=array('deviceType' =>'Largest',
                        'rangeMin'=>'1920',
                        'rangeMax'=>'3000',
                        'iSize'=>'4%',
                        'aSize'=>'4%');

    $font_size = array($font_size1,$font_size2,$font_size3,$font_size4,$font_size5,$font_size6,$font_size7,$font_size8,$font_size9,$font_size10,$font_size11,$font_size12,$font_size13);

       $UISettings1 = array('1' =>'OpenSans-Regular',
                            '2'=>'OpenSans-SemiBold');
    $placeHolderColors1 = array('color'=> '#FFE0DA');
    $placeHolderColors2 = array('color'=> '#FFFEDA');
    $placeHolderColors3 = array('color'=> '#F0FFDA');
    $placeHolderColors4 = array('color'=> '#DAFDFF');
    $placeHolderColors5 = array('color'=> '#DAE9FF');
    $placeHolderColors6 = array('color'=> '#DEDAFF');

    $placeHolderColors = array($placeHolderColors1,$placeHolderColors2,$placeHolderColors3,$placeHolderColors4,$placeHolderColors5,$placeHolderColors6);
 
    $generalSetting = array('appBackgroundColor' =>'#f6f6f6',
                            'shadowColor'=>'#dedede',
                            'buttonCornerRadius'=>'4',
                            'loaderColor'=>'#308afc', 
                            'textFieldUnderlineColor'=>'#dbdbdb',
                            'errorBannerBackgroundColor'=>'#3976f9',
    'networkTitle' => array('textColor' =>'#666666',
                            'font'=>'2',
                            'fontSize'=>'16'), 
    'networkDescription'=>array('textColor' =>'#DEDEDE',
                                'font'=>'2',
                                'fontSize'=>'16'),    
      'errorBannerTitle'=>array('textColor' =>'#666666',
                                'font'=>'2',
                                'fontSize'=>'15'), 
        'errorBannerMsg'=>array('textColor' =>'#666666',
                                'font'=>'1',
                                'fontSize'=>'16'),
             'formTitle'=>array('textColor' =>'#666666',
                                'font'=>'2',
                                'fontSize'=>'15'),
         'formTextField'=>array('textColor' =>'#666666',
                                'font'=>'1',
                                'fontSize'=>'14'),
                'button'=>array('textColor' =>'#ffffff',
                                'font'=>'2',
                                'fontSize'=>'18',
                                'backgroundColor'=>'#308afc'),
           'alreadyLogIn'=>array('textColor' =>'#308afc', 
                                'font'=>'2',
                                'fontSize'=>'14', 
                                'backgroundColor'=>'#ffffff'),
                                'placeHolderColors'=>$placeHolderColors,
                        /**/ 
           'customPopUp'=>array('textColor' =>'#ffffff',
                                'font'=>'2',
                                'fontSize'=>'18',
                                'backgroundColor'=>'#308afc',
                                'roundCornerRadious'=>'2',
                 'title'=>array('textColor' =>'#212121',
                                'font'=>'2',
                                'fontSize'=>'16'),
           'description'=>array('textColor' =>'#212121',
                                'font'=>'2',
                                'fontSize'=>'16'),
            'buttonTrue'=>array('textColor' =>'#212121',
                                'font'=>'2',
                                'fontSize'=>'16',
                                'backgroundColor'=>'#ffffff'),
           'buttonFalse'=>array('textColor' =>'#212121',
                                'font'=>'2',
                                'fontSize'=>'16',
                                'backgroundColor'=>'#ffffff'),
          'buttonCommon'=>array('textColor' =>'#212121',
                                'font'=>'2',
                                'fontSize'=>'16',
                                'backgroundColor'=>'#ffffff')),
              'dropDown'=>array('selectedTextColor' =>'#308afc',
                                'backgroundColor'=>'#ffffff',                   
                 'title'=>array('textColor' =>'#000000',
                                'font'=>'2',
                                'fontSize'=>'16'))); 

    $navigation=array('backgroundColor' =>'#ffffff',
       'title'=>array('textColor' =>'#666666',
                      'font'=>'2',
                      'fontSize'=>'16'),
    'subTitle'=>array('textColor' =>'#666666',
                      'font'=>'1',
                      'fontSize'=>'10'),
                      'backButtonColor'=>'#666666',
 'rightButton'=>array('wishListColor' =>'#666666',
                      'shareColor'=>'#666666',
                      'cartColor'=>'#666666',
                      'notificationColor'=>'#666666',
                      'bookmark'=>'#666666'),
      'search'=>array('cancelButtonColor' =>'#666666',
                      'searchIconColor'=>'#DEDEDE',
   'searchbar'=>array('searchbarPlaceholderColor' =>'#666666' ,
       'title'=>array('textColor' =>'#666666',
                      'font'=>'2',
                      'fontSize'=>'14'),'searchCaptionText'=>array('textColor' =>'#666666',
                                                                   'font'=>'1',
                                                                   'fontSize'=>'12'))));

    $tabBar = array('backgroundColor' =>'#ffffff',
                    'selectedColor'=>'#298afc',
                    'unSelectedColor' =>'#666666',
                    'selectedText'=>'#298afc',
                    'unselectedText'=>'#666666');

    $languageData1 = array('id' =>'1',
                            'text'=>'English',
                            'image'=>$baseUrl."pub/media/usa.png",
                            'isSelected'=>'1',
                            'isUIFlip'=>'0'); 
    $languageData2 = array('id' =>'2', 
                            'text'=>'Arabic (عربى)',
                            'image'=>$baseUrl."pub/media/uae.png",
                            'isSelected'=>'0',
                            'isUIFlip'=>'1'); 

    $languageData = array($languageData1,$languageData2 ); 

    $currencyData1 = array('id' =>'1', 
                           'text'=>'Bahraini Dinar (دينار بحريني)',  
                           'image'=>$baseUrl."pub/media/uae.png", 
                           'isSelected'=>'1'); 
    /*$currencyData2 = array('id' =>'2',  
                           'text'=>'Bahraini Dinar (دينار بحريني)', 
                           'image'=>$baseUrl."pub/media/uae.png", 
                           'isSelected'=>'0'); */
    $currencyData = array($currencyData1); 
      
    $languageAndCurrency = array('isMultiLanguage' =>'1',
                        'navDividerColor'=>'#DEDEDE',
                        'defaultLanguageId'=>'1',
                        'defaultIsUIFlip'=>'0', 
                        'defaultCurrencyId'=>'1',
                        'backgroundColor'=>'#FFFFFF',
                        'separatorColor'=>'#DEDEDE',
                        'selectionImageColor'=>'#308afc',
                        'screenTitleText'=>'LANGUAGE & CURRENCY',
                        'buttonText'=>'APPLY',
                        'selectLanguageTitle'=>array('text' =>'Select Language',
                        'textColor'=>'#242424',
                        'font'=>'1',
                        'fontSize'=>'18'),
                        'languageListTitle'=>array('textColor'=>'#666666',
                        'font'=>'2',
                        'fontSize'=>'14'),
                        'selectCurrencyTitle'=>array('text'=>'Select Currency',
                            'textColor'=>'#242424',
                        'font'=>'1',
                        'fontSize'=>'18'),
                        'currencyListTitle'=>array('textColor'=>'#242424',
                        'font'=>'2',
                        'fontSize'=>'18'),'languageData'=>$languageData,'currencyData'=>$currencyData);

  
$userManagement = array('fbImage' => $baseUrl."pub/media/fb.png", 
                        'gPlusImage'=>$baseUrl."pub/media/gp.png", 
                        'termsandConditionUrl'=>$baseUrl."web_component/order/terms-condition.html",
                        'privacyPolicyUrl'=>$baseUrl."web_component/order/privacy-policy.html",
                    'skipButton'=> array('textColor' =>'#666666',
                        'font'=>'2',
                        'fontSize'=>'14'),
                    'signInSignUpTabText'=> array('buttonSelected' =>'#666666',
                        'selectedTextColor' =>'#1f1f1f',
                        'unSelectedTextColor' =>'#626262',
                        'font'=>'2',
                        'fontSize'=>'20'),
                    'signInSignUpTab'=> array('singInFormColor' =>'#FFFFFF',
                        'signUpFormColor' =>'#FFFFFF',
                        'checkboxSelected' =>'#9e9e9e',
                        'checkboxUnselected'=>'#9e9e9e',
                    'rememberTitle' => array('textColor'=>'#9e9e9e',
                                                'font'=>'1',
                                                'fontSize'=>'12'),
                    'forgotTitle' => array('textColor'=>'#9e9e9e',
                                                'font'=>'1',
                                                'fontSize'=>'12'),
                    'termsTitle' => array('textColor'=>'#666666',
                                                'font'=>'1',
                                                'fontSize'=>'12'),
                    'socialTitle' => array('textColor'=>'#666666',
                                                'font'=>'2',
                                                'fontSize'=>'12'))); 

        $verify = array('backgroundColor'=>'#f6f6f6',
                                                'formColor'=>'#FFFFFF',
                        'title'=>array('textColor'=>'#1f1f1f',
                                                'font'=>'2',
                                                'fontSize'=>'18'),
                    'description'=>array('textColor'=>'#6a6a6a',
                                                'font'=>'1',
                                                'fontSize'=>'13'));
        $myAccount = array('screenBackgroundColor'=>'#ffffff',
                                                'arrowColor'=>'#666666',
                                                'dividerColor'=>'#dedede',
                                                'backgroundColor'=>'#ffffff',
                        'title'=>array('textColor'=>'#4A4A4A',
                                                'font'=>'2',
                                                'fontSize'=>'14')); 

        $forgotPassword = array('backgroundColor'=>'#f6f6f6',
                                                'formColor'=>'#FFFFFF',
                        'title'=>array('textColor'=>'#1f1f1f',
                                                'font'=>'2',
                                                'fontSize'=>'18'),
                    'description'=>array('textColor'=>'#6a6a6a',
                                                'font'=>'1',
                                                'fontSize'=>'13'));

        $address = array('backgroundColor'=>'#FFFFFF',
                        'navDividerColor'=>'#DEDEDE',
                        'formColor'=>'#FFFFFF',
                        'locationImageColor'=>'#308afc',
                        'typeTitleBackgroundColor'=>'#FFFFFF',
                        'selectAddressColor'=>'#666666',
                        'unselectAddressColor'=>'#666666', 
                        'locationBoxBackGroundColor'=>'#FFFFFF', 
                        'editAddressColor'=>'#308afc',
        'autoFillLabel'=>array('textColor'=>'#666666',
                        'font'=>'1',
                        'fontSize'=>'13'),
        'currentLocationText'=>array('textColor'=>'#308afc',
                        'font'=>'1',
                        'fontSize'=>'16'),
        'buttonAddAddress'=>array('textColor'=>'#308afc',
                        'font'=>'2',
                        'fontSize'=>'16',
                        'isButtonAddAddressShadowColor'=>'1',
                        'buttonAddAddressShadowColor'=>'#DEDEDE',
                        'buttonAddAddressBackgroundColor'=>'#ffffff'),
        'addressTitle'=>array('textColor'=>'#1f1f1f',
                        'addressCellBackgroundColor'=>'#ffffff',
                        'font'=>'1',
                        'fontSize'=>'14'),
        'description'=>array('textColor'=>'#6a6a6a',
                        'font'=>'1',
                        'fontSize'=>'14'),
                        'typeTitle'=>array('textColor'=>'#666666',
                        'backgroundColor'=>'#DEDEDE',
                        'font'=>'2',
                        'fontSize'=>'14'),
        'addOrChangeAddress'=>array('textColor'=>'#ffffff',
                        'font'=>'2',
                        'fontSize'=>'14',
                        'borderPixel'=>'2',
                        'backgroundColor'=>'#308afc',
                        'borderColor'=>'#308afc',
                        'shadowColor'=>'#dedede',
                        'isShadow'=>'1'));
        $alert = array('backgroundColor' =>'#ffffff',
                        'isShadow'=>'1',
                'title'=>array('textColor' =>'#666666',
                        'font'=>'1',
                        'fontSize'=>'10'),
                        'subtitle'=>array('textColor' =>'#666666',
                        'font'=>'1',
                        'fontSize'=>'10'),
                'positiveButton'=>array('textColor' =>'#ffffff',
                        'font'=>'1',
                        'fontSize'=>'10',
                        'backgroundColor'=>'#666666'),
                'negativeButton'=>array('textColor' =>'#ffffff',
                        'font'=>'1',
                        'fontSize'=>'10',
                        'backgroundColor'=>'#666666')); 

        $changePassword = array('backgroundColor'=>'#ffffff','resend' =>array('textColor' =>'#6a6a6a',
                                            'font'=>'1',
                                            'fontSize'=>'14'
                                            )); 

        $reviewProduct = array('navDividerColor' =>'#DEDEDE',
                    'ratingView'=>array('selectedRating' =>'#2a8afb',
                                'unselectedRating'=>'#f0f0f0'),
                    'productImage'=>array('isShadow' =>'1',
                                'backgroundColor'=>'#DEDEDE'));

    $UISettings = array('fontCodeSet' =>$UISettings1,
                        'font_size'=>$font_size,
                        'fontSizeAndroid'=>$fontSizeAndroid,
                        'generalSetting'=>$generalSetting,
                        'navigation'=>$navigation,
                        'tabBar'=>$tabBar,
                        'languageAndCurrency'=>$languageAndCurrency,
                        'userManagement'=>$userManagement,
                        'verify'=>$verify,
                        'myAccount'=>$myAccount,
                        'forgotPassword'=>$forgotPassword,
                        'address'=>$address,
                        'alert'=>$alert,
                        'changePassword'=>$changePassword,
                        'reviewProduct'=>$reviewProduct); 
    $apiConsole = array('home' => 'index',
                        'allReviews' => 'allReview',
                        'category' => 'categories',  
                        'commonWebComponent' => 'commonWebComponent',
                        'filter' => 'filter', 
                        'labels' => 'labels',
                        'myAccount' => 'myAccount',
                        'myCart' => 'mycart', 
                        'myOrderDetails' => 'myOrderDetails',
                        'myOrders' => 'myOrders',
                        'myPolicies' => 'mypolicies', 
                        'myProfile' => 'myprofile', 
                        'myReviews' => 'myreview',  
                        'notificatoin' => 'notification',
                        'payment' => 'payment', 
                        'popup' => 'popup',
                        'productDetail' => 'productdetail',  
                        'productList' => 'productlist', 
                        'promoCode' => 'promocode',
                        'search' => 'search',
                        'selectVarient' => 'selectvarient', 
                        'singleItemOrderDetailsScreen' => 'singleItemOrderDetailsScreen',
                        'subScreen' => 'subScreen',
                        'wishlist' => 'wishlist', 
                        'signUp'=>'signup',
                        'signIn'=>'login',
                        'addtocart'=>'addtocart', 
                        'addtowishlist'=>'addtowishlist',
                        'addReview'=>'addReview',
                        'applyVarient'=>'applyVarient',
                        'logout'=>'logout',
                        'changepwd'=>'changepwd',
                        'CreateOrder'=>'createorder',
                        'addAddress'=>'addaddress',
                        'selectAddress'=>'selectaddress',
                        'forgotPassword'=>'forgotpwd', 
                        'updateAccountDetails'=>'Updateaccountdetails',
                        'CheckQuantity'=>'CheckQuantity',
                        'ProductDescription'=>'productdescription',
                        'DefalutAddress'=>'defalutaddress',
                        'Updateaddress'=>'updateaddress',
                        'ContactUs'=>'contactus',
                        'applyFilter'=>'applyFilter',
                        'notificationDelete'=>'notificationDelete',
                        'applypromocode'=>'applypromocode',
                        'guestaccount'=>'guestaccount');    

    $countryData = array (
  /*0 => 
  array (
    'name' => 'Afghanistan',
    'code' => 'AF',
  ),
  1 => 
  array (
    'name' => 'land Islands',
    'code' => 'AX',
  ),
  2 => 
  array (
    'name' => 'Albania',
    'code' => 'AL',
  ),
  3 => 
  array (
    'name' => 'Algeria',
    'code' => 'DZ',
  ),
  4 => 
  array (
    'name' => 'American Samoa',
    'code' => 'AS',
  ),
  5 => 
  array (
    'name' => 'AndorrA',
    'code' => 'AD',
  ),
  6 => 
  array (
    'name' => 'Angola',
    'code' => 'AO',
  ),
  7 => 
  array (
    'name' => 'Anguilla',
    'code' => 'AI',
  ),
  8 => 
  array (
    'name' => 'Antarctica',
    'code' => 'AQ',
  ),
  9 => 
  array (
    'name' => 'Antigua and Barbuda',
    'code' => 'AG',
  ),
  10 => 
  array (
    'name' => 'Argentina',
    'code' => 'AR',
  ),
  11 => 
  array (
    'name' => 'Armenia',
    'code' => 'AM',
  ),
  12 => 
  array (
    'name' => 'Aruba',
    'code' => 'AW',
  ),
  13 => 
  array (
    'name' => 'Australia',
    'code' => 'AU',
  ),
  14 => 
  array (
    'name' => 'Austria',
    'code' => 'AT',
  ),
  15 => 
  array (
    'name' => 'Azerbaijan',
    'code' => 'AZ',
  ),
  16 => 
  array (
    'name' => 'Bahamas',
    'code' => 'BS',
  ),*/
  0 => 
  array (
    'name' => 'Bahrain',
    'code' => 'BH',
  )
  /*18 => 
  array (
    'name' => 'Bangladesh',
    'code' => 'BD',
  ),
  19 => 
  array (
    'name' => 'Barbados',
    'code' => 'BB',
  ),
  20 => 
  array (
    'name' => 'Belarus',
    'code' => 'BY',
  ),
  21 => 
  array (
    'name' => 'Belgium',
    'code' => 'BE',
  ),
  22 => 
  array (
    'name' => 'Belize',
    'code' => 'BZ',
  ),
  23 => 
  array (
    'name' => 'Benin',
    'code' => 'BJ',
  ),
  24 => 
  array (
    'name' => 'Bermuda',
    'code' => 'BM',
  ),
  25 => 
  array (
    'name' => 'Bhutan',
    'code' => 'BT',
  ),
  26 => 
  array (
    'name' => 'Bolivia',
    'code' => 'BO',
  ),
  27 => 
  array (
    'name' => 'Bosnia and Herzegovina',
    'code' => 'BA',
  ),
  28 => 
  array (
    'name' => 'Botswana',
    'code' => 'BW',
  ),
  29 => 
  array (
    'name' => 'Bouvet Island',
    'code' => 'BV',
  ),
  30 => 
  array (
    'name' => 'Brazil',
    'code' => 'BR',
  ),
  31 => 
  array (
    'name' => 'British Indian Ocean Territory',
    'code' => 'IO',
  ),
  32 => 
  array (
    'name' => 'Brunei Darussalam',
    'code' => 'BN',
  ),
  33 => 
  array (
    'name' => 'Bulgaria',
    'code' => 'BG',
  ),
  34 => 
  array (
    'name' => 'Burkina Faso',
    'code' => 'BF',
  ),
  35 => 
  array (
    'name' => 'Burundi',
    'code' => 'BI',
  ),
  36 => 
  array (
    'name' => 'Cambodia',
    'code' => 'KH',
  ),
  37 => 
  array (
    'name' => 'Cameroon',
    'code' => 'CM',
  ),
  38 => 
  array (
    'name' => 'Canada',
    'code' => 'CA',
  ),
  39 => 
  array (
    'name' => 'Cape Verde',
    'code' => 'CV',
  ),
  40 => 
  array (
    'name' => 'Cayman Islands',
    'code' => 'KY',
  ),
  41 => 
  array (
    'name' => 'Central African Republic',
    'code' => 'CF',
  ),
  42 => 
  array (
    'name' => 'Chad',
    'code' => 'TD',
  ),
  43 => 
  array (
    'name' => 'Chile',
    'code' => 'CL',
  ),
  44 => 
  array (
    'name' => 'China',
    'code' => 'CN',
  ),
  45 => 
  array (
    'name' => 'Christmas Island',
    'code' => 'CX',
  ),
  46 => 
  array (
    'name' => 'Cocos (Keeling) Islands',
    'code' => 'CC',
  ),
  47 => 
  array (
    'name' => 'Colombia',
    'code' => 'CO',
  ),
  48 => 
  array (
    'name' => 'Comoros',
    'code' => 'KM',
  ),
  49 => 
  array (
    'name' => 'Congo',
    'code' => 'CG',
  ),
  50 => 
  array (
    'name' => 'Congo, The Democratic Republic of the',
    'code' => 'CD',
  ),
  51 => 
  array (
    'name' => 'Cook Islands',
    'code' => 'CK',
  ),
  52 => 
  array (
    'name' => 'Costa Rica',
    'code' => 'CR',
  ),
  53 => 
  array (
    'name' => 'Croatia',
    'code' => 'HR',
  ),
  54 => 
  array (
    'name' => 'Cuba',
    'code' => 'CU',
  ),
  55 => 
  array (
    'name' => 'Cyprus',
    'code' => 'CY',
  ),
  56 => 
  array (
    'name' => 'Czech Republic',
    'code' => 'CZ',
  ),
  57 => 
  array (
    'name' => 'Denmark',
    'code' => 'DK',
  ),
  58 => 
  array (
    'name' => 'Djibouti',
    'code' => 'DJ',
  ),
  59 => 
  array (
    'name' => 'Dominica',
    'code' => 'DM',
  ),
  60 => 
  array (
    'name' => 'Dominican Republic',
    'code' => 'DO',
  ),
  61 => 
  array (
    'name' => 'Ecuador',
    'code' => 'EC',
  ),
  62 => 
  array (
    'name' => 'Egypt',
    'code' => 'EG',
  ),
  63 => 
  array (
    'name' => 'El Salvador',
    'code' => 'SV',
  ),
  64 => 
  array (
    'name' => 'Equatorial Guinea',
    'code' => 'GQ',
  ),
  65 => 
  array (
    'name' => 'Eritrea',
    'code' => 'ER',
  ),
  66 => 
  array (
    'name' => 'Estonia',
    'code' => 'EE',
  ),
  67 => 
  array (
    'name' => 'Ethiopia',
    'code' => 'ET',
  ),
  68 => 
  array (
    'name' => 'Falkland Islands (Malvinas)',
    'code' => 'FK',
  ),
  69 => 
  array (
    'name' => 'Faroe Islands',
    'code' => 'FO',
  ),
  70 => 
  array (
    'name' => 'Fiji',
    'code' => 'FJ',
  ),
  71 => 
  array (
    'name' => 'Finland',
    'code' => 'FI',
  ),
  72 => 
  array (
    'name' => 'France',
    'code' => 'FR',
  ),
  73 => 
  array (
    'name' => 'French Guiana',
    'code' => 'GF',
  ),
  74 =>  
  array (
    'name' => 'French Polynesia',
    'code' => 'PF',
  ),
  75 => 
  array (
    'name' => 'French Southern Territories',
    'code' => 'TF',
  ),
  76 => 
  array (
    'name' => 'Gabon',
    'code' => 'GA',
  ),
  77 => 
  array (
    'name' => 'Gambia',
    'code' => 'GM',
  ),
  78 => 
  array (
    'name' => 'Georgia',
    'code' => 'GE',
  ),
  79 => 
  array (
    'name' => 'Germany',
    'code' => 'DE',
  ),
  80 => 
  array (
    'name' => 'Ghana',
    'code' => 'GH',
  ),
  81 => 
  array (
    'name' => 'Gibraltar',
    'code' => 'GI',
  ),
  82 => 
  array (
    'name' => 'Greece',
    'code' => 'GR',
  ),
  83 => 
  array (
    'name' => 'Greenland',
    'code' => 'GL',
  ),
  84 => 
  array (
    'name' => 'Grenada',
    'code' => 'GD',
  ),
  85 => 
  array (
    'name' => 'Guadeloupe',
    'code' => 'GP',
  ),
  86 => 
  array (
    'name' => 'Guam',
    'code' => 'GU',
  ),
  87 => 
  array (
    'name' => 'Guatemala',
    'code' => 'GT',
  ),
  88 => 
  array (
    'name' => 'Guernsey',
    'code' => 'GG',
  ),
  89 => 
  array (
    'name' => 'Guinea',
    'code' => 'GN',
  ),
  90 => 
  array (
    'name' => 'Guinea-Bissau',
    'code' => 'GW',
  ),
  91 => 
  array (
    'name' => 'Guyana',
    'code' => 'GY',
  ),
  92 => 
  array (
    'name' => 'Haiti',
    'code' => 'HT',
  ),
  93 => 
  array (
    'name' => 'Heard Island and Mcdonald Islands',
    'code' => 'HM',
  ),
  94 => 
  array (
    'name' => 'Holy See (Vatican City State)',
    'code' => 'VA',
  ),
  95 => 
  array (
    'name' => 'Honduras',
    'code' => 'HN',
  ),
  96 => 
  array (
    'name' => 'Hong Kong',
    'code' => 'HK',
  ),
  97 => 
  array (
    'name' => 'Hungary',
    'code' => 'HU',
  ),
  98 => 
  array (
    'name' => 'Iceland',
    'code' => 'IS',
  ),
  99 => 
  array (
    'name' => 'India',
    'code' => 'IN',
  ),
  100 => 
  array (
    'name' => 'Indonesia',
    'code' => 'ID',
  ),
  101 => 
  array (
    'name' => 'Iran, Islamic Republic Of',
    'code' => 'IR',
  ),
  102 => 
  array (
    'name' => 'Iraq',
    'code' => 'IQ',
  ),
  103 => 
  array (
    'name' => 'Ireland',
    'code' => 'IE',
  ),
  104 => 
  array (
    'name' => 'Isle of Man',
    'code' => 'IM',
  ),
  105 => 
  array (
    'name' => 'Israel',
    'code' => 'IL',
  ),
  106 => 
  array (
    'name' => 'Italy',
    'code' => 'IT',
  ),
  107 => 
  array (
    'name' => 'Jamaica',
    'code' => 'JM',
  ),
  108 => 
  array (
    'name' => 'Japan',
    'code' => 'JP',
  ),
  109 => 
  array (
    'name' => 'Jersey',
    'code' => 'JE',
  ),
  110 => 
  array (
    'name' => 'Jordan',
    'code' => 'JO',
  ),
  111 => 
  array (
    'name' => 'Kazakhstan',
    'code' => 'KZ',
  ),
  112 => 
  array (
    'name' => 'Kenya',
    'code' => 'KE',
  ),
  113 => 
  array (
    'name' => 'Kiribati',
    'code' => 'KI',
  ),
  114 => 
  array (
    'name' => 'Kuwait',
    'code' => 'KW',
  ),
  115 => 
  array (
    'name' => 'Kyrgyzstan',
    'code' => 'KG',
  ),
  116 => 
  array (
    'name' => 'Latvia',
    'code' => 'LV',
  ),
  117 => 
  array (
    'name' => 'Lebanon',
    'code' => 'LB',
  ),
  118 => 
  array (
    'name' => 'Lesotho',
    'code' => 'LS',
  ),
  119 => 
  array (
    'name' => 'Liberia',
    'code' => 'LR',
  ),
  120 => 
  array (
    'name' => 'Libyan Arab Jamahiriya',
    'code' => 'LY',
  ),
  121 => 
  array (
    'name' => 'Liechtenstein',
    'code' => 'LI',
  ),
  122 => 
  array (
    'name' => 'Lithuania',
    'code' => 'LT',
  ),
  123 => 
  array (
    'name' => 'Luxembourg',
    'code' => 'LU',
  ),
  124 => 
  array (
    'name' => 'Macao',
    'code' => 'MO',
  ),
  125 => 
  array (
    'name' => 'Macedonia, The Former Yugoslav Republic of',
    'code' => 'MK',
  ),
  126 => 
  array (
    'name' => 'Madagascar',
    'code' => 'MG',
  ),
  127 => 
  array (
    'name' => 'Malawi',
    'code' => 'MW',
  ),
  128 => 
  array (
    'name' => 'Malaysia',
    'code' => 'MY',
  ),
  129 => 
  array (
    'name' => 'Maldives',
    'code' => 'MV',
  ),
  130 => 
  array (
    'name' => 'Mali',
    'code' => 'ML',
  ),
  131 => 
  array (
    'name' => 'Malta',
    'code' => 'MT',
  ),
  132 => 
  array (
    'name' => 'Marshall Islands',
    'code' => 'MH',
  ),
  133 => 
  array (
    'name' => 'Martinique',
    'code' => 'MQ',
  ),
  134 => 
  array (
    'name' => 'Mauritania',
    'code' => 'MR',
  ),
  135 => 
  array (
    'name' => 'Mauritius',
    'code' => 'MU',
  ),
  136 => 
  array (
    'name' => 'Mayotte',
    'code' => 'YT',
  ),
  137 => 
  array (
    'name' => 'Mexico',
    'code' => 'MX',
  ),
  138 => 
  array (
    'name' => 'Micronesia, Federated States of',
    'code' => 'FM',
  ),
  139 => 
  array (
    'name' => 'Moldova, Republic of',
    'code' => 'MD',
  ),
  140 => 
  array (
    'name' => 'Monaco',
    'code' => 'MC',
  ),
  141 => 
  array (
    'name' => 'Mongolia',
    'code' => 'MN',
  ),
  142 => 
  array (
    'name' => 'Montenegro',
    'code' => 'ME',
  ),
  143 => 
  array (
    'name' => 'Montserrat',
    'code' => 'MS',
  ),
  144 => 
  array (
    'name' => 'Morocco',
    'code' => 'MA',
  ),
  145 => 
  array (
    'name' => 'Mozambique',
    'code' => 'MZ',
  ),
  146 => 
  array (
    'name' => 'Myanmar',
    'code' => 'MM',
  ),
  147 => 
  array (
    'name' => 'Namibia',
    'code' => 'NA',
  ),
  148 => 
  array (
    'name' => 'Nauru',
    'code' => 'NR',
  ),
  149 => 
  array (
    'name' => 'Nepal',
    'code' => 'NP',
  ),
  150 => 
  array (
    'name' => 'Netherlands',
    'code' => 'NL',
  ),
  151 => 
  array (
    'name' => 'Netherlands Antilles',
    'code' => 'AN',
  ),
  152 => 
  array (
    'name' => 'New Caledonia',
    'code' => 'NC',
  ),
  153 => 
  array (
    'name' => 'New Zealand',
    'code' => 'NZ',
  ),
  154 => 
  array (
    'name' => 'Nicaragua',
    'code' => 'NI',
  ),
  155 => 
  array (
    'name' => 'Niger',
    'code' => 'NE',
  ),
  156 => 
  array (
    'name' => 'Nigeria',
    'code' => 'NG',
  ),
  157 => 
  array (
    'name' => 'Niue',
    'code' => 'NU',
  ),
  158 => 
  array (
    'name' => 'Norfolk Island',
    'code' => 'NF',
  ),
  159 => 
  array (
    'name' => 'Northern Mariana Islands',
    'code' => 'MP',
  ),
  160 => 
  array (
    'name' => 'Norway',
    'code' => 'NO',
  ),
  161 => 
  array (
    'name' => 'Oman',
    'code' => 'OM',
  ),
  162 => 
  array (
    'name' => 'Pakistan',
    'code' => 'PK',
  ),
  163 => 
  array (
    'name' => 'Palau',
    'code' => 'PW',
  ),
  164 => 
  array (
    'name' => 'Palestinian Territory, Occupied',
    'code' => 'PS',
  ),
  165 => 
  array (
    'name' => 'Panama',
    'code' => 'PA',
  ),
  166 => 
  array (
    'name' => 'Papua New Guinea',
    'code' => 'PG',
  ),
  167 => 
  array (
    'name' => 'Paraguay',
    'code' => 'PY',
  ),
  168 => 
  array (
    'name' => 'Peru',
    'code' => 'PE',
  ),
  169 => 
  array (
    'name' => 'Philippines',
    'code' => 'PH',
  ),
  170 => 
  array (
    'name' => 'Pitcairn',
    'code' => 'PN',
  ),
  171 => 
  array (
    'name' => 'Poland',
    'code' => 'PL',
  ),
  172 => 
  array (
    'name' => 'Portugal',
    'code' => 'PT',
  ),
  173 => 
  array (
    'name' => 'Puerto Rico',
    'code' => 'PR',
  ),
  174 => 
  array (
    'name' => 'Qatar',
    'code' => 'QA',
  ),
  175 => 
  array (
    'name' => 'Reunion',
    'code' => 'RE',
  ),
  176 => 
  array (
    'name' => 'Romania',
    'code' => 'RO',
  ),
  177 => 
  array (
    'name' => 'Russian Federation',
    'code' => 'RU',
  ),
  178 => 
  array (
    'name' => 'RWANDA',
    'code' => 'RW',
  ),
  179 => 
  array (
    'name' => 'Saint Helena',
    'code' => 'SH',
  ),
  180 => 
  array (
    'name' => 'Saint Kitts and Nevis',
    'code' => 'KN',
  ),
  181 => 
  array (
    'name' => 'Saint Lucia',
    'code' => 'LC',
  ),
  182 => 
  array (
    'name' => 'Saint Pierre and Miquelon',
    'code' => 'PM',
  ),
  183 => 
  array (
    'name' => 'Saint Vincent and the Grenadines',
    'code' => 'VC',
  ),
  184 => 
  array (
    'name' => 'Samoa',
    'code' => 'WS',
  ),
  185 => 
  array (
    'name' => 'San Marino',
    'code' => 'SM',
  ),
  186 => 
  array (
    'name' => 'Sao Tome and Principe',
    'code' => 'ST',
  ),
  187 => 
  array (
    'name' => 'Saudi Arabia',
    'code' => 'SA',
  ),
  188 => 
  array (
    'name' => 'Senegal',
    'code' => 'SN',
  ),
  189 => 
  array (
    'name' => 'Serbia',
    'code' => 'RS',
  ),
  190 => 
  array (
    'name' => 'Seychelles',
    'code' => 'SC',
  ),
  191 => 
  array (
    'name' => 'Sierra Leone',
    'code' => 'SL',
  ),
  192 => 
  array (
    'name' => 'Singapore',
    'code' => 'SG',
  ),
  193 => 
  array (
    'name' => 'Slovakia',
    'code' => 'SK',
  ),
  194 => 
  array (
    'name' => 'Slovenia',
    'code' => 'SI',
  ),
  195 => 
  array (
    'name' => 'Solomon Islands',
    'code' => 'SB',
  ),
  196 => 
  array (
    'name' => 'Somalia',
    'code' => 'SO',
  ),
  197 => 
  array (
    'name' => 'South Africa',
    'code' => 'ZA',
  ),
  198 => 
  array (
    'name' => 'South Georgia and the South Sandwich Islands',
    'code' => 'GS',
  ),
  199 => 
  array (
    'name' => 'Spain',
    'code' => 'ES',
  ),
  200 => 
  array (
    'name' => 'Sri Lanka',
    'code' => 'LK',
  ),
  201 => 
  array (
    'name' => 'Sudan',
    'code' => 'SD',
  ),
  202 => 
  array (
    'name' => 'Suriname',
    'code' => 'SR',
  ),
  203 => 
  array (
    'name' => 'Svalbard and Jan Mayen',
    'code' => 'SJ',
  ),
  204 => 
  array (
    'name' => 'Swaziland',
    'code' => 'SZ',
  ),
  205 => 
  array (
    'name' => 'Sweden',
    'code' => 'SE',
  ),
  206 => 
  array (
    'name' => 'Switzerland',
    'code' => 'CH',
  ),
  207 => 
  array (
    'name' => 'Syrian Arab Republic',
    'code' => 'SY',
  ),
  208 => 
  array (
    'name' => 'Taiwan, Province of China',
    'code' => 'TW',
  ),
  209 => 
  array (
    'name' => 'Tajikistan',
    'code' => 'TJ',
  ),
  210 => 
  array (
    'name' => 'Tanzania, United Republic of',
    'code' => 'TZ',
  ),
  211 => 
  array (
    'name' => 'Thailand',
    'code' => 'TH',
  ),
  212 => 
  array (
    'name' => 'Timor-Leste',
    'code' => 'TL',
  ),
  213 => 
  array (
    'name' => 'Togo',
    'code' => 'TG',
  ),
  214 => 
  array (
    'name' => 'Tokelau',
    'code' => 'TK',
  ),
  215 => 
  array (
    'name' => 'Tonga',
    'code' => 'TO',
  ),
  216 => 
  array (
    'name' => 'Trinidad and Tobago',
    'code' => 'TT',
  ),
  217 => 
  array (
    'name' => 'Tunisia',
    'code' => 'TN',
  ),
  218 => 
  array (
    'name' => 'Turkey',
    'code' => 'TR',
  ),
  219 => 
  array (
    'name' => 'Turkmenistan',
    'code' => 'TM',
  ),
  220 => 
  array (
    'name' => 'Turks and Caicos Islands',
    'code' => 'TC',
  ),
  221 => 
  array (
    'name' => 'Tuvalu',
    'code' => 'TV',
  ),
  222 => 
  array (
    'name' => 'Uganda',
    'code' => 'UG',
  ),
  223 => 
  array (
    'name' => 'Ukraine',
    'code' => 'UA',
  ),
  224 => 
  array (
    'name' => 'United Arab Emirates',
    'code' => 'AE',
  ),
  225 => 
  array (
    'name' => 'United Kingdom',
    'code' => 'GB',
  ),
  226 => 
  array (
    'name' => 'United States',
    'code' => 'US',
  ),
  227 => 
  array (
    'name' => 'United States Minor Outlying Islands',
    'code' => 'UM',
  ),
  228 => 
  array (
    'name' => 'Uruguay',
    'code' => 'UY',
  ),
  229 => 
  array (
    'name' => 'Uzbekistan',
    'code' => 'UZ',
  ),
  230 => 
  array (
    'name' => 'Vanuatu',
    'code' => 'VU',
  ),
  231 => 
  array (
    'name' => 'Venezuela',
    'code' => 'VE',
  ),
  232 => 
  array (
    'name' => 'Viet Nam',
    'code' => 'VN',
  ),
  233 => 
  array (
    'name' => 'Virgin Islands, British',
    'code' => 'VG',
  ),
  234 => 
  array (
    'name' => 'Virgin Islands, U.S.',
    'code' => 'VI',
  ),
  235 => 
  array (
    'name' => 'Wallis and Futuna',
    'code' => 'WF',
  ),
  236 => 
  array (
    'name' => 'Western Sahara',
    'code' => 'EH',
  ),
  237 => 
  array (
    'name' => 'Yemen',
    'code' => 'YE',
  ),
  238 => 
  array (
    'name' => 'Zambia',
    'code' => 'ZM',
  ),
  239 => 
  array (
    'name' => 'Zimbabwe',
    'code' => 'ZW',
  ),*/
);         
    $countryUISettings = array('dividerColor' =>'#DEDEDE',
                               'isShadow'=>'1',
                               'shadowColor'=>'#a4a4a4',
                               'backgroundColor'=>'#f1f1f1',
                               'navDividerColor'=>'#DEDEDE',
                               'clearImageColor'=>'#212121',
                'title'=>array('textColor'=>'#212121',
                               'font'=>'1',
                               'fontSize'=>'12'),
          'searchTitle'=>array('textColor'=>'#212121',
                               'font'=>'1',
                               'fontSize'=>'12'));
    $country = array('countryUISettings'=>$countryUISettings,
                     'countryData'=>$countryData);
    $status = array('status' =>'OK', 
                    'statusCode'=>200, 
                    'message'=>'Success',
                    'baseEnvironment'=>'1',
                    'baseURLSandBox'=>$baseUrl, 
                    'baseURL'=>$baseUrl."ecommerceapi/", 
                    'appUnderMaintainenceFlag'=>'0',
                    'pageLoadUrl'=>'Coming%20Soon/07-comming-soon.html',
                    'tokenAuthentication'=>'',
                    'UISettings'=>$UISettings,
                    'apiConsole'=>$apiConsole,
                    /*'countryUISettings'=>$countryUISettings,*/
                    'country'=>$country  
            /*'component'=>$component1*/);

        echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
    }   
}
/* "countryUISettings": {
    "dividerColor": "#DEDEDE",
    "isShadow": "1",
    "shadowColor": "#a4a4a4",
    "backgroundColor": "#f1f1f1",
    "navDividerColor": "#DEDEDE",
      "clearImageColor":"212121",
      "title": {
      "textColor": "#212121",
      "font": "1",
     "fontSize":"12"
    },
      "searchTitle": {
      "textColor": "#212121",
      "font": "1",
     "fontSize":"12"
    }
  },*/ 