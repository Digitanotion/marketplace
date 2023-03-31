<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
elseif(strpos($url,'192.168.')){
    require_once(__DIR__ . "\../../vendor/autoload.php");
}
USE services\SecS\SecurityManager;  
$sys_msg=[];
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob=new SecurityManager();
//$securityManager_ob->verify_forgot_password("tNOaJyEbzbAinvC0hNtC",'DvarYj43b0DcvAGoxl-y');
if ($securityManager_ob->is_user_auth_admin__()){
    header("location: cspace.php");
}
if (isset($_POST['btn_Auth_User'])){
    if ($securityManager_ob->validateCSRF($_POST['form_token__input'])) {
       
        if ($auth_user_response = $securityManager_ob->is_user_auth_admin__()) {

            //echo $_SESSION['gaijinmall_user_'];
        } else {
            //echo "No Session";
        }
        $auth_user_response = $securityManager_ob->auth__user_admin($_POST['txt_Auth_emailPhone'],$_POST['txt_Auth_pwd']);
        $sys_msg['msg_type'] = $auth_user_response['status'];
        $sys_msg['msg'] = $auth_user_response['message'];
    } else {
        $sys_msg['msg_type'] = 0;
        $sys_msg['msg'] = "System error";
    }
    
    
}
$newToken=$securityManager_ob->setCSRF();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href=".././assets/images/favicon.png">
    <link rel="stylesheet" href="../../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href=".././assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../views/assets/css/style.css">
    <link rel="stylesheet" href="../../views/assets/css/signin.css">
</head>

<body class="">
    <?php //include "header-top.php"; ?>
    <div class="container-fluid" style="margin-top:-60px; height:100vh">
        <div class="row h-100">
            <!--Gaiijin column-->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 bg-primary p-0" style="position:relative;">
                <div class="gaiijincolumn__elipse">
                    <img src="../../views/assets/images/elipse(1).png" alt="elipse" class="img-fluid elipse_class--1">
                    <img src="../../views/assets/images/elipse-2.png" alt="elipse" class="img-fluid elipse_class--2">
                </div>
                <div class="gaiijincolumn">
                    <!-- Elipse circle images-->

                    <div class="d-flex  ps-4 pt-5 d-none d-md-block ">
                        <a href="index.php">
                            &nbsp;&nbsp;
                        </a>
                       
                    </div>
                    <div class="d-flex my-auto align-items-center p-4 ps-md-4 mb-5 mb-md-0 mb-lg-0 gaiijincolumn__text">
                        <div class="mb-5 mb-md-0 mb-lg-0">

                            <div class="gaiijincolumn__text">


                                <h4><img src="../../views/assets/images/Emoji -_.png" alt="emojipic" id="signindetails__emoji">
                                    Welcome back! </h4>
                                <p class="p-tag fs-md-2">
                                    Enjoy the store features when you are logged, create ad, business page, view saved
                                    ads, etc.
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

                    <!--Nested columns-->
                    <form id="formSign" action="" method="POST" class="top--space mb-3">
                        <div class="row">

                            <div class="form-group mb-3">
                                <label for="emailField">
                                    <h6>E-mail or Phone</h6>
                                </label>
                                <input type="text" name="txt_Auth_emailPhone" id="txt_Auth_emailPhone"
                                    class="form-control p-3 fs-md-1" placeholder="Enter your email address">
                            </div>


                            <div class="form-group">
                                <label for="password">
                                    <h6>Password</h6>
                                </label>
                                <input type="password" name="txt_Auth_pwd" id="txt_Auth_pwd"
                                    class="form-control p-3 fs-md-1" placeholder="************">
                            </div>
                        </div>
                        <input type="hidden" required value="<?php echo $newToken;?>" name="form_token__input">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button type="submit" name="btn_Auth_User" id="btn_Auth_User"
                                    class="btn btn-primary btn-signindetails">Sign in</button>
                            </div>
                        </div>

                    </form>

                    <!--Reset page link-->
                    <p class="p-tag top--space mb-3 fs-md-1"><b>Forgot password? <a href="password_reset.php" class="text-primary">Reset
                                now</a></b></p>
                   
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

    <?php //include "footer.php";?>
    <script src="../../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <!-- <script src=".././assets/js/main.js"></script> -->
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