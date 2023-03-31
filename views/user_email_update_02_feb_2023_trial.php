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

$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message']; //


if (isset($_POST['edit_email__btn'])){
    $editEmailResponse=$securityManager_ob->generateVerifyToken($getUsrInfo['mallUsrEmail'], $_POST['edit_email__txt']);
    $sys_msg['msg_type'] = $editEmailResponse['status'];
    $sys_msg['msg'] = $editEmailResponse['message'];
    if ($editEmailResponse==1){
        echo ' <script>
                setTimeout(function (e) {
                    window.location="adverts";
                },5000); 
            </script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update My Email | Gaijinmall</title>
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
    <link rel="stylesheet" href="assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="assets/css/cute-alert.css">
</head>

<body>
    <?php include "header-top.php"; ?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "settings_side__bar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="prof1">Change email number</span>
                    </div>
                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body">
                    <div class="ha-none__display  w-75 text-center m-5 mx-auto">
                        <div class="number_0" id="number0">
                            <form action="" method="POST">
                                <span class="d-flex justify-content-around ">
                                    <input type="email" id="disabledTextInput" value="<?php echo $getUsrInfo['mallUsrEmail'];?>" name="edit_email__txt" <?php echo ($isUsrIDUploaded['status'] == 1) ? "" : "disabled"; ?> class="form-control">
                                    <button class="p-2 bg-light ms-2 rounded btn btn-primary text-primary fw-bold text-primary" name="edit_email__btn" <?php echo ($isUsrIDUploaded['status'] == 1) ? 'type="submit"' : 'type="button" onclick="myPhone()"'; ?>>Verify</button>
                                </span>
                                <span class="d-flex m-2">
                                    <?php if ($getUsrInfo['mallUsrEmailStatus'] == 1) {
                                        echo '<i class="fa fa-check text-success fs-title-2 m-1 me-2"></i>';
                                    } else {
                                        echo '<i class="fa fa-times text-danger fs-title-2 m-1 me-2"></i>';
                                    } ?>

                                    <p class="opacity-75 fs-md-1">Confirmed</p>
                                </span>
                            </form>

                        </div>
                        <?php
                        if (($isUsrIDUploaded['status'] == 0)) {
                        ?>
                            <div class="phone_0" id="phone0">
                                <div class="fs-md fw-bolder p-3 m-2 bg-light">
                                    To change your Email<br> we need your ID card:
                                </div>
                                <!-- <a href="#">
                                <div class="text-dark p-2 border m-2">
                                    <span class="d-flex fs-sm" onclick="myCall()">
                                        <i class="fa fa-email text-success fs-title-4 m-3 me-5"></i>
                                        <span>
                                            <p class="fs-title-2 fw-bold m-1">Answer call on 09012783001</p> 
                                            <p class="fs-md m-1">If you are able to</p>
                                        </span>
                                    </span>
                               </div>
                            </a> -->
                                <a href="#">
                                    <div class="text-dark p-2 border m-2">
                                        <span class="d-flex fs-sm" onclick="myInfo()">
                                            <i class="fa fa-list text-success fs-title-4 m-3 me-5"></i>
                                            <span>
                                                <p class="fs-title-2 fw-bold m-1">Attach your ID</p>
                                                <p class="fs-md m-1">If you lost your old number</p>
                                            </span>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="w-75 call_0" id="call0">
                                <span><input type="text" id="" placeholder="New email number" class="form-control m-1 mb-3 btn btn-outline-primary bg-white"></span>
                                <span><button class="btn bg-secondary m-1 w-100 p-2">Next</button></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <form class="row m-2 mt-4" action="" method="POST" enctype="multipart/form-data">
                    <div class="col-lg-6 col-md-6 col-sm-12 text-start  info_0" id="info0">
                        <select id="usrIDType__select" class="form-select mb-4" name="usrIDType__select">
                            <option value="passport">Passport</option>
                            <option value="drivers">Drivers Licence</option>
                            <option value="national">National ID</option>
                            <!-- <option>NIN (National Identity Number)</option> -->
                            <option value="digital">Digital ID</option>
                        </select>
                        <span>
                            <input type="text" name="usrIDNo__txt" id="usrIDNo__txt" placeholder="Document ID*" class="form-control btn-outline-secondary bg-white my-4 text-dark">
                        </span>
                        <a href="#">
                            <span class="">
                                <p class="bg-light-blue text-primary p-2 mb-4 w-75 fs-md">Where to find Document ID no?</p>
                            </span>
                        </a>
                        <span>
                            <input type="text" name="usrIDFirstName__txt" id="usrIDFirstName__txt" placeholder="First Name*" class="form-control btn-outline-secondary bg-white mb-4 text-dark">
                        </span>
                        <span>
                            <input type="text" name="usrIDLastName__txt" id="usrIDLastName__txt" placeholder="Last Name*" class="form-control btn-outline-secondary bg-white p-2 mb-4 text-dark">
                        </span>
                    </div> 
                    <div class="col-lg-6 col-md-6 col-sm-12 text-start  info_0" id="info1">
                        <span>
                            <a href="#">
                                <input type="text" id="date0" name="datusrIDDOB__txt" placeholder="Date of birth*" class="form-control btn-outline-secondary bg-white mb-4 text-dark" onfocus="myDate()">
                            </a>
                        </span>
                        <span class="d-flex">
                            <a href="#"><i class="fa fa-add fs-title-4 m-1 me-4 text-primary bg-light-blue p-3 add_0" onclick="myFile()"></i></a>
                            <span>
                                <p class="fs-title-1 fw-bold m-1">Attach photo of your Passport*</p>
                                <p class="fs-md m-1 mb-4">Push for choose</p>
                            </span>
                        </span>
                        <span>
                            <input type="file" name="usrIDFile__file[]" id="click0" class="w-100 mb-5 form-control click_0">
                        </span>
                        <span>
                            <input type="email" name="usrIDEmail__txt" id="usrIDEmail__txt" placeholder="New email" class="form-control btn-outline-secondary bg-white p-2 mb-4 text-dark" onkeypress="myEnable()">
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
    <script src="./assets/js/userAdmin.js"></script>
    <script src="./assets/js/settings.js"></script>
    <script src="assets/js/cute-alert.js"></script>
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
                        echo 'cuteAlert({
                            type: "success",
                            title: "Email Verification",
                            message: "'.$sys_msg['msg'].'",
                            buttonText: "Thank you"
                          })';
                        break;
                    default:
                        echo 'cuteAlert({
                            type: "error",
                            title: "Email Verification",
                            message: "'.$sys_msg['msg'].'",
                            buttonText: "Ok"
                          })';
                        break;
                }
            }
            ?>
        });
    </script>
</body>

</html>