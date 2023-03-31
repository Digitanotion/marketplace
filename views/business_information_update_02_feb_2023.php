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
$getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
//echo $getUsrBizInfo['mallBizID'];
if (isset($_POST['editBizUpdate_settings'])) {
    $bizDaysMon=(isset($_POST['editBizMon_settings']))?1:0;
    $bizDaysTue=(isset($_POST['editBizTue_settings']))?1:0;
    $bizDaysWed=(isset($_POST['editBizWed_settings']))?1:0;
    $bizDaysThur=(isset($_POST['editBizThur_settings']))?1:0;
    $bizDaysFri=(isset($_POST['editBizFri_settings']))?1:0;
    $bizDaysSat=(isset($_POST['editBizSat_settings']))?1:0;
    $bizDaysSun=(isset($_POST['editBizSun_settings']))?1:0;
    $editBizResponse = $usrAccManager_ob->updateUsrBizInfoByID($pageUsrID__,$getUsrBizInfo['mallBizID'],$_POST['editBizBN_settings'],$_POST['editBizDelivery_settings'],$_POST['editBizAbout_settings'],$_POST['editBizAddress_settings'],$_POST['editBizPostalAddress_settings'],$_POST['editBizHoursStart_settings'],$_POST['editBizHoursEnd_settings'],$bizDaysMon,$bizDaysTue,$bizDaysWed,$bizDaysThur,$bizDaysFri,$bizDaysSat,$bizDaysSun);
    $sys_msg['msg_type'] = $editBizResponse['status'];
    $sys_msg['msg'] = $editBizResponse['message'];
}
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message']; //
$getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Settings | Gaijinmall</title>
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
    <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="assets/css/cute-alert.css">
</head>

<body>
    <?php include "header-top.php"; ?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "settings_side__bar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-1 mt-4" id="demo000">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder">Business information</span>
                    </div>
                    <!-- <div class="text-center">
                        <button class="rounded-pill border-0" id="demo0" style="background-color: rgb(72, 72, 250);"><span
                                class="btn p-1" style="color: white;">Saved</span></button>
                    </div> -->

                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-none__display w-50 text-center m-5 mx-auto">
                    <form action="" method="post">
                        <?php   
                            if ($getUsrBizInfo['mallBizStatus'] == 0) { ?>
                                <div class="alert bg-warning fs-6 text-light">
                                    <i class="fa fa-exclamation-circle"></i>Not verified <a href="verify_business.php" class="text-primary text-underline">verify now</a>
                                </div>
                            <?php  } else { ?>
                                <div class="alert bg-success fs-6 text-light">
                                    Verified <i class="fa fa-check-circle"></i>
                                </div>
                           <?php }
                            
                        ?>
                        
                        <div class="form-floating mb-3">
                            <input type="name" class="form-control" value="<?php echo $getUsrBizInfo['mallBizName'];?>" id="editBizBN_settings" name="editBizBN_settings" placeholder="Business name" onkeyup="changeTheColorOfButtonDemo4()">
                            <label for="editBizBN_settings">Business name</label>
                        </div>
                        <!-- <div class="input-group form-floating  mb-3 ">
                            <input type="name" class="form-control" id="floatingDomain" placeholder="Create a domain" aria-label="Create a domain" aria-describedby="basic-addon2" onkeyup="changeTheColorOfButtonDemo5()">
                            <label for="floatingInput">Create a domain</label>
                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-pen"></i></span>
                        </div> -->
                        <div class="mt-3 form-floating formsel">
                            <select class="form-select" id="editBizDelivery_settings" name="editBizDelivery_settings" aria-label="Floating label select example" onclick="changeTheColorOfButtonDemo6()">
                                
                                <option value="1" <?php switch ($getUsrBizInfo['mallBizDelivery']){case '1':echo "selected"; break;}?> >Yes, delivery fee is included in price</option>
                                <option value="2" <?php switch ($getUsrBizInfo['mallBizDelivery']){case '2': echo "selected"; break;}?>>Yes, for an additional fee</option>
                                <option value="0" <?php switch ($getUsrBizInfo['mallBizDelivery']){case '0': echo "selected"; break;}?>>No</option>
                            </select>
                            <label for="editBizDelivery_settings">Do you offer delivery</label>
                        </div>
                        <!-- <div class="mt-3 form-floating">
                            <input type="text" class="form-control" id="editBizDeliveryDays_settings" value="<?php echo $getUsrBizInfo['mallBizDeliveryDays'];?>" name="editBizDeliveryDays_settings" placeholder="no-display" onkeyup="changeTheColorOfButtonDemo7()">
                            <label for="editBizDeliveryDays_settings">Delivery estimates (days)</label>
                        </div> -->
                        <div class="mt-3 form-floating">
                            <input type="text" class="form-control" id="editBizAbout_settings" name="editBizAbout_settings" value="<?php echo $getUsrBizInfo['mallBizAbout'];?>" placeholder="no-display" onkeyup="changeTheColorOfButtonDemo8()">
                            <label for="editBizAbout_settings">About company</label>
                        </div>
                        <div class="mt-3 form-floating">
                            <input type="text" class="form-control" id="editBizAddress_settings" name="editBizAddress_settings" value="<?php echo $getUsrBizInfo['mallBizAddress'];?>" placeholder="no-display" onkeyup="changeTheColorOfButtonDemo9()">
                            <label for="editBizAddress_settings">Address</label>
                        </div>
                        <div class="mt-3 form-floating">
                            <input type="text" class="form-control" id="editBizPostalAddress_settings" name="editBizPostalAddress_settings" value="<?php echo $getUsrBizInfo['mallBizPostalAddress'];?>" placeholder="no-display" onkeyup="changeTheColorOfButtonDemo9()">
                            <label for="editBizPostalAddress_settings">Postal Address</label>
                        </div>
                        <!-- <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" onclick="myFunc3()" value="" id="editBizShowAddress_settings" name="editBizShowAddress_settings">
                            <label class="form-check-label" style="font-size: small;" for="flexCheckDefault" id="demo333">Show this address on all my ads</label>
                        </div> -->
                        <div class="mt-3">Working hours</div>
                        <div class="row mt-2 timer">
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" id="editBizHoursStart_settings" name="editBizHoursStart_settings" aria-label="Floating label select example" onclick="changeTheColorOfButtonDemo10()">
                                        <option selected><?php echo $getUsrBizInfo['mallBizStart'];?></option>
                                        <option>01:00 AM</option>
                                        <option>02:00 AM</option>
                                        <option>03:00 AM</option>
                                        <option>04:00 AM</option>
                                        <option>05:00 AM</option>
                                        <option>06:00 AM</option>
                                        <option>07:00 AM</option>
                                        <option>08:00 AM</option>
                                        <option>09:00 AM</option>
                                        <option>10:00 AM</option>
                                        <option>11:00 AM</option>
                                        <option>12:00 PM</option>
                                        <option>01:00 PM</option>
                                        <option>02:00 PM</option>
                                        <option>03:00 PM</option>
                                        <option>04:00 PM</option>
                                        <option>05:00 PM</option>
                                        <option>06:00 PM</option>
                                        <option>07:00 PM</option>
                                        <option>08:00 PM</option>
                                        <option>09:00 PM</option>
                                        <option>10:00 PM</option>
                                        <option>11:00 PM</option>
                                        <option>12:00 AM</option>
                                    </select>
                                    <label for="editBizHoursStart_settings" style="color: blue;">Start</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" id="editBizHoursEnd_settings" name="editBizHoursEnd_settings" aria-label="Floating label select example" onclick="changeTheColorOfButtonDemo10()">
                                    <option selected><?php echo $getUsrBizInfo['mallBizEnd'];?></option>
                                    <option >01:00 AM</option>
                                        <option>02:00 AM</option>
                                        <option>03:00 AM</option>
                                        <option>04:00 AM</option>
                                        <option>05:00 AM</option>
                                        <option>06:00 AM</option>
                                        <option>07:00 AM</option>
                                        <option>08:00 AM</option>
                                        <option>09:00 AM</option>
                                        <option>10:00 AM</option>
                                        <option>11:00 AM</option>
                                        <option>12:00 PM</option>
                                        <option>01:00 PM</option>
                                        <option>02:00 PM</option>
                                        <option>03:00 PM</option>
                                        <option>04:00 PM</option>
                                        <option>05:00 PM</option>
                                        <option>06:00 PM</option>
                                        <option>07:00 PM</option>
                                        <option>08:00 PM</option>
                                        <option>09:00 PM</option>
                                        <option>10:00 PM</option>
                                        <option>11:00 PM</option>
                                        <option>12:00 AM</option>
                                    </select>
                                    <label for="editBizHoursEnd_settings" style="color: blue;">End</label>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group mt-5 mb-5 buttoninpt" role="group" aria-label="Basic checkbox toggle button group">
                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDayMon']==1)?"checked":"";?> class="btn-check" id="editBizMon_settings" name="editBizMon_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo11()">
                            <label class="btn btn-outline-primary" for="editBizMon_settings">Mon</label>

                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDayTue']==1)?"checked":"";?> class="btn-check" id="editBizTue_settings" name="editBizTue_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo12()">
                            <label class="btn btn-outline-primary" for="editBizTue_settings">Tue</label>

                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDayWed']==1)?"checked":"";?> class="btn-check" id="editBizWed_settings" name="editBizWed_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo13()">
                            <label class="btn btn-outline-primary" for="editBizWed_settings">Wed</label>
                            
                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDayThu']==1)?"checked":"";?> class="btn-check" id="editBizThur_settings" name="editBizThur_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo14()">
                            <label class="btn btn-outline-primary" for="editBizThur_settings">Thu</label>

                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDayFri']==1)?"checked":"";?> class="btn-check" id="editBizFri_settings" name="editBizFri_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo15()">
                            <label class="btn btn-outline-primary" for="editBizFri_settings">Fri</label>

                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDaySat']==1)?"checked":"";?> class="btn-check" id="editBizSat_settings" name="editBizSat_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo16()">
                            <label class="btn btn-outline-primary" for="editBizSat_settings">Sat</label>

                            <input type="checkbox" <?php echo ($getUsrBizInfo['mallBizDaySun']==1)?"checked":"";?> class="btn-check" id="editBizSun_settings" name="editBizSun_settings" autocomplete="off" onclick="changeTheColorOfButtonDemo17()">
                            <label class="btn btn-outline-primary" for="editBizSun_settings">Sun</label>
                        </div>
                        <div><button class="mt-2 mb-5 btn w-75" style="color: white; background-color: rgb(72, 72, 250);" id="demo1001" type="submit" name="editBizUpdate_settings">Save</button></div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php include "footer.php";?>
</body>
<script src="./assets/js/personalbusiness.js"></script>
<script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
<script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
<script src="./assets/js/vertical-menu.js"></script>
<script src="./assets/js/adverts.js"></script>
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
                    echo '
                    cuteAlert({
                        type: "success",
                        message: "",
                        title: "' . $sys_msg['msg'] . '",
                        buttonText: "Ok",
                      })';
                    break;
                default:
                echo '
                cuteAlert({
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
</script>

</html>