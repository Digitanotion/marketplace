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
if (isset($_POST['editPersonalsubmit_settings'])) {
    $usrLocation = $_POST["location_city"] . "." . $_POST["location_state"];
    $editPersonalResponse = $usrAccManager_ob->updateUsrPersonaInfoByID($pageUsrID__, $_POST['editPersonalFN_settings'], $_POST['editPersonalLN_settings'], $usrLocation, $_POST['editPersonaldob_settings'], $_POST['editPersonalsex_settings'], $_POST['editAddressLN_settings'], $_POST['editPostalLN_settings']);
    $sys_msg['msg_type'] = $editPersonalResponse['status'];
    $sys_msg['msg'] = $editPersonalResponse['message'];
}
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrBizInfo = "";
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
    <title>My Personal Settings</title>
    <script>
        var pageTitle=document.title;
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

                    <form action="" method="POST">
                        <!-- Personal__Details-image section -->
                        <div class="ha-image-update__holder mx-auto bg-purple mb-4 pt- rounded-circle" style="<?php $usrIcon = $getUsrInfo['mallUsrPhoto']; $checkSSO=$getUsrInfo['mallUsr_SSO']; 
                                                                                                                echo ($usrIcon == "" || $usrIcon == "NULL") ? "" : ($checkSSO==1)? "background-image:url(".$getUsrInfo['mallUsrPhoto']:"background-image:url(../media_store/usrPictures/thumbs/" . $getUsrInfo['mallUsrPhoto']; ?>)">
                            <?php $usrIcon = $getUsrInfo['mallUsrPhoto'];
                            if ($usrIcon == "" || $usrIcon == "NULL") {
                                echo $getUsrInfo['mallUsrFirstName'][0];
                            }
                            ?>
                            <div class="ha-update-details__icon"><i class="fa fa-pencil"></i> &nbsp;</div>
                        </div>
                        
                        <a href="blockedUsers?user_mob_id__=<?php echo $pageUsrID__?>" target="_blank" class="form-floating mb-3 alert bg-white text-primary fs-md btn-mobile">
                            Blocked sellers are <span class="badge bg-primary fw-bold"><?php echo $usrAccManager_ob->getAllUsersBlockedCountByID($pageUsrID__)['message'];?></span>
                        </a>
                        <input type="hidden" id="usrIDData" name="usrIDData" value="<?php echo $pageUsrID__ ?>">
                        <input type="file" name="updateUsrIcon[]" id="updateUsrIcon" accept="image/*;capture=camera">
                        <div class="form-floating mb-3">
                            <input type="name" class="form-control" id="editPersonalFN_settings" name="editPersonalFN_settings" placeholder="First name" value="<?php echo $getUsrInfo['mallUsrFirstName']; ?>" onkeyup="changeTheColorOfButtonDemo()">
                            <label for="editPersonalFN_settings">First Name *</label>
                        </div>
                        <div class="form-floating">
                            <input type="name" class="form-control" id="editPersonalLN_settings" name="editPersonalLN_settings" placeholder="Last name" value="<?php echo $getUsrInfo['mallUsrLastName']; ?>" onkeyup="changeTheColorOfButtonDemo1()">
                            <label for="editPersonalLN_settings">Last Name *</label>
                        </div>
                        <div class="mt-3 form-floating formsel" style="text-align: left;">
                            <select class="form-select select2" style="text-align: left;" name="location_state" id="location_state">
                                <?php $getLocationExp = explode(".", $getUsrInfo['mallUsrLocation']); ?>
                                <option value="<?php echo $getLocationExp[1] ?>" selected><?php echo $getLocationExp[1]; ?></option>
                                <option>Select location</option>
                                <?php
                                $getCitiesResponse = $adsManager_ob->getAllLocationState();
                                if ($getCitiesResponse['status'] == 1) {
                                    foreach ($getCitiesResponse['message'] as $cityEach) {
                                        echo "<option value='" . $cityEach['mallLocState'] . "'>" . $cityEach['mallLocState'] . "</option>";
                                    }
                                } else {
                                    echo "<h1>No Location Found</h1>";
                                }
                                ?>
                            </select>
                            <label for="location_state">Select Location</label>

                        </div>
                        <div class="mt-3 form-floating formsel" style="text-align: left;">
                            <select class="form-select p-3 select2" name="location_city" id="location_city">
                                <option value="<?php echo ($getLocationExp[0] != "") ? $getLocationExp[0] : ""; ?>"><?php echo ($getLocationExp[0] != "") ? $getLocationExp[0] : "Select City"; ?></option>

                            </select>
                        </div>
                        <div class="form-floating mt-3">
                            <input type="text" class="form-control" id="editAddressLN_settings" name="editAddressLN_settings" placeholder="Your Address" value="<?php echo $getUsrInfo['mallUsrAddress']; ?>" onkeyup="changeTheColorOfButtonDemo()">
                            <label for="editAddressLN_settings">Address*</label>
                        </div>
                        <div class="form-floating mt-3">
                            <input type="text" class="form-control" id="editPostalLN_settings" name="editPostalLN_settings" placeholder="Your Postal Address" value="<?php echo $getUsrInfo['mallUsrPostal']; ?>" onkeyup="changeTheColorOfButtonDemo()">
                            <label for="editPostalLN_settings">Postal Code*</label>
                        </div>
                        <div class="mt-3 form-floating">
                            <input type="text" class="form-control" id="editPersonaldob_settings" name="editPersonaldob_settings" placeholder="YYYY/MM/DD" value="<?php echo $getUsrInfo['mallUsrBirthday']; ?>" onfocus="myFunc7()">
                            <label for="editPersonaldob_settings">Birthday - YYYY/MM/DD</label>
                        </div>
                        <div class="mt-3 form-floating formsel">
                            <select class="form-select" id="editPersonalsex_settings" name="editPersonalsex_settings" aria-label="Sex" value="" onclick="changeTheColorOfButtonDemo3()">
                                <option><?php echo $getUsrInfo['mallUsrSex']; ?></option>
                                <option>Do not specify</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <label for="editPersonalsex_settings">Sex</label>
                        </div>


                        <!-- <hr class="mt-3 horiline"> -->
                        <div><button class="my-5 btn w-100" style="color: white; background-color: rgb(72, 72, 250);" id="editPersonalsubmit_settings" name="editPersonalsubmit_settings" type="submit">Save</button></div>

                    </form>

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
    $("#updateUsrIcon").change(function(e) {
        var formData = new FormData();
        var usrID = $("#usrIDData").val();
        var adImgFile = "user_img_" + usrID;

        var totalfiles = parseInt(document.getElementById('updateUsrIcon').files.length);
        for (var index = 0; index < totalfiles; index++) {
            formData.append("updateUsrIcon[]", document.getElementById('updateUsrIcon').files[index]);
        }

        //formData.append("adImgFile", adImgFile);
        formData.append("usrIDProfile", usrID);
        console.log(formData);
        $.ajax({
            url: '../handlers/mediaUpload.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                for (var index = 0; index < totalfiles; index++) {
                    $(".ha-image-update__holder").css({
                        "background-image": "url('./assets/images/gaijinLoading.svg')"
                    });
                }

            },
            success: function(data) {
                //var newFile="url('../../media_store/usrPictures/thumbs/" + data + "')";
                console.log(data);
                $(".ha-image-update__holder").css("background-image", data);
            }
        });
    });
    $(".ha-update-details__icon i").on("click", function(e) {
        $("#updateUsrIcon").click();
    })
</script>

</html>