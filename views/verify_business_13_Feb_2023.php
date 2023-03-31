<?php
ob_start();
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
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrPhoneVerified=$usrAccManager_ob->getUserVerifiedNumberByIDandPhone($pageUsrID__,$getUsrInfo['mallUsrPhoneNo']);
if (isset($_POST['usrIDSubmit__btn'])) {
    $editBizResponse = $usrAccManager_ob->updateUsrIDByID($pageUsrID__,$_FILES['usrBizFile__file'],$getUsrInfo["mallUsrBirthday"],$_FILES['usrIDFile__file'],$getUsrInfo["mallUsrFirstName"],$getUsrInfo["mallUsrLastName"],"phone",$_POST['usrIDPhone__txt'], $getUsrInfo["mallUsrEmail"]);
    $sys_msg['msg_type'] = $editBizResponse['status'];
    $sys_msg['msg'] = $editBizResponse['message'];

    // if ($editBizResponse['status']==1){
    //     $securityManager_ob->generatePhoneVerifyToken($pageUsrID__,$_POST['usrIDPhone__txt']);
    //     $sys_msg['msg'] = "You'll be redirected shortly";
    //     header("location: verify_phone?phone=".$_POST['usrIDPhone__txt']."&for=".$pageUsrID__."&request=".$editBizResponse['message']);
    // }
    // else{
    //     $sys_msg['msg'] = $editBizResponse['message'];
    // }
}
if (isset($_POST['edit_phone__btn'])){
    //$editPhoneResponse=$usrAccManager_ob->updateUsrPhoneByID($pageUsrID__,$_POST['edit_phone__txt']);
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
    <title>Verify Business | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="views/assets/css/personal-buiness.css">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    <link rel="stylesheet" href="./assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/cute-alert.css">
    <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
</head>

<body>
    <?php include "header-top.php"; ?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <?php include "settings_side__bar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="prof1">Verify your business now</span>
                    </div>
                </div>
                <hr class="m-0 bg-hr-light">

                <form class="row m-2 mt-4" action="" method="POST" enctype="multipart/form-data">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-start" id="info0">

                       <!--  <select id="usrIDType__select" class="form-select mb-4" name="usrIDType__select">
                            <option value="passport">Passport</option>
                            <option value="drivers">Drivers Licence</option>
                            <option value="national">National ID</option>
                            <option value="digital">Digital ID</option>
                        </select>
                        <span>
                            <input type="text" name="usrIDNo__txt" id="usrIDNo__txt" placeholder="ID Number" class="form-control btn-outline-secondary bg-white my-4 text-dark">
                        </span> -->
                        <p class="fs-sm text-info">Only Jpg, Jpeg and Png are required</p>
                        <span class="d-flex">
                            <a href="javascript:void" onclick="updateSelectFile()" id="addNewfile__phoneupdate"><i id="addNewfileIconB__phoneupdate" class="fa fa-plus fs-title-4 m-1 me-4 text-primary bg-light-blue p-3 add_0" ></i></a>
                            <span>
                                <p class="fs-title-1 fw-bold m-1" onclick="updateSelectFile()">Attach proof of business</p>
                                <p class="fs-md m-1" onclick="updateSelectFile()">
                                    Click or Touch to select
                                </p>   
                            </span>
                        </span>
                        <span>
                            <input type="file" name="usrBizFile__file[]" id="usrBizFile__file" class="w-100 mb-5 form-control click_0">
                        </span>
                        <!-- <span>
                            <input type="text" name="usrIDLastName__txt" id="usrIDLastName__txt" placeholder="Last Name" class="form-control btn-outline-secondary bg-white p-2 mb-4 text-dark" >
                        </span> -->
                    </div> 
                    <div class="col-lg-12 col-md-12 col-sm-12 text-start" id="info1">
                        <!-- <span>
                           
                                <input type="text" id="phoneUpdateChangeToDate__txt" name="datusrIDDOB__txt" placeholder="Date of birth" class="form-control btn-outline-secondary bg-white mb-4 text-dark" onfocus="phoneUpdateChangeTDate()">
                            
                        </span> -->
                        <span class="d-flex">
                            <a href="javascript:void" onclick="phoneUpdateSelectFile()" id="addNewfile__phoneupdate"><i id="addNewfileIcon__phoneupdate" class="fa fa-plus fs-title-4 m-1 me-4 text-primary bg-light-blue p-3 add_0" ></i></a>
                            <span>
                                <p class="fs-title-1 fw-bold m-1" onclick="phoneUpdateSelectFile()">Attach any other copy</p>
                                <p class="fs-md m-1" onclick="phoneUpdateSelectFile()">Click or Touch to select</p>   
                            </span>
                        </span>
                        <span>
                            <input type="file" name="usrIDFile__file[]" id="usrIDFile__file" class="w-100 mb-5 form-control click_0">
                        </span>
                        <span>
                            <input type="tel" name="usrIDPhone__txt" id="usrIDPhone__txt" placeholder="Business phone number *" class="form-control btn-outline-secondary bg-white p-2 mb-4 text-dark" onfocus="myEnable()">
                        </span>
                        <span>
                            <button class="btn bg-primary p-2 w-100 text-white fw-bolder" disabled id="enable0" type="submit" name="usrIDSubmit__btn">APPLY</button>
                            <p class="fs-sm fw-bold opacity-50 mb-4">Please note,by submitting any information and document to our customer support you <Span class="text-primary">consent</Span> to the processing of such data for use in identification and authentication and you acknowledge that such processing is also required to continue providing our services to you.</p>
                        </span>
                    </div>
                </form>
            </div>

        </div>
    </section>
    <?php include "footer.php";?>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="assets/js/cute-alert.js"></script>
    <script src="./assets/js/userAdmin.js"></script>
    <script src="./assets/js/jquery-ui.js"></script>
    <script src="./assets/js/settings.js"></script>
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
                    case 1:
                        echo 'cuteAlert({
                            type: "success",
                            message: "",
                            title: "' . $sys_msg['msg'] . '",
                            buttonText: "Ok",
                          })';
                        break;
                    default:
                        echo 'cuteAlert({
                        type: "error",
                        message: "",
                        title: "' . $sys_msg['msg'] . '",
                        buttonText: "Ok",
                      })';
                        break;
                }
            }
            ?>
        });

        $( function() {
    $( "#phoneUpdateChangeToDate__txt" ).datepicker({
        dateFormat: "dd/mm/yy",
        changeYear: true,
        changeMonth: true,
    });
  } );
    </script>
</body>

</html>
<?php ob_end_flush();?>