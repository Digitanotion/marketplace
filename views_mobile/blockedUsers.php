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
if (!$securityManager_ob->is_user_auth__()) {
    //header("location: " . MALL_ROOT . "/Signin.php");
}
//echo "<h1>" . $_GET['user_mob_id__'] . "</h1>";
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = $_GET['user_mob_id__']; //(isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";
if (isset($_GET['unblock'])&&$_GET['unblock']==1&&isset($_GET['seller'])&&$_GET['seller']!="") {
    $unblockUser = $usrAccManager_ob->unblockUserByID($pageUsrID__, $_GET['seller']);
    $sys_msg['msg_type'] = $unblockUser['status'];
    $sys_msg['msg'] = $unblockUser['message'];
}
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
//$getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
if (isset($_GET['view_business'])) {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
} else {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked List</title>
    <script>
        var pageTitle = document.title;
        window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
            window.flutter_inappwebview.callHandler('getPageTitles', 1, true, pageTitle)
        });
    </script>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="views/assets/css/personal-buiness.css">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="../dependencies/node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="assets/css/cute-alert.css">

</head>

<body>
    <section class="container-fluid  m-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-md-8 col-lg-8 col-sm-12 px-2" id="demo000">

                <!-- Personal__Details-header_text -->
                <!--<div class="py-4 text-center fixed-top bg-white">
                    <div class="">
                        <h6 class="fs-title-5 fw-bolder m-0"> PERSONAL DETAILS</h6>
                    </div>
                    <div class="text-center">
                         <button class="rounded-pill border-0" id="demo0" style="background-color: rgb(72, 72, 250);"><span class="btn p-1" style="color: white;">Saved</span></button>
                    </div>

                </div> -->

                <!-- Personal__Details-horizontal_line -->
                <!-- <hr class="m-0 bg-hr-light"> -->


                <!-- <div class="ha-profile-url-data__body mt-3" id="demo00">
                    <div class="ha-none__display text-center mt-1 mb-1 imguser">
                        <i class="fa fa-user-circle fa-9x" style="color: blue;"></i>
                    </div>
                </div> -->

                <!-- Personal__Details-form_input section -->
                <div class="ha-none__display w-100 text-center">
                <?php 
                $getAllBlockedUser=$usrAccManager_ob->getAllUsersBlockedByID($pageUsrID__);
                if ($getAllBlockedUser['status']==1){
                    //CODE DISPLAY ALL BLOCKED USER
                    foreach ($getAllBlockedUser['message'] as $fields) {
                        $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($fields['mallSellerID'])['message'];
                    ?>
                    <div class="row d-flex justify-content-start text-start px-2 my-1 rounded py-3 bg-white fs-md-2">
                        <div class="col-9"><?php echo $getUsrBizInfo['mallBizName'];?>
                            <div class="fs-sm-1"><?php echo date("d M, y. h:m", $fields['mallBlockTime']);?></div>
                        </div>
                        <a href="?user_mob_id__=<?php echo $fields['mallUsrID'];?>&unblock=1&seller=<?php echo $fields['mallSellerID'];?>" target="_self" class="col-3 d-flex align-items-center justify-content-center badge bg-primary fw-bold"><i class="fa fa-unlock m-0 pe-1"></i> Unblock</a>
                    </div>
                    <?php
                }
            }
                else{
                    echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
                    <img class="img-fluid mx-auto" src="./assets/images/notfound3.svg" id="adverts1">
                    <div class="fs-title-4 fw-bolder" id="adverts2">Block list is empty</div>
                    <div class="fs-md" id="adverts3">You have no blocked seller yet.</div>
                </div>';
                }
                ?>
                    

                    


                </div>
            </div>
        </div>


    </section>
</body>
<!-- <script src="./assets/js/personalbusiness.js"></script> -->
<script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
<script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="./assets/js/vertical-menu.js"></script>
<!-- <script src="./assets/js/adverts.js"></script> -->
<script src="./assets/js/userAdmin.js"></script>
<!-- <script src="./assets/js/main.js"></script> -->
<script src="assets/js/cute-alert.js"></script>
<script>
    $(document).ready(function onDocumentReady() {
        // LOCATION AND CITY HANDLERS

        toastr.options = {
            //   "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            //   "preventDuplicates": false,
            //   "onclick": null,
            //   "showDuration": "300",
            //   "hideDuration": "1000",
            //   "timeOut": "5000",
            //   "extendedTimeOut": "1000",
            //   "showEasing": "swing",
            //   "hideEasing": "linear",
            //   "showMethod": "fadeIn",
            //   "hideMethod": "fadeOut"
        }
        <?php
        if (isset($sys_msg) && !empty($sys_msg)) {
            switch ($sys_msg['msg_type']) {

                case '1':
                    echo '
                    window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                        window.flutter_inappwebview.callHandler("getPageAlert", "success", "", "' . $sys_msg['msg'] . '")
                    });
                    ';
                    break;
                default:
                    echo '
                    window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                        window.flutter_inappwebview.callHandler("getPageAlert", "error", "", "' . $sys_msg['msg'] . '")
                    });';
                    break;
            }
        }
        ?>
    });
    $(".select2").select2({
        theme: "bootstrap-5",
    })
</script>

</html>