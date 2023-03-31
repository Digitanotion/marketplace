<?php
require_once(__DIR__ . "\../../vendor/autoload.php");

use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\AccS\AccountManager;
use services\MedS\MediaManager;
USE services\AudS\AuditManager;

$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$mediaManager_ob=new MediaManager();
$audService_ob=new AuditManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg = [];
if (!$securityManager_ob->is_user_auth__()){
    header("location: ".MALL_ROOT."/Signin.php");
} 
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";
$getUsrInfo = "";
$getUsrBizInfo = "";
$pageBizUsrID__="";
if (isset($_GET['view_business'])) {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
    $getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($_GET['view_business'])['message'];
    $pageBizUsrID__=$_GET['view_business'];
} elseif($pageUsrID__!="none") {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
    $getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
    $pageBizUsrID__=$pageUsrID__;
}
else{
    header("location: " . MALL_ROOT . "Signin.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $getUsrBizInfo['mallBizName']; ?>'s Shop | Gaijinmall</title>
    <link rel="stylesheet" href="../../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/seller.css">
    <link rel="stylesheet" href="../assets/css/vertical-menu.css">
    <link rel="stylesheet" href="../assets/css/adverts.css">
</head>

<body>
<?php
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
?>
<div id="ha-header__top" class="row bg-light-blue px-0 px-md-5 px-lg-5 justify-content-center align-items-center fixed-top py-3 ">
    <!--IF NOT SIGNED IN SHOW THIS-->
    <div class="col-md-4 col-lg-4 col-4">
        <a href="../">
            <div class="logo" style="max-width: 130px;">
                <img src="../assets/images/logo-sm.png" class="img-fluid">
            </div>
        </a>


    </div>
    <div class="col-md-3 col-lg-3 col-1 text-center ha-social__top ">
        <div class="d-none d-md-block d-lg-block fs-6">
            COOL BUY & SELL
        </div>
    </div>
    <div class="col-md-5 col-lg-5 col-5 text-end text-dark ha-fs_7 nav-container fw-bolder">
        <?php
        if ($securityManager_ob->is_user_auth__()) { ?>
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <ul class="">
                    <li><a class="active ha-nav__item" href="saved.php"><i class="fa fa-bookmark mx-auto"></i></a></li>
                    <li><a href="messages.php" class="ha-nav__item"><i class="fa fa-envelope mx-auto"></i></a></li>
                    <li><a href="notifications.php" class="ha-nav__item"><i class="fa fa-bell mx-auto"></i></a></li>
                    <li><a href="adverts.php" class="ha-nav__item"><i class="fa fa-list mx-auto"></i></a></li>
                    <!-- <li class="dropdown">
                                <a href="#" class="ha-nav__profile" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">N</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li> -->
                    <li class="nav-item dropdown ha-dropdown__top">
                        <a class="ha-nav__profile" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user m-0"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="page.php">My Page</a></li>
                            <li><a class="dropdown-item" href="feedback.php">Feedback</a></li>
                            <hr class="my-1">
                            <li><a class="dropdown-item" href="messages.php">My messages</a></li>
                            <li><a class="dropdown-item" href="personal_details_update.php">Settings</a></li>
                            <hr class="my-1">
                            <li><a class="dropdown-item fw-bolder" href="?logout=1">Log out</a></li>


                        </ul>
                    </li>
                </ul>
            </nav>



        <?php
        } else {
            echo '<a href="Signin.php" class="text-dark">Sign in</a>  | <a href="Signup.php" class="text-dark">Create Account</a>';
        }
        ?>

        <!--  -->
    </div>
    <!--IF NOT SIGNED IN SHOW THIS-->

</div>
<div class="mt-4 mt-md-5 mt-lg-5 mb-5 mb-md-5 mb-lg-5">
    <div class="mt-md-5 mt-lg-5">&nbsp;</div>
</div>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "../sidebar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="prof1">Adverts (Recent first)</span>
                    </div>
                    <div class="text-center">

                        <span class="btn text-primary fs-md p-2 acts0" id="actvs"> Active <span class="ha-count__Active badge bg-primary"><?php echo $adManager_ob->getAllAdsCountByUsrID($pageBizUsrID__, "active")['message']; ?></span></span>
                    </div>

                </div>
                <hr class="m-0 bg-hr-light">
                <input type="hidden" value="<?php echo $pageBizUsrID__ ?>" id="ha-userID" name="ha-userID">
                <div class="ha-profile-url-data__body mx-4 my-2">
                    <?php
                    //$adID = $_GET['view_business'];
                    $usrID = $pageBizUsrID__ ;
                        $getAds_response = $adManager_ob->getActiveAdsByUsrID($usrID);
                        if ($getAds_response['status'] == 1) {
                            /* <input type="hidden" value="<?php echo $_GET["adcategory"];?>" id="ha-categID" name="ha-categID"> */
                            foreach ($getAds_response['message'] as $fields) {
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
                                <div class="row ha-mpage-items__wrapper mb-3">
                                    <div class="col-4 rounded-start ha-mpage-item__image" style="background-image: url('../../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                                        <?php
                                        if (!empty($fields['mallAdCondition'])) {
                                            echo '<span class="ha-mpage-item__title fs-6 text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                                        }
                                        ?>
                                        
                                        <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1"></i></span>
                                        <a class="ha-card-content-icon-1 fw-bolder shadow-sm d-flex justify-content-center align-items-center" href="#">
                                            <i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i><i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i><i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i>
                                        </a>
                                    </div>
                                    <a href="product.php?adID=<?php echo $fields['mallAdID']; ?>" class="col-8 rounded-end bg-light-blue text-dark" style="position: relative;">
                                        <div class="">
                                            <div class="my-2 ">
                                                <span class="fs-title-1 fw-bolder"><?php echo $fields['mallAdTitle'] ?></span>
                                            </div>
                                            <div class="">
                                                <span class="ha-mpage-item__desc fs-md-1"><?php echo $fields['mallAdDesc'] ?></span>
                                            </div>
                                            <div class="mt-2 py-auto">
                                                <span class="badge bg-dark fs-6"><?php echo $adManager_ob::CURRENCY . number_format($fields['mallAdPrice']); ?></span><br>
                                                <span class="badge bg-info fs-md mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> <?php echo $fields['mallAdLoc'] ?>, Japan.</span>
                                            </div>
                                        </div>
                                        
                                    </a>
                                </div>
                    <?php     }
                        } else {
                            echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
                                    <img class="img-fluid mx-auto" src="./assets/images/notfound3.svg" id="adverts1">
                                    <div class="fs-title-4 fw-bolder" id="adverts2">No active Ad found</div>
                                    <div class="fs-md" id="adverts3">No content availiable</div>
                                </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
                        }
                    ?>
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