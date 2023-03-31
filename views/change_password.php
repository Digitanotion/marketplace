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
    <title>Change My Password | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="./assets/css/translate.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/set.css">

</head>
<body>
<?php include "header-top.php";?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "settings_side__bar.php";?>

            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
               <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="">Change Password</span>
                    </div>
                    <div class="text-center">
                        
                    </div>
                    
                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body w-50 justify-content-center m-5 mx-auto p-0 m-0 sub01" id="pass">
                    <form class="ha-none__display m-5 mx-auto" method="post" action="">
                        <div class="form-floating">
                            <input type="password" id="password" name="chngPasswordCurrent__txt" class="form-control w-75" placeholder="New Password" required>
                            <label for="floatingPassword">Current Password</label>
                            <!-- <span class="spin01"><i class="fa fa-eye fa-2x"></i></span>
                            <span class="spin01 d-none"><i class="fa fa-eye-slash fa-2x"></i></span> -->
                        </div>
                        <div class="form-floating mt-2">
                            <input type="password" id="chngPasswordNew__txt" name="chngPasswordNew__txt" class="form-control w-75" placeholder="New Password" required>
                            <label for="floatingPassword">New Password</label>
                            <!-- <span class="spin01"><i class="fa fa-eye fa-2x"></i></span>
                            <span class="spin01 d-none"><i class="fa fa-eye-slash fa-2x"></i></span> -->
                        </div>
                        <div class="form-floating mt-2">
                            <input type="password" id="confrm-password" name="chngPasswordReNew__txt" class="form-control w-75" placeholder="Re-type new password">
                            <!-- <span class="spin01"><i class="fa fa-eye fa-2x" onclick="" id="eye"></i></span>
                            <span class="spin01 d-none"><i class="fa fa-eye-slash fa-2x" id="eye_slash"></i></span> -->
                            <label for="floatingPassword">Re-type new password</label>
                        </div>
                        <div class="">
                            <p id="message"></p>
                        </div>
                        <div class="mt-3">
                            <input type="submit" name="chngPasswordNew__btn" class="w-75 bg-primary text-light p-2 fs-title-3 rounded-3" onclick="checkPassword()" value="CHANGE">
                        </div>
                        <div class="mt-3 pass1">
                            <a class="text-secondary fs-title-3" href="#">forgot Password?</a>
                        </div>
                    </form>
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
                        echo 'toastr.success("'.$sys_msg['msg'].'");';
                        break;
                    default:
                        echo 'toastr.error("'.$sys_msg['msg'].'");';
                        break;
                }
            }
        ?>
        });
    </script>
</body>
</html>