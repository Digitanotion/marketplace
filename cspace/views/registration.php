<?php
require_once(__DIR__ ."\../../vendor/autoload.php");
USE services\SecS\SecurityManager; 
USE services\AccS\AccountManager;

$sys_msg=[];
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
 $accManager = new AccountManager();
 $securityManager_ob=new SecurityManager();

 if ($securityManager_ob->is_user_auth_admin__()){
    header("location: cspace.php");
}
if (isset($_POST['btn_Create_Admin'])){
   
    $create_admin_response = $accManager->new_admin_account($_POST['firstField'],$_POST['lastField'], $_POST["emailField"], $_POST["phoneField"], $_POST["passwordField"] );
    $sys_msg['msg_type']=$create_admin_response['status'];
    $sys_msg['msg']=$create_admin_response['message'];
    
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration page</title>
    <link rel="stylesheet" href="./assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assets/css/registration.css">

    <link rel="stylesheet" href="../../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/signin.css">
</head>

<body>


    <div class="container-fluid">
        <div class="row">
            <!--Gaiijin column-->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 gaiijincolumn">
                <!--Elipse circle images-->
                <div class="gaiijincolumn__elipse">
                    <img src="assets/images/elipse.png" alt="elipse" class="img-fluid elipse_class">
                    <img src="assets/images/elipse(1).png" alt="elipse" class="img-fluid elipse_class--1">
                    <img src="assets/images/elipse-2.png" alt="elipse" class="img-fluid elipse_class--2">
                </div>

                <!--Gaiijin image-->
                <img src="assets/images/Untitled_design__4_-removebg-preview 1.png" alt="Logo"
                    class="img-fluid gaiijincolumn__logopic">

                <div class="gaiijincolumn__text">
                    <h3>I see you're the first <br> time here.</h3>

                </div>
            </div>

            <!--Account details column-->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 justify-content-center">
                <div class="form-control accountdetails">
                    <h4 class="accountdetails__headtext">Create Admin account</h4>

                    <!--Nested columns-->
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <form id="formID" method="POST" class="input__field top--space mb-3">
                                <label for="firstField">
                                    <h6>First name</h6>
                                </label>
                                <input name="firstField" type="text" class="form-control" id="firstField"
                                    placeholder="First name" required>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                            <label for="lastField">
                                <h6 style="margin-bottom:10px; margin-top:10px;">Last name</h6>
                            </label>
                            <input name="lastField" type="text" class="form-control" id="lastField"
                                placeholder="Last name" required>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                            <label for="emailField">
                                <h6>E-mail</h6>
                            </label>
                            <input name="emailField" type="email" class="form-control" id="emailField"
                                placeholder="E-mail" required>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                            <label for="phoneField">
                                <h6>Phone number</h6>
                            </label>
                            <input name="phoneField" type="text" class="form-control" id="phoneField"
                                placeholder="Phone number" required>

                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <label for="passwordField">
                                <h6>Password</h6>
                            </label>
                            <input name="passwordField" type="password" class="form-control" id="passwordField"
                                placeholder="**********" required>

                        </div>
                    </div>


                    <!-- <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <h5 class="text-center mt-4 mb-3">Define priviledges</h5>

                            <div class="form-check">
                                <input name="adspriv" type="checkbox" class="form-check-input" id="adspriv">
                                <label class="text-left form-check-label" for="adspriv">Approve ads</label>
                            </div>

                            <div class="form-check">
                                <input name="kycpriv" type="checkbox" class="form-check-input" id="kycpriv">
                                <label class="form-check-label" for="kycpriv">Approve kyc documents</label>
                            </div>

                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button name="btn_Create_Admin" type="submit" class="btn-primary btn--accountdetails">Create
                                account</button>
                        </div>
                    </div>

                    </form>

                </div>


                <p class="signin p-tag mt-4">Already have an account? <a href="signin.php">Sign in</a></p>
            </div>
        </div>
    </div>



    
    <script src="../../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/node_modules/toastr/build/toastr.min.js"></script>

    <script src="./assets/js/bootstrap.min.js"></script>
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