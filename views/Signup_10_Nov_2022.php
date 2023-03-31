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
use services\AccS\AccountManager;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
session_start();
$sys_msg = [];
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication 
$securityManager_ob = new SecurityManager();
$accountManager_ob = new AccountManager();
$new_user_response = [];
if (isset($_POST['btn_New_user'])) {

    if ($securityManager_ob->validateCSRF($_POST['form_token__input'])) {
        if ($_POST['txt_New_pwd'] === $_POST['txt_New_verifypwd']) {
            $new_user_response = $accountManager_ob->new_user_account($_POST['txt_New_firstname'], $_POST['txt_New_lastname'], $_POST['txt_New_email'], $_POST['txt_New_phone'], $_POST['txt_New_pwd']);
            $sys_msg['msg_type'] = $new_user_response['status'];
            $sys_msg['msg'] = $new_user_response['message'];
            if ($new_user_response['status'] == 1) {
                echo ' <script>
                setTimeout(function (e) {
                    window.location="Signin.php";
                },10000); 
            </script>';
            }
        } else {
            $sys_msg['msg_type'] = 0;
            $sys_msg['msg'] = "Passwords do not match";
        }
    } else {
        $sys_msg['msg_type'] = 0;
        $sys_msg['msg'] = "System Error";
    }
}
$newToken = $securityManager_ob->setCSRF();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Us | Gaijinmall account</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/signin.css">
</head>

<body>
    <?php include "header-top.php"; ?>
    <div class="container-fluid" style="margin-top:-60px;">
        <div class="row">
            <!--Gaiijin column-->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 bg-primary p-0" style="position:relative; ">
                <div class="gaiijincolumn__elipse">
                    <img src="assets/images/elipse(1).png" alt="elipse" class="img-fluid elipse_class--1">
                    <img src="assets/images/elipse-2.png" alt="elipse" class="img-fluid elipse_class--2">
                </div>
                <div class="gaiijincolumn">
                    <!-- Elipse circle images-->

                    <div class="d-flex  ps-4 pt-5 d-none d-md-block ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="d-flex my-auto align-items-center p-4 ps-md-4 mb-5 mb-md-0 mb-lg-0 gaiijincolumn__text">
                        <div class="mb-5 mb-md-0 mb-lg-0">

                            <div class="gaiijincolumn__text">


                                <h4><img src="assets/images/Emoji -_.png" alt="emojipic" id="signindetails__emoji"> Your
                                    first time here? </h4>
                                <p class="p-tag fs-md-2">
                                    You are just few seconds away from posting and managing your ads. Create an account
                                    now for free, it takes less than 2 minutes to do that.
                                </p>
                            </div>
                        </div>

                    </div>
                    <!--Gaiijin image-->
                </div>
            </div>

            <!--Signin column-->
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <div class="signindetails">
                    <?php
                    if (isset($_POST['btn_New_user'])) {
                        if ($new_user_response['status'] == 1) {
                            echo '<div class="alert bg-warning text-white fs-md-2 fw-bold"><i class="fa fa-info-circle me-1"></i> We sent a mail to you now to verify your account.</div>';
                        }
                    }
                    ?>
                    <!--Nested columns-->
                    <form id="formSignup" action="" method="POST" class="top--space mb-3">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="txt_New_firstname">
                                    <h6>First name <span class="text-danger">*</span></h6>
                                </label>
                                <input type="text" name="txt_New_firstname" required id="txt_New_firstname" class="form-control p-3 fs-md-1" placeholder="Enter your first name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="txt_New_lastname">
                                    <h6>Last name</h6>
                                </label>
                                <input type="text" name="txt_New_lastname" id="txt_New_lastname" class="form-control p-3 fs-md-1" placeholder="Enter your last name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="txt_New_email">
                                    <h6>Email address <span class="text-danger">*</span></h6>
                                </label>
                                <input type="email" required name="txt_New_email" id="txt_New_email" class="form-control p-3 fs-md-1" placeholder="Enter your email address">
                            </div>
                            <div class="form-group mb-3">
                                <label for="txt_New_phone">
                                    <h6>Phone number <span class="text-danger">*</span></h6>
                                </label>
                                <input type="text" required name="txt_New_phone" id="txt_New_phone" class="form-control p-3 fs-md-1" placeholder="Enter your phone">
                            </div>


                            <div class="form-group mb-3">
                                <label for="txt_New_pwd">
                                    <h6>Password <span class="text-danger">*</span></h6>
                                </label>
                                <input type="password" required name="txt_New_pwd" id="txt_New_pwd" class="form-control p-3 fs-md-1" placeholder="************">
                            </div>
                            <div class="form-group">
                                <label for="txt_New_verifypwd">
                                    <h6>Verify password <span class="text-danger">*</span></h6>
                                </label>
                                <input type="password" required name="txt_New_verifypwd" id="txt_New_verifypwd" class="form-control p-3 fs-md-1" placeholder="************">
                            </div>
                        </div>
                        <input type="hidden" required value="<?php echo $newToken; ?>" name="form_token__input">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" name="btn_New_user" id="btn_New_user" class="btn btn-primary btn-signindetails">Sign up</button>
                            </div>
                        </div>

                    </form>

                    <!--Reset page link-->
                    <p class="p-tag top--space mb-3 fs-md-2"><b>By signing up you agree to our <a href="terms" class="text-primary">Terms and Conditions</a></b></p>
                    <p class="signup p-tag fs-md-1">Already have an account? <a href="Signin.php" class="text-primary">Signin</a></p>
                </div>

                <!--Other signin accounts section-->
                <!-- <div class="top--space mb-3">
                    <p class="otheracc__text p-tag">or do it via other accounts</p>

                    <div class="otheracc__options">
                        <button type="button" class="btn btn-options"><img src="views/assets/images/icons8-google-48.png" alt="googleicon" class="img-fluid btn-options--1"></button>
                        <button type="button" class="btn btn-options"><img src="views/assets/images/f.png" alt="facebookicon" class="img-fluid btn-options--2"></button>
                    </div>    
                </div> -->

                <!--Regsistration page link-->

            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>

    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <!-- <script src="./assets/js/main.js"></script> -->
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
            if (isset($sys_msg) && !empty($sys_msg)) {
                switch ($sys_msg['msg_type']) {
                    case '1':
                        echo 'toastr.success("' . $sys_msg['msg'] . '");';
                        break;
                    default:
                        echo 'toastr.error("' . $sys_msg['msg'] . '");';
                        break;
                }
            }
            ?>
        });
    </script>

</body>

</html>