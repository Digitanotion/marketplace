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
    <title>Disable My Feedbacks | Gaijinmall</title>
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
</head>
<body>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <?php include "header-top.php";?>
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
        <link rel="stylesheet" href="assets/css/seller.css">
        <link rel="stylesheet" href="./assets/css/vertical-menu.css">
        <link rel="stylesheet" href="./assets/css/feed.css">
    
    </head>
    <body>
        <section class="container-fluid m-0 p-0">
            <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <?php include "settings_side__bar.php";?>
                <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                    <div class="ha-profile-url-data__body w-50 justify-content-center m-5 mx-auto p-0 m-0 sub01" id="sub01">
                        <div class="ha-none__display mx-auto d-flex justify-content-between">
                            <span class="fs-title-2"><strong> Recieve messages</strong></span>
                            <span class="form-check form-switch">
                                <input class="form-check-input fs-title-3" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
                            </span>
                        </div>
                        <div class="">
                            <hr>
                        </div>
                        <div class="">
                            <p>Disabling feedback is available only for sellers with Silver promotion plan.</p>
                        </div>
                        <div class="">
                            <hr>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary w-100 text-center p-3" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
        <?php include "footer.php";?>
        <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
        <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
        <script src="./assets/js/vertical-menu.js"></script>
        <script src="./assets/js/feed.js"></script>
    </body>
    </html>
