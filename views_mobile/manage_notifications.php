<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}  
USE services\SecS\SecurityManager;
USE services\AdS\AdManager;
USE services\AccS\AccountManager;
$securityManager_ob=new SecurityManager();
$adsManager_ob=new AdManager();
$usrAccManager_ob=new AccountManager();
$sys_msg=[]; //Hold data for the toasts
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg=[];
if (!$securityManager_ob->is_user_auth__()){
    header("location: ".MALL_ROOT."/Signin.php");
}
if (isset($_GET['logout'])&&$_GET['logout']==1){
    if ($securityManager_ob->endUserSession()){
        header("location: ".MALL_ROOT);
    }
    else{
        $sys_msg['msg_type']=500;
        $sys_msg['msg']="Could not log out";
    }
} 
$pageUsrID__=$_SESSION['gaijinmall_user_'];
$getUsrInfo=$usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/translate.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/set.css">
    <!-- google translator  -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'ja' }, 'google_translate_element');
        }
    </script>
         <!-- google translator  -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
</head>
<body>
<?php include "header-top.php";?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "settings_side__bar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
            <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="header01">Manage Notifications</span>
                    </div>
                    <div class="text-center">
                        
                    </div>
                    
                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body w-50 justify-content-center mx-auto p-0 m-0 sub01" id="switches">
                    
                    <div class="ha-none__display mt-5 mx-auto d-flex justify-content-between">
                        <span class="fs-title-2"> Hot deals and notifications</span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                    <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                    <div class="ha-none__display mx-auto d-flex justify-content-between">
                        <span class="fs-title-2"> Info about your Ads</span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                    <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                    <div class="ha-none__display mx-auto d-flex justify-content-between">
                        <span class="fs-title-2">Premium packages </span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                    <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                    <div class="ha-none__display mx-auto d-flex justify-content-between">
                        <span class="fs-title-2">Your Subscriptions</span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                     <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                    <div class="ha-none__display mx-auto d-flex justify-content-between">
                        <span class="fs-title-2">messages </span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                     <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                    <div class="ha-none__displaY mx-auto d-flex justify-content-between">
                        <span class="fs-title-2">Feedbacks</span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                    <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                    <div class="ha-none__display mx-auto d-flex justify-content-between">
                        <span class="fs-title-2">SMS info notification</span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
                    <div class=" text-center mx-auto p-0 m-0">
                        <hr>
                    </div>
                     <div class="ha-none__display mx-auto d-flex justify-content-between">
                        <span class="fs-title-2"> Web notifications</span>
                        <span class="form-check form-switch">
                            <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </span>
                    </div>
               </div> 
            </div>
        </div>
    </section>
    <?php include "footer.php";?>
    <!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/feed.js"></script>
</body>
</html>