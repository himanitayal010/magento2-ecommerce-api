<?php 
namespace Magneto\Ecommerceapi\Controller\Labels; 

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

     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
     $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
     $baseUrl= $storeManager->getStore()->getBaseUrl();

     $langId = $_POST["langId"]; 
     if($langId == 1){
     $userManagement = array('SKIPBUTTON' => 'SKIP',
                             'SOCIALTITLE' => 'OR Via Social Account',
                             'SIGNINTITLE' => 'Sign In',
                             'SIGNUPTITLE' => 'Sign Up',
                             'EMAILPHONE' => 'Email / Phone No.',
                             'EMAIL' => 'Email Address',
                             'PASSWORD' => 'Password',
                             'CONFIRMPASSWORD' => 'Confirm Password',
                             'REMEMBERME' => 'Remember me',
                             'FORGOTPASSWORD' => 'Forgot Password?',
                             'LOGIN' => 'LOG IN',
                             'REGISTER' => 'SIGN UP',
                             'FIRST' => 'First Name',
                             'LAST' => 'Last Name',
                             'MOBILE' => 'Mobile No.',
                             'LOYALTYCARD' => 'Loyalty Card No.',
                             'LOYALTYPROGRAM' => 'I want to become a part of Ashraf\'s Loyalty program.',
                             'AGREE' => 'By Signing in, you agree to our',
                             'TERMS' => 'Terms of Services',
                             'AND' => '&',
                             'PRIVACY' => 'Privacy Policy',
                             'SEND' => 'SEND',
                             'FORGOTDESCRIPTION' => 'Enter Your registered email address below. We will send you an email with a link for resetting your password.',
                             'AUTHENTICATION' => 'Authentication',
                             'REGISTEREMAIL' => 'Please enter the code which you have received on your registered email.',
                             'CODE' => 'Enter code here',
                             'AUTHENTICATE' => 'AUTHENTICATE',
                             'RESETPASSWORD' => 'RESET PASSWORD', 
                             'Guest'=>'GUEST ACCOUNT',
                             'alreadyLogIn'=>'ALREADY HAVE AN ACCOUNT?');    
     
     $general = array('APPNAME' => 'Ashrafs', 
                     'HOME' => 'HOME',
                     'CATEGORIES' => 'CATEGORIES',
                     'SERACH' => 'SEARCH',
                     'MYCART' => 'MY CART',
                     'ACCOUNT' => 'ACCOUNT',
                     'NOTIFICATION' => 'NOTIFICATIONS',
                     'ORDERDETAIL' => 'Order Details',
                     'CURRENCY' => 'Currency',
                     'SAVE' => 'SAVE',
                     'MYORDER' => 'MY ORDERS',
                     'PRIVACYPOLICY' => 'PRIVACY POLICY',
                     'CONTACTUS' => 'CONTACT US',
                     'WISHLIST' => 'WISH LIST',
                     'APPLY' => 'APPLY',
                     'BACK' => 'BACK',
                     'PAYMENT' => 'PAYMENT',
                     'VIEWALL' => 'View All',
                     'UPDATEINFORMATION' => 'UPDATE INFORMATION',
                     'CHANGEPASSWORD' => 'CHANGE PASSWORD',
                     'REVIEWPRODUCT' => 'REVIEW PRODUCT',
                     'APPLYPROMOCODE' => 'APPLY PROMOCODE',
                     'MYREVIEWS' => 'MY REVIEWS',
                     'CANCEL' => 'CANCEL',
                     'SELECTVARIANT' => 'SELECT VARIANT',
                     'LEGALPOLICIES' => 'LEGAL POLICIES',
                     'OUTOFSTOCK' => 'Out of stock',
                     'QTY' => 'Qty:',
                     'AllREVIEWS' => 'All REVIEWS',
                     'Quantity'=>'Enter Quantity',
                     'placeholderQuantity'=>'Quantity',
                     'cancel'=>'Cancel',
                     'NETWORKTITLE'=>'No Internet!',  
                     'NETWORKDESCRIPTION'=>'Please check your network connection.',
                     'SERVERTITLE'=>'Server error!',  
                     'SERVERDESCRIPTION'=>'There is some issue connecting to the server.',
                     'ERRORCTA'=>'Retry',
                     'SUBSCREEN'=>'SUBSCREEN',
                     'ALERT'=>'Alert',
                     'CONFIRMDELETE'=>'Are you sure you want to delete this item?');      

     $productListing = array('SORT' => 'SORT',
                             'FILTER' => 'FILTER',
                             'ALL' => 'All',
                             'MOSTRECENT' => 'Most Recent',
                             'ONSALE' => 'On Sale',
                             'PRICELOW' => 'Price - Low to High',
                             'PRICEHIGH' => 'Price - High to Low',
                             'FILTERS' => 'FILTERS',
                             'CLEARALL' => 'Clear All',
                             'ClOSE' => 'CLOSE',
                             'DISCARD' => 'Do you want to discard the changes?',
                             'MODIFIED' => 'You modified some filter. What would you like to do with these changes?',
                             'DISGARDCHANGES' => 'DISCARD CHANGES',
                             'APPLYCHANGES' => 'APPLY CHANGES',
                             'YOURROYALTY' => 'Your Royalty Points');

     $productDetails = array('ADDCART' => 'ADD TO CART',
                             'BUY' => 'BUY NOW');

     $address = array('SELECTADDRESS' => 'SELECT ADDRESS',
                      'DELIVERHERE' => 'DELIVER HERE',
                      'NEWADDRESS' => 'ADD A NEW ADDRESS',
                      'BUTTONNEWADDRESS' => 'Add a new address',
                      'CURRENTLOCATION' => 'Use my current location',
                      'AUTOFILL' => 'Tap to autofill the address fields',
                      'NAME' => 'Name*',
                      'firstname'=>'First Name*',
                      'lastname'=>'Last Name*',
                      'email'=>'Email*',  
                      'LOCALITY' => 'Locality, area, or street*',
                      'FLATNO' => 'Flat no., Building name*',
                      'STATE' => 'State*',
                      'CITY' => 'City*',
                      'PHONE' => 'Phone Number*',
                      'ADDRESSTYPE' => 'Address Type',
                      'HOME' => 'HOME',
                      'WORK' => 'WORK',
                      'UPDATE' => 'UPDATE',
                      'CHANGEORADDADDRESS' => 'CHANGE OR ADD ADDRESS',
                      'MOBILENO'=>'Mobile No*',
                      'ZIPCODE'=>'Zip Code*',
                      'COUNTRY'=>'Country*',
                      'ADDRESS1'=>'Address1*',
                      'ADDRESS2'=>'Address2*');  

     $account = array('ACCOUNT' => 'ACCOUNT',
                      'MYORDER' => 'My Orders',
                      'MYWISHLIST' => 'My Wishlist',
                      'MYREVIEW' => 'My Reviews',
                      'NOTIFICATION' => 'Notifications',
                      'LANGUAGEANDCURRENCY' => 'Language & Currency',
                      'CLEARHISTORY' => 'Clear History',
                      'CONTACTUS' => 'Contact Us',
                      'ACCOUNTSETTING' => 'Account Settings',
                      'LEGAL' => 'Legal',
                      'LOGOUT' => 'Logout',
                      'MYROYALTY' => 'My Royalty points',
                      'CHANGECURRENCY' => 'Change Currency',
                      'CHANGELANGUAGE' => 'Change Language');

     $review = array('WRITEREVIEW' => 'Write Review',
                     'SKPIFINISH' => 'SKIP & FINISH',
                     'ALLREVIEW' => 'All Review',
                     'ADDREVIEW' => 'ADD REVIEW',
                     'REVIEWS' => 'Reviews',
                     'DETAILEDREVIEW' => 'More detailed review get more visiblity...',
                     'REVIEWTITLE'=>'Enter title here...');

     $coupons = array('COUPONCODE' => 'Enter promocode Here...',
                      'PROMOTITLE' => 'Choose the offers from below...',
                      'HAVEAPROMOCODE' => 'Have a promocode?',
                      'REMOVE' => 'REMOVE',
                      'ADDTOWISHLIST' => 'ADD TO WISHLIST');

     $checkout  = array('CONTINUE' => 'CONTINUE',
                        'CHECKOUT' => 'CHECKOUT');

     $myProfile = array('FIRSTNAME' => 'FirstName',
                        'LASTNAME' => 'LastName',
                        'MOBILENO' => 'Mobile Number',
                        'EMAILADDRESS' => 'Email Address',
                        'LOYALTYCARDNO' => 'Loyalty Card Number',
                        'CHANGEPASWORD' => 'Change password',
                        'DEACTIVEACCOUNT' => 'Deactive Account',
                        'UPDATE' => 'UPDATE',
                        'SUBMIT' => 'SUBMIT');

     $searchTab = array('RECENTSEARCH' => 'Your recent search',
                        'SEARCHPLACEHOLDER' => 'Search here...');

     $changePassword = array('OLDPASSWORD' => 'Old Password',
                             'NEWPASSWORD' => 'New Password',
                             'RETYPEPASSWORD' => 'Retype Password',
                             'ENTEROTP' => 'Please enter OTP sent to Faisalrahmankhan@gmail.com',
                             'OTP' => 'OTP',
                             'RESEND' => 'RESEND');

     $writeReviews = array('SKIPANDFINISH' => 'Skip & Finish');

     $logoutUISettings = array('backgroundColor' =>'#ffffff',
                              'roundCornerRadious'=>'2',
                              'closeIconColor'=>'#DEDEDE',
               'title'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16'),
         'description'=> array('textColor' =>'#212121',
                               'font'=>'1',
                               'fontSize'=>'16'),
          'buttonTrue'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16',
                               'backgroundColor'=>'#DEDEDE'),
         'buttonFalse'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16',
                               'backgroundColor'=>'#DEDEDE'),
        'buttonCommon'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16',
                               'backgroundColor'=>'#ffffff'));

        $button1 = array('text' =>'YES' ,
                         'type'=>'buttonTrue',
                         'navigation'=>'' );

        $button2 = array('text' =>'NO' ,
                         'type'=>'buttonFalse',
                         'navigation'=>'' );

        $buttons  = array($button1,$button2);

        $button11 = array('text' =>'YES' ,
                          'type'=>'buttonTrue',
                          'navigation'=>'' );

        $button22 = array('text' =>'NO' ,
                          'type'=>'buttonFalse',  
                          'navigation'=>'' ); 

        $buttonsLogout  = array($button11,$button22);

        $logoutData = array('img' =>$baseUrl."pub/media/inside-logout-icon.png", 
                           'title'=>'LOGOUT',
                           'description'=>'Are you sure you wants to Logout from app. You will not be able to get notifications. Please confirm.',
                           'buttons' => $buttonsLogout);
    
        $logout = array('componentId' => 'logout',
                        'logoutUISettings'=>$logoutUISettings,
                        'logoutData'=>$logoutData); 

        $clearHistoryData = array('img' =>$baseUrl."pub/media/clear-cache-icon.png",
                                  'title'=>'CLEAR CACHE',
                                  'description'=>'Do you wants to clear your cache?', 
                                  'buttons' => $buttons);

              $clearHistory = array('componentId' =>'clearHistory',
    'clearHistoryUISettings'=>array('backgroundColor' =>'#ffffff',
                                    'roundCornerRadious'=>'2',
                                    'closeIconColor'=>'#DEDEDE',
                    'title'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16'),
              'description'=> array('textColor' =>'#212121',
                                    'font'=>'1',
                                    'fontSize'=>'16'),
               'buttonTrue'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
              'buttonFalse'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
             'buttonCommon'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#ffffff')),
                                    'clearHistoryData'=>$clearHistoryData); 
        $button1 = array('text' =>'YES' ,
                         'type'=>'buttonTrue',
                         'navigation'=>'' );

        $button2 = array('text' =>'NO' ,
                         'type'=>'buttonFalse',
                         'navigation'=>'' );

        $buttons  = array($button1,$button2);


        $confirmationData = array('img' =>$baseUrl."pub/media/clear-cache-icon.png",
                                  'title'=>'',
                                  'description'=>'', 
                                  'buttons' => $buttons); 

              $confirmDelete = array('componentId' =>'confirmationDialog',
    'confirmationUISettings'=>array('backgroundColor' =>'#ffffff',
                                    'roundCornerRadious'=>'2',
                                    'closeIconColor'=>'#DEDEDE',
                    'title'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16'),
              'description'=> array('textColor' =>'#212121',
                                    'font'=>'1',
                                    'fontSize'=>'16'),
               'buttonTrue'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
              'buttonFalse'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
             'buttonCommon'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#ffffff')),
                                    'confirmationData'=>$confirmationData);   

     $component1 = array($logout,$clearHistory,$confirmDelete); 
     $errorCode = array('400' =>'Bad Request' ,
                        '401'=>'Unauthorized',
                        '403'=>'Forbidden',
                        '404'=>'Not found',
                        '405'=>'Not allowed',
                        '406'=>'Not acceptable',
                        '500'=>'System Errors',
                        '501'=>'Please enter Email Address',
                        '502'=>'Please enter Password.',
                        '503'=>'Email is not Correct.',
                        '504'=>'Password should contain 8 digit ,Special character and 1 Uppercase.',
                        '505'=>'Email and Password is not match in are records.',
                        '506'=>'Please enter Email Address.',
                        '507'=>'Please enter Password.',
                        '508'=>'Please enter First Name.',
                        '509'=>'Please enter Last Name.',
                        '510'=>'Please enter Loyalty Card No.',
                        '511'=>'Please enter Confirm Password.',
                        '512'=>'Please enter required Fields.',
                        '513'=>'Please enter Mobile Number',
                        '999'=>'Please Check Your Internet Connection.',
                        '514'=> 'Password does not match.',
                        '515'=>'Street Address can not be empty.',
                        '516'=>'Locality/Town can not be empty.',
                        '517'=>'ZipCode can not be empty.', 
                        '518'=>'Address Type cann ot be empty.', 
                        '519'=>'City can not be empty.',
                        '520'=>'Mobile number should be greater than 12 digit.',
                        '521'=>'Please rate the product.',
                        '522'=>'Please provide a detailed review of the product.',
                        '523'=>'Please enter title.');    
     $status = array('status' =>'OK', 
                     'statusCode'=>200, 
                     'message'=>'Success',
                     'isUpdateUISettingFlag'=>'0',
                     'userManagement'=>$userManagement,
                     'general'=>$general,
                     'productListing'=>$productListing,
                     'productDetails'=>$productDetails,
                     'address'=>$address,
                     'account'=>$account,
                     'review'=>$review,
                     'coupons'=>$coupons,
                     'checkout'=>$checkout,
                     'myProfile'=>$myProfile,
                     'searchTab'=>$searchTab,
                     'changePassword'=>$changePassword,
                     'writeReviews'=>$writeReviews,
                     'popup'=>$component1,
                     'errorCode'=>$errorCode);    

       echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 
     }elseif($langId == 2){
        $userManagement = array('SKIPBUTTON' => 'تخطى',
                             'SOCIALTITLE' => 'أو عن طريق الحساب الاجتماعي',
                             'SIGNINTITLE' => 'تسجيل الدخول',
                             'SIGNUPTITLE' => 'سجل',
                             'EMAILPHONE' => 'البريد الإلكتروني / الهاتف',
                             'EMAIL' => 'عنوان بريد الكتروني', 
                             'PASSWORD' => 'كلمه السر',
                             'CONFIRMPASSWORD' => 'تأكيد كلمة المرور',
                             'REMEMBERME' => 'تذكرنى',
                             'FORGOTPASSWORD' => 'هل نسيت كلمة المرور',
                             'LOGIN' => 'تسجيل الدخول',
                             'REGISTER' => 'سجل',
                             'FIRST' => 'الاسم الاول',
                             'LAST' => 'الكنية',
                             'MOBILE' => 'رقم الموبايل',
                             'LOYALTYCARD' => 'رقم بطاقة الولاء',
                             'LOYALTYPROGRAM' => 'أريد أن أصبح جزءًا من برنامج الولاء الخاص بأشرف',
                             'AGREE' => 'عن طريق تسجيل الدخول ، فإنك توافق على موقعنا',
                             'TERMS' => 'شروط الخدمة',
                             'AND' => 'و',
                             'PRIVACY' => 'سياسة خاصة',
                             'SEND' => 'إرسال',
                             'FORGOTDESCRIPTION' => 'أدخل عنوان بريدك الإلكتروني المسجل أدناه. سنرسل لك رسالة بريد إلكتروني تحتوي على',
                             'AUTHENTICATION' => 'المصادقة',
                             'REGISTEREMAIL' => 'يرجى إدخال الرمز الذي تلقيته على بريدك الإلكتروني المسجل',
                             'CODE' => 'أدخل رمز هنا',
                             'AUTHENTICATE' => 'مصادقة',
                             'RESETPASSWORD' => 'إعادة تعيين كلمة المرور', 
                             'Guest'=>'حساب الضيف',
                             'alreadyLogIn'=>'هل لديك حساب؟');     
     $general = array('APPNAME' => 'أشرفص', 
                     'HOME' => 'الصفحة الرئيسية',
                     'CATEGORIES' => 'الاقسام',
                     'SERACH' => 'بحث',
                     'MYCART' => 'المخطط الخاص بي',
                     'ACCOUNT' => 'الحساب',
                     'NOTIFICATION' => 'إشعارات',
                     'ORDERDETAIL' => 'تفاصيل الطلب',
                     'CURRENCY' => 'العملة',
                     'SAVE' => 'حفظ',
                     'MYORDER' => 'طلباتي', 
                     'PRIVACYPOLICY' => 'سياسةالخصوصية',
                     'CONTACTUS' => 'اتصلبنا',
                     'WISHLIST' => 'الأماني',
                     'APPLY' => 'تطبيق',
                     'BACK' => 'BACK',  
                     'PAYMENT' => 'دفع',
                     'VIEWALL' => 'عرض الكل',
                     'UPDATEINFORMATION' => 'تحديث المعلومات',
                     'CHANGEPASSWORD' => 'تغيير كلمة المرور',
                     'REVIEWPRODUCT' => 'مراجعة المنتج',
                     'APPLYPROMOCODE' => 'تطبيق رمز ترويجي',
                     'MYREVIEWS' => ' بلدي الاستعراضات',
                     'CANCEL' => 'إلغاء',
                     'SELECTVARIANT' => 'تحديد تختلف',
                     'LEGALPOLICIES' => 'السياسات القانونية',
                     'OUTOFSTOCK' => 'غير متوفر بالمخزون',
                     'QTY' => 'الكمية',
                     'AllREVIEWS' => 'جميع المراجعات',
                     'Quantity'=>'أدخل الكمية',
                     'placeholderQuantity'=>'الكمية',
                     'cancel'=>'إلغاء',
                     'NETWORKTITLE'=>' لا يوجد إنترنت',  
                     'NETWORKDESCRIPTION'=>'الرجاء التحقق من اتصالك بالشبكة',
                     'SERVERTITLE'=>'خطأ في الخادم',  
                     'SERVERDESCRIPTION'=>'هناك مشكلة في الاتصال بالخادم',
                     'ERRORCTA'=>'إعادة المحاولة ');    

     $productListing = array('SORT' => 'فرز', 
                             'FILTER' => 'منقي',
                             'ALL' => 'الكل',
                             'MOSTRECENT' => 'الأحدث',
                             'ONSALE' => 'للبيع',
                             'PRICELOW' => ' السعر - من الأقل إلى الأعلى',
                             'PRICEHIGH' => 'السعر - مرتفع إلى منخفض',
                             'FILTERS' => 'مرشحات',
                             'CLEARALL' => 'مسح الكل',
                             'ClOSE' => 'أغلق',
                             'DISCARD' => 'هل تريد تجاهل التغييرات؟',
                             'MODIFIED' => 'لقد قمت بتعديل بعض المرشحات. ماذا تريد أن تفعل بهذه التغييرات؟',
                             'DISGARDCHANGES' => ' إلغاء تغييرات',
                             'APPLYCHANGES' => 'تطبيق التغييرات',
                             'YOURROYALTY' => 'نقاط الاتهام الخاص بك'); 

     $productDetails = array('ADDCART' => 'ADD TO CART',
                             'BUY' => 'BUY NOW');

     $address = array('SELECTADDRESS' => 'SELECT ADDRESS',
                      'DELIVERHERE' => 'DELIVER HERE',
                      'NEWADDRESS' => 'ADD A NEW ADDRESS',
                      'BUTTONNEWADDRESS' => 'Add a new address',
                      'CURRENTLOCATION' => 'Use my current location',
                      'AUTOFILL' => 'Tap to autofill the address fields',
                      'NAME' => 'Name*',
                      'firstname'=>'First Name*',
                      'lastname'=>'Last Name*',
                      'email'=>'Email*',  
                      'LOCALITY' => 'Locality, area, or street*',
                      'FLATNO' => 'Flat no., Building name*',
                      'STATE' => 'State*',
                      'CITY' => 'City*',
                      'PHONE' => 'Phone Number*',
                      'ADDRESSTYPE' => 'Address Type',
                      'HOME' => 'HOME',
                      'WORK' => 'WORK',
                      'UPDATE' => 'UPDATE',
                      'CHANGEORADDADDRESS' => 'CHANGE OR ADD ADDRESS',
                      'MOBILENO'=>'Mobile No*',
                      'ZIPCODE'=>'Zip Code*');  

     $account = array('ACCOUNT' => 'الحساب',
                      'MYORDER' => 'طلبي',
                      'MYWISHLIST' => 'قائمة امنياتي',
                      'MYREVIEW' => 'تقييمي',
                      'NOTIFICATION' => 'الإشعارات',
                      'LANGUAGEANDCURRENCY' => 'اللغة والعملات',
                      'CLEARHISTORY' => ' مسح السجل',
                      'CONTACTUS' => ' اتصل بنا',
                      'ACCOUNTSETTING' => 'إعدادات الحساب',
                      'LEGAL' => 'قانوني',
                      'LOGOUT' => 'الخروج',
                      'MYROYALTY' => 'نقاط التفضيل الخاصة بي',
                      'CHANGECURRENCY' => 'تغيير العملة',
                      'CHANGELANGUAGE' => 'تغيير اللغة'); 

     $review = array('WRITEREVIEW' => 'كتابة مراجعة', 
                     'SKPIFINISH' => 'تخطي الانتهاء',
                     'ALLREVIEW' => 'إضافة مراجعة',
                     'ADDREVIEW' => 'إضافة مراجعة',
                     'REVIEWS' => 'مراجعات',
                     'DETAILEDREVIEW' => 'مراجعة أكثر تفصيلاً للحصول على مزيد من المرونة');

     $coupons = array('COUPANCODE' => 'أدخل الرمز التوضيحي هنا ',
                      'PROMOTITLE' => 'اختر العروض من الأسفل ',
                      'HAVEAPROMOCODE' => 'هل يمتلك الرمز الترويجي؟',
                      'REMOVE' => 'إزالة',
                      'ADDTOWISHLIST' => 'أضف إلى قائمة الامنيات'); 

     $checkout  = array('CONTINUE' => 'استمر',
                        'CHECKOUT' => 'الدفع');

     $myProfile = array('FIRSTNAME' => 'الاسم الاول',
                        'LASTNAME' => 'الكنية',
                        'MOBILENO' => 'رقم الهاتف المحمول',
                        'EMAILADDRESS' => 'عنوان بريد الكتروني',
                        'LOYALTYCARDNO' => 'رقم بطاقة الولاء',
                        'CHANGEPASWORD' => 'غير كلمة السر',
                        'DEACTIVEACCOUNT' => 'تعطيل الحساب',
                        'UPDATE' => 'تحديث',
                        'SUBMIT' => 'خضع'); 

     $searchTab = array('RECENTSEARCH' => 'بحثك الأخير',
                        'SEARCHPLACEHOLDER' => 'ابحث هنا...');

     $changePassword = array('OLDPASSWORD' => 'كلمة المرور القديمة',
                            'NEWPASSWORD' => 'كلمة السر الجديدة',
                            'RETYPEPASSWORD' => 'أعد إدخال كلمة السر',
                            'ENTEROTP' => 'يرجى إدخال كلمة المرور الخاصة بك التي تم إرسالها إلى فيصل رحمن خان@جوجل.كوم',
                            'OTP' => 'مكتب المدعي العام',
                            'RESEND' => 'إعادة إرسال');  

     $writeReviews = array('SKIPANDFINISH' => 'Skip & Finish');

     $logoutUISettings = array('backgroundColor' =>'#ffffff',
                              'roundCornerRadious'=>'2',
                              'closeIconColor'=>'#DEDEDE',
               'title'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16'),
         'description'=> array('textColor' =>'#212121',
                               'font'=>'1',
                               'fontSize'=>'16'),
          'buttonTrue'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16',
                               'backgroundColor'=>'#DEDEDE'),
         'buttonFalse'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16',
                               'backgroundColor'=>'#DEDEDE'),
        'buttonCommon'=> array('textColor' =>'#212121',
                               'font'=>'2',
                               'fontSize'=>'16',
                               'backgroundColor'=>'#ffffff'));

        $button1 = array('text' =>'YES' ,
                         'type'=>'buttonTrue',
                         'navigation'=>'' );

        $button2 = array('text' =>'NO' ,
                         'type'=>'buttonFalse',
                         'navigation'=>'' );

        $buttons  = array($button1,$button2);

        $button11 = array('text' =>'YES' ,
                          'type'=>'buttonTrue',
                          'navigation'=>'' );

        $button22 = array('text' =>'NO' ,
                          'type'=>'buttonFalse',  
                          'navigation'=>'' ); 

        $buttonsLogout  = array($button11,$button22);

        $logoutData = array('img' =>$baseUrl."pub/media/inside-logout-icon.png",
                           'title'=>'CLEAR CACHE',
                           'description'=>'Are you sure you wants to Logout from app. You will not be able to get notifications. Please confirm.',
                           'buttons' => $buttonsLogout);
    
        $logout = array('componentId' => 'logout',
                        'logoutUISettings'=>$logoutUISettings,
                        'logoutData'=>$logoutData); 

        $clearHistoryData = array('img' =>$baseUrl."pub/media/clear-cache-icon.png",  
                                  'title'=>'LOGOUT',
                                  'description'=>'Do you wants to clear your cache?',
                                  'buttons' => $buttons);

              $clearHistory = array('componentId' =>'clearHistory',
    'clearHistoryUISettings'=>array('backgroundColor' =>'#ffffff',
                                    'roundCornerRadious'=>'2',
                                    'closeIconColor'=>'#DEDEDE',
                    'title'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16'),
              'description'=> array('textColor' =>'#212121',
                                    'font'=>'1',
                                    'fontSize'=>'16'),
               'buttonTrue'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
              'buttonFalse'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
             'buttonCommon'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#ffffff')),
                                    'clearHistoryData'=>$clearHistoryData); 
               $button1 = array('text' =>'YES' ,
                         'type'=>'buttonTrue',
                         'navigation'=>'' );

        $button2 = array('text' =>'NO' ,
                         'type'=>'buttonFalse',
                         'navigation'=>'' );

        $buttons  = array($button1,$button2);


        $confirmationData = array('img' =>$baseUrl."pub/media/clear-cache-icon.png",
                                  'title'=>'',
                                  'description'=>'', 
                                  'buttons' => $buttons); 

              $confirmDelete = array('componentId' =>'confirmationDialog',
    'confirmationUISettings'=>array('backgroundColor' =>'#ffffff',
                                    'roundCornerRadious'=>'2',
                                    'closeIconColor'=>'#DEDEDE',
                    'title'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16'),
              'description'=> array('textColor' =>'#212121',
                                    'font'=>'1',
                                    'fontSize'=>'16'),
               'buttonTrue'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
              'buttonFalse'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#DEDEDE'),
             'buttonCommon'=> array('textColor' =>'#212121',
                                    'font'=>'2',
                                    'fontSize'=>'16',
                                    'backgroundColor'=>'#ffffff')),
                                    'confirmationData'=>$confirmationData);

     $component1 = array($logout,$clearHistory,$confirmDelete);  
     $errorCode = array('400' =>'اقتراح غير جيد' ,
                        '401'=>'غير مصرح',
                        '403'=>'ممنوع',
                        '404'=>'غير معثور عليه',
                        '405'=>'غير مسموح',
                        '406'=>'غير مقبول',
                        '500'=>'أخطاء النظام',
                        '501'=>'الرجاء إدخال عنوان البريد الإلكتروني',
                        '502'=>'من فضلك أدخل كلمة المرور.',
                        '503'=>'البريد الإلكتروني ليس صحيحًا.',
                        '504'=>'يجب أن تحتوي كلمة المرور على 8 أرقام وحرف خاص وحرف واحد كبير.',
                        '505'=>'البريد الإلكتروني وكلمة المرور غير متطابقين في السجلات.',
                        '506'=>'الرجاء إدخال عنوان البريد الإلكتروني.',
                        '507'=>'من فضلك أدخل كلمة المرور.',
                        '508'=>'يرجى إدخال الاسم الأول.',
                        '509'=>'يرجى إدخال اسم العائلة.',
                        '510'=>'يرجى إدخال رقم بطاقة الولاء',
                        '511'=>'الرجاء إدخال تأكيد كلمة المرور.',
                        '512'=>'يرجى إدخال الحقول المطلوبة.',
                        '513'=>'يرجى إدخال رقم الجوال',
                        '999'=>'الرجاء التحقق من اتصال الانترنت الخاص بك.',
                        '514'=> 'كلمة السر غير متطابقة.',
                        '515'=>'لا يمكن أن يكون عنوان الشارع خاليًا.',
                        '516'=>'لا يمكن أن تكون المنطقة / البلدة فارغة.',
                        '517'=>'لا يمكن أن يكون الرمز البريدي فارغًا.', 
                        '518'=>'يمكن أن يكون نوع العنوان فارغًا.', 
                        '519'=>'المدينة لا يمكن أن تكون فارغة.',
                        '520'=>'يجب أن يكون رقم الهاتف المحمول أكبر من 12 رقمًا.',
                        '521'=>'يرجى تقييم المنتج.',
                        '522'=>'يرجى تقديم استعراض مفصل للمنتج.');    
     $status = array('status' =>'OK', 
                     'statusCode'=>200, 
                     'message'=>'Success',
                     'isUpdateUISettingFlag'=>'0',
                     'userManagement'=>$userManagement,
                     'general'=>$general,
                     'productListing'=>$productListing,
                     'productDetails'=>$productDetails,
                     'address'=>$address,
                     'account'=>$account,
                     'review'=>$review,
                     'coupons'=>$coupons,
                     'checkout'=>$checkout,
                     'myProfile'=>$myProfile,
                     'searchTab'=>$searchTab,
                     'changePassword'=>$changePassword,
                     'writeReviews'=>$writeReviews,
                     'popup'=>$component1,
                     'errorCode'=>$errorCode);   

       echo $status1 = json_encode($status,JSON_UNESCAPED_SLASHES); 

     }else{
          $status = array('status' =>'OK', 
                     'statusCode'=>300, 
                     'message'=>'No Data Found!');

            echo $status1 = json_encode($status);        
     }
    } 
} 
