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
USE services\SecS\SecurityManager;
USE services\AdS\AdManager;
USE services\AccS\AccountManager;
USE services\MsgS\messagingManager;
$securityManager_ob=new SecurityManager();
$adsManager_ob=new AdManager();
$usrAccManager_ob=new AccountManager();
$usrMessage_ob=new messagingManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg=[];
if (!$securityManager_ob->is_user_auth__()){
    //header("location: ".MALL_ROOT."/Signin.php");
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
$pageUsrID__=$_GET['user_mob_id__'];
$getUsrInfo=$usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrBizInfo="";
if (isset($_GET['view_business'])){
    $getUsrBizInfo=$usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
}
else{
    $getUsrBizInfo=$usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}

$getAllNotifications=$usrMessage_ob->getAllUsrNotifi_ByID($pageUsrID__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications</title>
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
    <link rel="stylesheet" href="./assets/css/adverts.css">
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
    <section class="container-fluid m-0 p-0 ">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm  rounded-3 p-0">
                
                <div class="ha-profile-url-data__body">
                    <?php if ($getAllNotifications['status']==1){ 
                        foreach ($getAllNotifications['message'] as $notificatioEach){?>
                    <div class="text-dark" id="notifItems">
                       <div class="bg-light-blue m-1 mx-3">
                            <p class="w-md-75 w-lg-75 w-sm-100 px-3 pt-3 fs-md-1"><?php echo $notificatioEach['mallNotifyContent']?></p>
                            <div class="d-flex px-1 pb-2 opacity-50 align-items-baseline">
                                <p class="fs-md mb-2 p-0"><i class="fa fa-clock"></i> <?php echo date("d M, Y", $notificatioEach['mallNotifyTime']);?></p>
                                <p class="px-4 fs-sm  p-0">From <b>Gaijinmall</b></p>
                        </div>
                        </div> 
                    </div>
                    <?php }}else{?>
                    <div class="ha-none__display w-50 text-center m-5 mx-auto bg_image" id="bg_image0">
                        <img class="img-fluid w-75 mx-auto" src="./assets/images/bg-03.png">
                        <div class="fs-md opacity-50"><p>You don't have any notifications right now</p></div>
                    </div>
                    <?php }?>
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
    <script src="./assets/js/adverts.js"></script>
    <script src="./assets/js/settings.js"></script>
    
</body>
</html>