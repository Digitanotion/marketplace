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
$getUsrBizInfo="";
if (isset($_GET['view_business'])){
    $getUsrBizInfo=$usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
}
else{
    $getUsrBizInfo=$usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite a friend | Gaijinmall</title>
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
    <link rel="stylesheet" href="./assets/css/adverts.css">
</head>
<body>
<?php include "header-top.php";?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "sidebar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-4 fw-bolder">
                            <!-- <a href="followers.html"><i class="fa fa-angle-left fs-title-4 text-dark fw-bold"></i></a> -->
                            Invite your friend</span>
                    </div>
                    <div class="text-center text-dark"></div>
                </div>

                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body">
                    <div class="ha-none__display w-100 text-center m-5 mx-auto">
                        <div class="d-flex justify-content-between mx-5">
                            <span class="d-flex"><h1><i class="fa fa-whatsapp text-success"></i></h1></i><p class="mt-3">WhatsApp</p></span>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url);?>&text=Hello, you can now buy and sell your goods and services with Gaijinmall. https://gaijinmall.com" target="_new">
                                <button class="p-2 px-4 bg-primary text-white border-0 rounded-3 mt-2">Invite</button>
                            </a>
                        </div>
                        <hr class="m-4">
                        <div class="d-flex justify-content-between mx-5">
                            <span class="d-flex"><h1><i class="fa fa-facebook-f text-primary"></i></h1></i><p class="mt-3">Facebook</p></span>
                            <a target="_new" href="https://www.facebook.com/sharer/sharer.php?u=https://gaijinmall.com">
                                <button class="p-2 px-4 bg-primary text-white border-0 rounded-3 mt-2">Invite</button>
                            </a>
                        </div>
                        <hr class="m-4">
                        <div class="d-flex justify-content-between mx-5">
                            <span class="d-flex"><h1><i class="fa fa-envelope text-danger fs-2"></i></h1></i><p class="mt-3">Send by email</p></span>
                            <a target="_new" href="mailto:?subject=Buy and Sell with Gaijinmall&amp;body=Hello, you can now buy and sell in gaijinmall market place http://gaijinmall.com">
                                <button class="p-2 px-4 bg-primary text-white border-0 rounded-3 mt-2">Invite</button>
                            </a>
                        </div>
                        <hr class="m-4">
                        <div class="d-flex justify-content-between mx-5">
                            <span class="d-flex"><h1><i class="fa fa-twitter text-primary "></i></h1></i><p class="mt-3">Twitter</p></span>
                            <a href="https://twitter.com/share?ref_src=http://gaijinmall.com" target="_new">
                                <button class="p-2 px-4 bg-primary text-white border-0 rounded-3 mt-2">Invite</button>
                            </a>
                        </div>
                        </div>
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
    <script src="./assets/js/adverts.js"></script>
    
</body>
</html>