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
use services\MedS\MediaManager;
use services\AudS\AuditManager;

$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$mediaManager_ob = new MediaManager();
$audService_ob = new AuditManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg = [];
/* if (!$securityManager_ob->is_user_auth__()) {
    header("location: " . MALL_ROOT . "Signin.php");
} */
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = $_GET['user_mob_id__']; 
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
    <title>My Saved Ads</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    <script>
        var pageTitle=document.title;
            window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                window.flutter_inappwebview.callHandler('getPageTitles', 1, true, pageTitle)
            });
        </script>
</head>

<body>
    <?php //include "header-top.php";
    ?>
    <section class="container-fluid m-0 p-0 ">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <?php //include "sidebar.php";
            ?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 rounded-3 p-0">
              
                <input type="hidden" value="<?php echo $pageUsrID__ ?>" id="ha-userID" name="ha-userID">
                <div class="ha-profile-url-data__body mx-4 my-2">
                    <?php

                    $getAds_response = $adManager_ob->getSavedAdsByUsrID($pageUsrID__);
                    if ($getAds_response['status'] == 1) {
                        /* <input type="hidden" value="<?php echo $_GET["adcategory"];?>" id="ha-categID" name="ha-categID"> */
                        foreach ($getAds_response['message'] as $fields) {
                            $fields = $adManager_ob->getAdByID($fields['mallAdID']);
                            if ($fields['status'] == 1) {
                                $fields = $fields['message'];
                                $getImageCount = $adManager_ob->countAdImagesByID($fields['mallAdID']);
                                $thumbImageName = $mediaManager_ob->getThumbImage($fields['mallAdID']);
                                $adViews = $adManager_ob->getAdView($fields['mallAdID'])['message'];
                                $phoneNoViews = $adManager_ob->getAdPhoneViews($fields['mallAdID'])['message'];
                                $chatRequests = $adManager_ob->getAdChatReq($fields['mallAdID'])['message'];
                                $socialShares = $adManager_ob->getAdSocialShare($fields['mallAdID'])['message'];
                                if ($thumbImageName['status'] == 1) {
                                    $thumbImageName = $thumbImageName['message']['mallMediaName'];
                                } else {
                                    $thumbImageName = "";
                                }
                    ?>
                                <div class="row ha-mpage-items__wrapper shadow-sm mb-3">
                                    <div class="col-4 rounded-start ha-mpage-item__image" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                                        <?php
                                        if (!empty($fields['mallAdCondition'])) {
                                            echo '<span class="ha-mpage-item__title fs-md text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                                        }
                                        ?>
                                        
                                        <span class="ha-card__counter fs-sm"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1 me-0"></i></span>
                                        <?php $adManager_ob::displayPromoted($fields['mallAdID'], $fields['mallAdPromoID']); ?>
                                    </div>
                                    <a href="product.php?adID=<?php echo $fields['mallAdID']; ?>" class="col-8 rounded-end bg-light-blue text-dark py-2" style="position: relative;">
                                        <div class="">
                                            <div class="mt-2 ">
                                                <span class="fs-md-1 fw-bolder"><?php echo $fields['mallAdTitle'] ?> </span>
                                            </div>
                                            <div class="">
                                                <span class="ha-mpage-item__desc fs-md-1"><?php echo $fields['mallAdDesc'] ?></span>
                                            </div>
                                            <div class="mt-1 py-auto">
                                                <span class="badge bg-dark fs-md"><?php echo $adManager_ob::CURRENCY . number_format($fields['mallAdPrice']); ?></span><br>
                                                <div class="badge fs-sm-1 text-dark mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> <?php echo $fields['mallAdLoc'] ?>, Japan.</div>
                                            </div>
                                        </div>

                                    </a>
                                </div>
                    <?php     }
                        }
                    } else {
                        echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
                                    <img class="img-fluid mx-auto" src="./assets/images/notfound3.svg" id="adverts1">
                                    <div class="fs-title-4 fw-bolder" id="adverts2">No saved Ad</div>
                                    <div class="fs-md" id="adverts3">No content availiable</div>
                                </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
                    }
                    ?>
                </div>
            </div>

        </div>
    </section>
    <?php //include "footer.php";
    ?>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/userAdmin.js"></script>

</body>

</html>