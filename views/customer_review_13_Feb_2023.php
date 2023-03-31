<?php
ob_start();
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

use services\AdS\AdManager;
use services\MedS\MediaManager;
use services\AccS\AccountManager;
use services\AudS\AuditManager;
use services\MsgS\feedbackManager;
use services\SecS\SecurityManager;

$securityManager_ob = new SecurityManager();
$adsManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$feedback_ob = new feedbackManager();
$mediaManager = new MediaManager();
$timeAgo_ob = new AuditManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg = [];
if (!$securityManager_ob->is_user_auth__()) {
    header("location: ./Signin.php");
}
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : header("location: ./Signin.php");
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrBizInfo = "";
if (isset($_GET['view_business'])) {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
} else {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}
$adDetails = null;
$feedBackDetails = null;
$commentsCount = 0;
$adThumbImageName = null;
$getAllAdComments = null;
$getProductUsrRating = 0;
if (isset($_POST['user_comment__btn'])) {
    if ($securityManager_ob->validateCSRF($_POST['form_token__input'])) {
        $getUserComment = $_POST['user_comment'];
        $getRepliedComment = $_POST['user_comment__ID'];
        $rating = $_POST['rating_star'];
        $userCommentResp = $feedback_ob->makeNewComment($pageUsrID__, $_GET['adtok'], $getUserComment, $rating, $getRepliedComment);
        $sys_msg['msg_type'] = $userCommentResp['status'];
        $sys_msg['msg'] = $userCommentResp['message'];
        if ($userCommentResp['status'] == "404") {
            echo "<script>
            setTimeout(function (e) {
                window.location='./';
            },5000); 
        </script>";
        }
    }
}
if (!isset($_GET['adtok'])) {
    header("location: ./");
} else {
    $adDetails = $adsManager_ob->getAllAdByID($_GET['adtok']);
    $adDetailsTitle = $adsManager_ob->getAllAdByID($_GET['adtok']);
    if ($adDetails['status'] != 1) {
        header("location: ./");
    } else {
        $adDetails = $adDetails['message'];
        $getProductUsrRating = $feedback_ob->getAdUsrRating($_GET['adtok'])['message'][0]['mallUsrRating'];
        $adThumbImageName = $mediaManager->getThumbImage($adDetails['mallAdID']);
        if ($adThumbImageName['status'] == 1) {
            $adThumbImageName = $adThumbImageName['message']['mallMediaName'];
        } else {
            $adThumbImageName = "";
        }
        $feedBackDetails = $feedback_ob->getProductFeedbacks($_GET['adtok']);
        if ($feedBackDetails['status'] == 1) {
            $getAllAdComments = $feedBackDetails['message'];
            $commentsCount = $feedBackDetails['message']->rowCount();
        }
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
    <title><?php echo ($adDetailsTitle['status'] == 1) ? $adDetailsTitle['message']['mallAdTitle'] : "Product review"; ?> | Gaijinmall</title>
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
    <?php include "header-top.php"; ?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <?php include "sidebar.php"; ?>
            <form method="POST" action="" class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <input type="hidden" required value="<?php echo $newToken; ?>" name="form_token__input">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <!-- <span class="fs-title-3 fw-bolder" id="prof1">Feedback</span> -->
                        <div class="d-flex flex-row align-items-center text-left comment-top p-2 bg-white px-4">
                            <div class="profile-image pe-3">

                                <div class="ha-seller__avatar__wrapper">
                                    <div class="ha-seller__icon profile-image-init__avatar bg-purple" style="background-image: url('../handlers/uploads/thumbs/<?php echo $adThumbImageName; ?>');">

                                        <?php $initialLetter = $adDetails['mallAdTitle'];

                                        if ($adThumbImageName == "" || $adThumbImageName == null) {
                                            echo $initialLetter[0];
                                        } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column ml-3">
                                <div class="d-flex flex-row post-title">
                                    <h5><?php echo $adDetails['mallAdTitle']; ?></h5>
                                </div>
                                <div class="d-flex flex-row align-items-center align-content-center post-title">
                                    <span class=" me-1 comments"><?php echo $commentsCount; ?> <i class="fa fa-comments-o m-0"></i></span>
                                    <!-- <span class="vr me-1"></span> -->
                                    <span class="bdge me-1"><?php echo number_format($feedback_ob->getProductTotalRating($adDetails['mallAdID'])['message'],1);?>☆</span>
                                    <div class="rating-star">
                                        <input type="radio" name="rating_star" <?php echo ($getProductUsrRating == "5") ? "checked" : ""; ?> value="5" id="5"><label for="5">☆</label> <input type="radio" name="rating_star" value="4" id="4" <?php echo ($getProductUsrRating == "4") ? "checked" : ""; ?>><label for="4">☆</label> <input type="radio" name="rating_star" value="3" id="3" <?php echo ($getProductUsrRating == "3") ? "checked" : ""; ?>><label for="3">☆</label> <input type="radio" name="rating_star" value="2" id="2" <?php echo ($getProductUsrRating == "2") ? "checked" : ""; ?>><label for="2">☆</label> <input type="radio" name="rating_star" value="1" id="1" <?php echo ($getProductUsrRating == "1") ? "checked" : ""; ?>><label for="1">☆</label>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="text-center">
                        <!-- <span class="btn text-primary fs-md p-2" onclick="myActv()" id="actvs"> Active <span class="ha-count__Active badge bg-primary">0</span></span>
                        <div class="vr"></div>
                        <span class="btn text-secondary fs-sm p-2" onclick="myRev1()" id="revws"> In Review <span class="ha-count__Active badge bg-secondary">0</span></span>
                        <div class="vr"></div>
                        <span class="btn text-danger fs-sm p-2" onclick="myDecl()" id="decls"> Declined <span class="ha-count__Active badge bg-danger">0</span></span> -->
                        <!-- <span class="btn text-secondary fs-sm p-2 feedsico fedes0 d-inline-block" id="feedsicon0" onclick="myRecvd()"> Recieved <span class="ha-count__Active badge bg-secondary">0</span></span>
                        <div class="vr"></div>
                        <span class="btn text-danger fs-sm p-2 feedsico fedes1 d-inline-block" id="feedsicon1" onclick="mySents()"> Sent <span class="ha-count__Active badge bg-danger">0</span></span> -->
                    </div>

                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body">
                    <div class="ha-none__display w-100 mx-auto mt-2">
                        <div class="coment-bottom bg-white p-2 px-4">
                            <div class="d-flex flex-row add-comment-section">
                            <div class="ha-seller__avatar__wrapper">
                                <?php 
                                    $adThumbImageName_comm = $mediaManager->getUsrThumbImage($pageUsrID__);
                                    $usrImage="";
                                    if ($adThumbImageName_comm ['status'] == 1) {
                                        if ($adThumbImageName_comm['message']['mallUsrPhoto']!=""){
                                            $usrImage=$adThumbImageName_comm['message']['mallUsrPhoto'];
                                        }
                                    } 
                                ?>
                                    <div class="ha-user-comment__icon profile-image-init__avatar bg-purple " style="background-image: url('../media_store/usrPictures/thumbs/<?php echo $usrImage ; ?>">

                                        <?php $initialLetter_comm  = $getUsrInfo['mallUsrFirstName'];

                                        if ($usrImage  == "") {
                                            echo $initialLetter_comm [0];
                                        } ?>
                                    </div>
                                </div>
                                <input type="text" name="user_comment" id="user-comment-msg" class="form-control fs-sm mr-3" placeholder="Add comment">
                                <input type="hidden" id="user-comment-id" name="user_comment__ID">
                                <button class="btn btn-primary fs-md-1" type="submit" name="user_comment__btn">Comment</button>
                            </div>
                            <div class="comments-box">
                                <?php
                                if ($feedBackDetails['status'] == 1) {
                                    foreach ($getAllAdComments->fetchAll() as $commentsAll) {
                                        $getUsrDetails = $usrAccManager_ob->getUsrBasicInfoByID($commentsAll['mallUsrID'])['message'];
                                        if ($commentsAll['mallFeedParent']==""){
                                ?>
                                        <div class="commented-section mt-3 ms-5">
                                            <div class="d-flex flex-row align-items-center commented-user">
                                                <h5 class="me-1 fs-md-2"><?php echo $getUsrDetails['mallUsrFirstName'] ?></h5><span class="dot mb-1"></span><span class="mb-1 ms-1"><?php echo $timeAgo_ob->time_ago( $commentsAll['mallFeedTimePosted'])?></span>
                                            </div>
                                            <div class="comment-text-sm"><span><?php echo $commentsAll['mallFeedMessage'] ?></span></div>
                                            <div class="reply-section">
                                                <div class="d-flex flex-row align-items-center voting-icons"><span class="ms-1"><?php echo $feedback_ob->countComments($commentsAll['mallFeedBackID'])['message']; ?> <i class="fa fa-comments-o m-0"></i></span>
                                                    <button type="button" class="ms-1 btn text-primary fs-sm reply-comment" id="reply-comment" user-firstname="<?php echo strtolower($getUsrDetails['mallUsrFirstName']); ?>" datacomment="<?php echo $commentsAll['mallFeedBackID'] ?>">Reply</button>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                        $getReplyComments = $feedback_ob->getCommentReplyByParent($commentsAll['mallFeedBackID']);
                                        if ($getReplyComments['status'] == 1) {
                                            foreach ($getReplyComments['message'] as $replyEach) {
                                                $getUsrDetails = $usrAccManager_ob->getUsrBasicInfoByID($replyEach['mallUsrID'])['message'];
                                        ?>
                                                <div class="commented-section-reply mb-1 ms-5 ">
                                                    <div class="ms-4 bg-light-blue p-3">
                                                        <div class="d-flex flex-row align-items-center commented-user">
                                                            <h5 class="me-1 fs-md-1"><?php echo $getUsrDetails['mallUsrFirstName'] ?></h5><span class="dot mb-1"></span><span class="mb-1 ms-1"><?php echo $timeAgo_ob->time_ago( $replyEach['mallFeedTimePosted'])?></span>
                                                        </div>
                                                        <div class="comment-text-sm"><span><?php echo $replyEach['mallFeedMessage'] ?></span></div>
                                                        <!-- <div class="reply-section">
                                                            <div class="d-flex flex-row align-items-center voting-icons"><span class="ms-1">10 <i class="fa fa-comments-o m-0"></i></span>
                                                                <button class="ms-1 btn text-primary fs-sm">Reply</button>
                                                            </div>

                                                        </div> -->
                                                    </div>
                                                </div>
                                <?php
                                            }
                                        }
                                    }
                                }
                                }
                                else{
                                    ?>
                                    <div class="ha-none__display w-50 text-center m-5 mx-auto">
                                <!-- <img class="img-fluid mx-auto" src="./assets/images/bg-01.png" id="adverts1"> -->
                                <div class="fs-title-4 fw-bolder" id="adverts2">No Comment yet.</div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </section>
    <?php include "footer.php"; ?>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/userAdmin.js"></script>
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
<?php ob_end_flush()?>