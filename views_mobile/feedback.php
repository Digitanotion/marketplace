<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
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
use services\MsgS\feedbackManager;

$securityManager_ob = new SecurityManager();
$adsManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$feedback_ob = new feedbackManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg = [];
if (!$securityManager_ob->is_user_auth__()) {
    //header("location: ./Signin.php");
}
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ =$_GET['user_mob_id__'];
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrBizInfo = "";
if (isset($_GET['view_business'])) {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
} else {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}

$getProductUsrRating = 0;
$getAdFeedResp = $feedback_ob->getAllUsrAdReviews($pageUsrID__);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Feedbacks</title>
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
</head>

<body>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-11 mx-auto p-0 shadow-sm bg-white rounded-3 p-0">
                
                <div class="ha-profile-url-data__body">
                    <div class="ha-none__display mx-3 my-3">
                        <?php
                        if ($getAdFeedResp['status'] == 1) {
                            $getAllAdFeeds = $getAdFeedResp['message'];
                            foreach ($getAllAdFeeds->fetchAll() as $adFeed) {
                                $getAdInfo=$adsManager_ob->getAllAdByID($adFeed['mallAdID']);
                                $getAdInfo=$getAdInfo['message'];
                        ?>
                                <div class="d-flex feedContainer bg-light-blue py-3 px-4 mb-1">
                                    <div class="d-flex ali">
                                        <div class="ha-seller__avatar__wrapper ">
                                            <div class="ha-seller__icon profile-image-init__avatar bg-purple" style="background-image: url('../handlers/uploads/thumbs/<?php //echo $adThumbImageName; ?>">
                                            <?php    
                                            $initialLetter_comm  = $getAdInfo['mallAdTitle'];
                                                    echo $initialLetter_comm [0];
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="fs-title-1"><?php echo $getAdInfo['mallAdTitle'];?></div>
                                        <div class="d-flex mt-2">
                                            <a href="./customer_review.php?adrec=B787Bbb87bsbd8nsd87877fds8f99fs9fdf8df9df&adtok=<?php echo $getAdInfo['mallAdID']?>" class=" me-1 comments fs-md-2 fw-bold"><?php $feedDetailss=$feedback_ob->getProductFeedbacks($getAdInfo['mallAdID']); echo ($feedDetailss['status']==1)? $feedDetailss['message']->rowCount() : "0";?> <i class="fa fa-comments-o m-0"></i></a>
                                            <a href="./customer_review.php?adrec=B787Bbb87bsbd8nsd87877fds8f99fs9fdf8df9df&adtok=<?php echo $getAdInfo['mallAdID']?>" class="rating-star1 fw-bold ms-2"><?php echo number_format($feedback_ob->getProductTotalRating($getAdInfo['mallAdID'])['message'],1);?> ☆</a>
                                            <!-- <span class="vr me-1"></span> -->
                                            <input type="text" class="text-copy d-none" value="<?php echo $url;?>/customer_review.php?adrec=B787Bbb87bsbd8nsd87877fds8f99fs9fdf8df9df&adtok=<?php echo $getAdInfo['mallAdID']?>">
                                            <button class="btn btn-primary btn-sm click-to-copy fs-sm mx-2" text-copy="sfdss">Copy Link </button>

                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <!-- Not Found -->
                            <div class="ha-none__display w-50 text-center m-5 mx-auto">
                                <img class="img-fluid mx-auto" src="./assets/images/bg-01.png" id="adverts1">
                                <div class="fs-title-1 mt-3" id="adverts2">No Ad or Feedback yet.</div>
                            <?php
                        }
                            ?>
                            </div>
                    </div>
                </div>

            </div>
    </section>


    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/userAdmin.js"></script>
</body>
</html>