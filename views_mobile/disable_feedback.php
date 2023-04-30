<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} else if (strpos($url, '192.168')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}

use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\AccS\AccountManager;

$securityManager_ob = new SecurityManager();
$adsManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$sys_msg = []; //Hold data for the toasts
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg = [];
if (!$securityManager_ob->is_user_auth__()) {
    //header("location: ".MALL_ROOT."/Signin.php");
}
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = $_GET['user_mob_id__'];
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrFeedStatus = $usrAccManager_ob->getUsrOptionsStatus($pageUsrID__);
$hasUserPromotedAd = $adsManager_ob->hasUserPromotedAd($pageUsrID__);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disable My Feedbacks</title>
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
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/set.css">
    <link rel="stylesheet" href="assets/css/cute-alert.css">
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
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Seller Profile</title>
        <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
        <link rel="stylesheet" href="./assets/fonts/inter/style.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./assets/css/style.css">
        <link rel="stylesheet" href="../views/assets/css/seller.css">
        <link rel="stylesheet" href="./assets/css/vertical-menu.css">
        <link rel="stylesheet" href="./assets/css/feed.css">

    </head>

    <body class="bg-white">
        <section class="container-fluid m-0">
            <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 gx-0 gx-md-5 gx-lg-5 justify-content-between">
                <div class="col-md-8 col-lg-8 col-sm-12 p-0 bg-white  p-0">
                    <!-- Header text -->
                    <!-- <div class="py-4 text-center fixed-top bg-white"> -->
                    <!-- <div class="">
                            <h6 class="fs-title-5 fw-bolder p-0" id="header01"><?php echo ($getUsrFeedStatus['status'] == 1) ? (($getUsrFeedStatus['message']['mallUsrFeedbacks'] == 1) ? "DISABLE" : "ENABLE") : "DISABLE"; ?>  FEEDBACKS</h6>
                            
                        </div> -->
                    <!-- <div class="text-center">
                            
                        </div> -->

                    <!-- </div> -->

                    <!-- Horizontal line -->
                    <!-- <hr class="m-0 bg-hr-light"> -->

                    <!-- Page body -->
                    <div class="ha-profile-url-data__bod w-100 justify-content-center  mx-auto pt-4 sub01 under_fixed-elemen" id="sub01">
                        <div class="">
                            <p class="fs-md-2">Disabling feedback is available only for sellers with Silver promotion plan.</p>
                        </div>
                        <div class="">
                            <hr>
                        </div>
                        <div class="ha-none__displa mx-auto d-flex justify-content-between">
                            <span class="fs-title-2"><strong> Recieve messages</strong></span>
                            <span class="form-check form-switch">
                                <span class="loading-containter"></span>
                                <input class="form-check-input fs-title-3 disableFeed__Btn" <?php echo ($getUsrFeedStatus['status'] == 1) ? (($getUsrFeedStatus['message']['mallUsrFeedbacks'] == 1) ? "checked" : "") : "checked"; ?> data-userID="<?php echo $pageUsrID__; ?>" type="checkbox" onclick="switchFeedback()" role="switch" id="flexSwitchCheckDisabled" <?php echo ($hasUserPromotedAd['status'] == 1) ? "" : "disabled"; ?>>
                            </span>

                        </div>



                    </div>
                </div>

            </div>
        </section>
        <!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
<script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
<script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
<script src="./assets/js/vertical-menu.js"></script>
<script src="./assets/js/feed.js"></script>
<script src="./assets/js/userAdmin.js"></script>
<script src="assets/js/cute-alert.js"></script>
    </body>

    </html>