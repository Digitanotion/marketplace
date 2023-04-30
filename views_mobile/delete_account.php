<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
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
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
if (isset($_POST['delAccount__btn'])){
    echo "POST";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete My Account Forever | Gaijinmall</title>
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
    <section class="container-fluid m-0 p-0 pb-3">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <?php include "settings_side__bar.php"; ?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="">Delete Account </span>
                    </div>
                    <div class="text-center">

                    </div>

                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body w-50 mx-auto drop1" id="drop01">
                    <form class="ha-none__display" action="" method="POST" id="deleteAccForever__Form">
                        <div class="py-4">
                            <p>Account deactivation means to delete your account:</p>
                            <p>You will not be able to log in to your profile anymore and all your account history will be deleted without the possibility to restore</p>
                        </div>
                        <div class="mt-3 form-floating formsel">
                            <select class="form-select delAccReason" id="floatingSelect" aria-label="Floating label select example" >
                                <option selected>Why do you leave?</option>
                                <option value="I Have a duplicate account">I Have a duplicate account</option>
                                <option >I get too many notifications</option>
                                <option>I haven't found anything interesting on Gaijinmall</option>
                                <option class="otherReason" value="other">Other reason</option>
                            </select>
                            <label for="floatingSelect">Why Do you leave</label>
                        </div>
                        <div class="mt-3 form-floating otherReasonContainer">
                        
                        </div>
                        <div class="mt-4 form-group pb-3">
                            <input type="button" data-userID="<?php echo $pageUsrID__;?>" id="delAccount__btn" name="delAccount__btn" class="btn btn-primary w-100 text-light p-2 fs-title-3 rounded-3" value="Delete forever">
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
    <?php include "footer.php"; ?>
    <!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="assets/js/userAdmin.js"></script>
    <script src="./assets/js/cute-alert.js"></script>
</body>

</html>