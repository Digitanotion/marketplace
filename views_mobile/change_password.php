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
if (isset($_POST['chngPasswordNew__btn'])) {
    $newPassword = $_POST['chngPasswordNew__txt'];
    $retypeNewPassword = $_POST['chngPasswordReNew__txt'];
    $currenPass = $_POST['chngPasswordCurrent__txt'];
    if ($newPassword == $retypeNewPassword) {
        $passUpdateResponse = $securityManager_ob->updateUsrPasswordByID($pageUsrID__, $currenPass, $newPassword);
        $sys_msg['msg_type'] = $passUpdateResponse['status'];
        $sys_msg['msg'] = $passUpdateResponse['message'];
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Passwords did not match";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change My Password</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="../views/assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/set.css">
    <script>
        var pageTitle=document.title;
            window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                window.flutter_inappwebview.callHandler('getPageTitles', 1, true, pageTitle)
            });
        </script>
</head>
<body>
    <section class="container-fluid m-0 p-">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt- gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 bg-white  p-0">
                    <!-- Header text -->
                <!-- <div class="py-4 text-center fixed-top bg-white">
                    <div class="">
                        <h6 class="fs-title-5 fw-bolder p-0" id="">CHANGE PASSWORD</h6>
                    </div>
                    <div class="text-center">
                        
                    </div>
                    
                </div> -->
                
                    <!-- Horizontal line -->
                <hr class="m-0 bg-hr-light">

                    <!-- Page body -->
                <div class="ha-profile-url-data__body w-100 justify-content-center px mx-auo pt-4  sub01 under_fixed-elemen" id="pass">
                    <form class="ha-none__display m" method="post" action="">
                        <!-- <div class="text-center">
                            <span>Insert your old password, then new password</span>
                        </div> -->
                        <div class="form-floating">
                            <input type="password" id="password" name="chngPasswordCurrent__txt" class="form-control w-100" placeholder="New Password" required>
                            <label for="floatingPassword">Current Password</label>
                            <!-- <span class="spin01"><i class="fa fa-eye fa-2x"></i></span>
                            <span class="spin01 d-none"><i class="fa fa-eye-slash fa-2x"></i></span> -->
                        </div>
                        <div class="form-floating mt-2">
                            <input type="password" id="chngPasswordNew__txt" name="chngPasswordNew__txt" class="form-control w-100" placeholder="New Password" required>
                            <label for="floatingPassword">New Password</label>
                            <!-- <span class="spin01"><i class="fa fa-eye fa-2x"></i></span>
                            <span class="spin01 d-none"><i class="fa fa-eye-slash fa-2x"></i></span> -->
                        </div>
                        <div class="form-floating mt-2">
                            <input type="password" id="confrm-password" name="chngPasswordReNew__txt" class="form-control w-100" placeholder="Re-type new password">
                            <!-- <span class="spin01"><i class="fa fa-eye fa-2x" onclick="" id="eye"></i></span>
                            <span class="spin01 d-none"><i class="fa fa-eye-slash fa-2x" id="eye_slash"></i></span> -->
                            <label for="floatingPassword">Re-type new password</label>
                        </div>
                        <div class="">
                            <p id="message"></p>
                        </div>
                        <div class="mt-1">
                            <input type="submit" name="chngPasswordNew__btn" class="w-100 btn-mobile btn-primary-mobile text-light border-0 p-2 fs-title-3 rounded-3" onclick="checkPassword()" value="Apply Changes">
                        </div>
                        <!-- <div class="mt-3 pass1">
                            <a class="text-secondary fs-title-3" href="#">forgot Password?</a>
                        </div> -->
                    </form>
                </div>
            </div>
            
        </div>
    </section>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/feed.js"></script>
    <script>
        $(document).ready(function onDocumentReady() {  
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
            if (isset($sys_msg) && !empty($sys_msg)){
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
    </script>
</body>
</html>