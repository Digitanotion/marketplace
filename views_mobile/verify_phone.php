<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else if (strpos($url,'192.168')){
    require_once(__DIR__ . "\../vendor/autoload.php");
}else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
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
    header("location: " . MALL_ROOT . "/Signin.php");
}
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = $_SESSION['gaijinmall_user_'];
$isUsrIDUploaded = $usrAccManager_ob->usrIDUploadedStatusByID($pageUsrID__);
$getUsrPhoneVerified=$usrAccManager_ob->getUserVerifiedNumberByID($pageUsrID__);

if (isset($_POST['edit_phone__btn'])){
    $editPhoneResponse=$usrAccManager_ob->updateUsrPhoneByID($pageUsrID__,$_POST['edit_phone__txt'],$_POST['tokenCode']);
    $sys_msg['msg_type'] = $editPhoneResponse['status'];
    $sys_msg['msg'] = $editPhoneResponse['message'];
}
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message']; //
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Phone | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="views/assets/css/personal-buiness.css">
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
    <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
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
    <?php include "header-top.php"; ?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <?php include "settings_side__bar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="prof1">Change phone number</span>
                    </div>
                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body">
                    <div class="ha-none__display w-75 text-center m-5 mx-auto">
                        <div class="number_0" id="number0">
                            <div class="alert bg-info fs-md fw-bold "><i class="fa fa-info-circle"></i> We sent a verification code to your phone now, please check you sms.</div>
                            <form action="" method="POST" id="verify_phone_token">
                                <span class="d-flex justify-content-center w-100">
                                    <input type="hidden" name="edit_phone__txt" id="edit_phone__txt" value="<?php echo $_GET['phone'] ?>">
                                    <input type="text" id="tokenCode" placeholder="Enter code here" name="tokenCode" class="form-control">
                                    <input type="hidden" name="submit_Verify">
                                    <button class=" btn btn-primary bg-light ms-2 rounded text-primary fw-bold" id="edit_phone__btn" name="edit_phone__btn" type="submit"> Verify</button>
                                </span>
                                <span class="d-flex m-2 " >
                                    

                                    <p class="opacity-75 fs-md d-none" style="cursor:pointer;" id="resend_verify_token">Did not get code? <span class="text-primary">Resend</span></p>
                                </span>
                                
                            </form>

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
    <script src="assets/js/userAdmin.js"></script>
    <script src="assets/js/cute-alert.js"></script>
    <script>
        $(document).ready(function onDocumentReady() {
            $('#resend_verify_token').hide();
                                    setTimeout(() => {
                                        $('#resend_verify_token').show();
                                        $('#resend_verify_token').removeClass("d-none");
                                    }, 40000);
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
    </script>
    <!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
</body>

</html>