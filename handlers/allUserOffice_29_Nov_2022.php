<?php
//Confirm if file is local or Public and add the right path
//session_start();
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

use services\AccS\AccountManager;
use services\AdS\AdManager;
use services\SecS\SecurityManager;
use services\SecS\InputValidator;
use services\MedS\MediaManager;
use services\MsgS\messagingManager;

$mediaManager_ob = new MediaManager();
$adManager_ob = new AdManager();
$security_ob = new SecurityManager();
$inputValidate_ob = new InputValidator();
$message_ob = new messagingManager();
$accManager_ob = new AccountManager();
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";
if (isset($_GET['p_source']) && $_GET['p_source'] != "") {
    $pageSource = $inputValidate_ob->sanitizeItem($_GET['p_source'], "string");
    $adID = $_GET['p_adcategory'];
    $usrID = $_GET['p_viewbyuser'];
    if ($pageSource == "viewUsrActiveAds") {
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
                <div class="row ha-mpage-items__wrapper mb-3" style="">
                    <div class="col-4 rounded-start ha-mpage-item__image ha-item-each__cardimg" datavalue="" datavalueTitle="" onclick="gotoProduct('<?php echo $fields['mallAdID']; ?>','<?php echo str_replace(' ', '-', $fields['mallAdTitle']); ?>')" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                        <?php
                        if (!empty($fields['mallAdCondition'])) {
                            echo '<span class="ha-mpage-item__title fs-6 text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                        }
                        ?>
                        <!-- <span class="ha-topright__comp dropdown " onclick="adMenuClicked()">
                            <button class="btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Ad Menu
                            </button>
                            <ul class="dropdown-menu fs-sm" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" >Edit Ad</a></li>
                                <li><a class="dropdown-item" >Delete Ad</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" >Boost Ad</a></li>
                            </ul>
                        </span> -->
                        <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1 me-0"></i></span>
                        <?php
                        if ($adManager_ob->checkPromotedAd($fields['mallAdID'], $fields['mallAdPromoID'])['status']) { ?>
                            <span class="ha-card-content-icon-1 fw-bolder text-dark d-flex justify-content-center align-items-center" href="#">
                                Sponsored Ad
                            </span>
                        <?php } ?>
                    </div>

                    <div class="col-8 rounded-end bg-light-blue text-dark" style="position: relative;">
                        <div class="my-2 my-md-4">
                            <a class=" " href="product.php?adID=<?php echo $fields['mallAdID']; ?>">
                                <span class="fs-title-1 fw-bolder"><?php echo $fields['mallAdTitle'] ?></span>
                            </a>
                            <div class="">
                                <span class="ha-mpage-item__desc fs-md-1"><?php echo $fields['mallAdDesc'] ?></span>
                            </div>
                            <div class="">
                                <span class="badge bg-dark fs-md mb-2"><?php echo $adManager_ob::CURRENCY . number_format($fields['mallAdPrice']); ?></span><br>
                                <span class="fs-sm-1 text-left mt-3"><i class="fa fa-map-marker m-0"></i> <?php $getlocationCateg = explode(".", $fields['mallAdLoc']);
                                                                                                            echo $getlocationCateg[0] . ", ", $getlocationCateg[1] ?>, Japan.</span><br>
                                <div class="d-flex mt-1">
                                    <span class="btn-sm border border-primary fs-sm" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</span>
                                    <span class="btn-sm btn-primary border border-primary text-white me-1 ms-1 fs-sm"><a class="text-white" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></span>
                                    <span class="btn-sm border border-primary fs-sm" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal">Delete Ad</span>
                                </div>
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="ha-mpage-item__metrics">
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-eye m-0 fs-md-2"></i> <span class="badge bg-dark"> <?php echo $adViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-comments-o fs-md-2 m-0 "></i> <span class="badge bg-dark"> <?php echo $chatRequests; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-phone fs-md-2 m-0"></i>&nbsp; <span class="badge bg-dark"> <?php echo $phoneNoViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2"><i class="fa fa-share fs-md-2 m-0"></i> <span class="badge bg-dark"> <?php echo $socialShares; ?></span></div>
                        </div>

                    </div>

                </div>
            <?php     }
        } else {
            echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
            <img class="img-fluid mx-auto" src="./assets/images/notfound3.svg" id="adverts1">
            <div class="fs-title-4 fw-bolder" id="adverts2">No active Ad found</div>
            <div class="fs-md" id="adverts3">No content availiable</div>
        </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
        }
    } elseif ($pageSource == "viewUsrReviewAds") {
        $getAds_response = $adManager_ob->getReviewAdsByUsrID($usrID);
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
                    <div class="col-4 rounded-start ha-mpage-item__image ha-item-each__cardimg" onclick="gotoProduct('<?php echo $fields['mallAdID']; ?>','<?php echo str_replace(' ', '-', $fields['mallAdTitle']); ?>')" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                        <?php
                        if (!empty($fields['mallAdCondition'])) {
                            echo '<span class="ha-mpage-item__title fs-6 text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                        }
                        ?>
                        <!-- <span class="ha-topright__comp" onclick="adMenuClicked()">
                            <button class="btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Ad Menu
                            </button>
                            <ul class="dropdown-menu fs-sm" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></li>
                                <li><a class="dropdown-item" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" class="" data-bs-toggle="modal">Delete Ad</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</a></li>
                            </ul>
                        </span> -->
                        <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1"></i></span>
                        <?php
                        if ($adManager_ob->checkPromotedAd($fields['mallAdID'], $fields['mallAdPromoID'])['status']) { ?>
                            <span class="ha-card-content-icon-1 fw-bolder text-dark d-flex justify-content-center align-items-center" href="#">
                                Sponsored Ad
                            </span>
                        <?php } ?>
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
                                <span class="badge bg-info fs-md mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> <?php $getlocationCateg = explode(".", $fields['mallAdLoc']);
                                                                                                                        echo $getlocationCateg[0] . ", ", $getlocationCateg[1] ?>, Japan.</span><br>
                                                                                                                        <div class="d-flex mt-1">
                                    <span class="btn-sm border border-primary fs-sm" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</span>
                                    <span class="btn-sm btn-primary border border-primary text-white me-1 ms-1 fs-sm"><a class="text-white" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></span>
                                    <span class="btn-sm border border-primary fs-sm" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal">Delete Ad</span>
                                </div>
                            </div>
                        </div>
                        <div class="ha-mpage-item__metrics">
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-eye m-0 fs-md-2"></i> <span class="badge bg-dark"> <?php echo $adViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-comments-o fs-md-2 m-0 "></i> <span class="badge bg-dark"> <?php echo $chatRequests; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-phone fs-md-2 m-0"></i>&nbsp; <span class="badge bg-dark"> <?php echo $phoneNoViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2"><i class="fa fa-share fs-md-2 m-0"></i> <span class="badge bg-dark"> <?php echo $socialShares; ?></span></div>
                        </div>
                    </a>
                </div>
            <?php     }
        } else {
            echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
            <img class="img-fluid mx-auto" src="./assets/images/bg-02.png" id="adverts1">
            <div class="fs-title-4 fw-bolder" id="adverts2">No in-review Ad found</div>
            <div class="fs-md" id="adverts3">No content availiable</div>
        </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
        }
    } elseif ($pageSource == "viewUsrDeclinedAds") {
        $getAds_response = $adManager_ob->getDeclinedAdsByUsrID($usrID);
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
                    <div class="col-4 rounded-start ha-mpage-item__image" onclick="gotoProduct('<?php echo $fields['mallAdID']; ?>','<?php echo str_replace(' ', '-', $fields['mallAdTitle']); ?>')" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                        <?php
                        if (!empty($fields['mallAdCondition'])) {
                            echo '<span class="ha-mpage-item__title fs-6 text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                        }
                        ?>
                        <!-- <span class="ha-topright__comp" onclick="adMenuClicked()">
                            <button class="btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Ad Menu
                            </button>
                            <ul class="dropdown-menu fs-sm" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></li>
                                <li><a class="dropdown-item" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" class="" data-bs-toggle="modal">Delete Ad</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</a></li>
                            </ul>
                        </span> -->
                        <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1"></i></span>
                        <?php
                        if ($adManager_ob->checkPromotedAd($fields['mallAdID'], $fields['mallAdPromoID'])['status']) { ?>
                            <span class="ha-card-content-icon-1 fw-bolder text-dark d-flex justify-content-center align-items-center" href="#">
                                Sponsored Ad
                            </span>
                        <?php } ?>
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
                                <span class="badge bg-info fs-md mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> <?php $getlocationCateg = explode(".", $fields['mallAdLoc']);
                                                                                                                            echo $getlocationCateg[0] . ", ", $getlocationCateg[1] ?>, Japan.</span><br>
                                                                                                                            <div class="d-flex mt-1">
                                    <span class="btn-sm border border-primary fs-sm" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</span>
                                    <span class="btn-sm btn-primary border border-primary text-white me-1 ms-1 fs-sm"><a class="text-white" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></span>
                                    <span class="btn-sm border border-primary fs-sm" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal">Delete Ad</span>
                                </div>
                            </div>
                        </div>
                        <div class="ha-mpage-item__metrics">
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-eye m-0 fs-md-2"></i> <span class="badge bg-dark"> <?php echo $adViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-comments-o fs-md-2 m-0 "></i> <span class="badge bg-dark"> <?php echo $chatRequests; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-phone fs-md-2 m-0"></i>&nbsp; <span class="badge bg-dark"> <?php echo $phoneNoViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2"><i class="fa fa-share fs-md-2 m-0"></i> <span class="badge bg-dark"> <?php echo $socialShares; ?></span></div>
                        </div>
                    </a>
                </div>
            <?php     }
        } else {
            echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
            <img class="img-fluid mx-auto" src="./assets/images/bg-04.png" id="adverts1">
            <div class="fs-title-4 fw-bolder" id="adverts2">No declined Ad found</div>
            <div class="fs-md" id="adverts3">No content availiable</div>
        </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
        }
    } elseif ($pageSource == "viewUsrExpireddAds") {
        $getAds_response = $adManager_ob->getExpiredAdsByUsrID($usrID);
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
                    <div class="col-4 rounded-start ha-mpage-item__image ha-item-each__cardimg" datavalue="<?php echo $fields['mallAdID']; ?>" datavalueTitle="<?php echo str_replace(" ", "-", $fields['mallAdTitle']); ?>" onclick="gotoProduct('<?php echo $fields['mallAdID']; ?>','<?php echo str_replace(' ', '-', $fields['mallAdTitle']); ?>')" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                        <?php
                        if (!empty($fields['mallAdCondition'])) {
                            echo '<span class="ha-mpage-item__title fs-6 text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                        }
                        ?>
                        <!-- <span class="ha-topright__comp" onclick="adMenuClicked()">
                            <button class="btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Ad Menu
                            </button>
                            <ul class="dropdown-menu fs-sm" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></li>
                                <li><a class="dropdown-item" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" class="" data-bs-toggle="modal">Delete Ad</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</a></li>
                            </ul>
                        </span> -->
                        <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1"></i></span>
                        <?php
                        if ($adManager_ob->checkPromotedAd($fields['mallAdID'], $fields['mallAdPromoID'])['status']) { ?>
                            <span class="ha-card-content-icon-1 fw-bolder text-dark d-flex justify-content-center align-items-center" href="#">
                                Sponsored Ad
                            </span>
                        <?php } ?>
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
                                <span class="badge bg-info fs-md mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> <?php $getlocationCateg = explode(".", $fields['mallAdLoc']);
                                                                                                                            echo $getlocationCateg[0] . ", ", $getlocationCateg[1] ?>, Japan.</span><br>
                                                                                                                            <div class="d-flex mt-1">
                                    <span class="btn-sm border border-primary fs-sm" onclick="sendAdIDToAdPromoPage(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal" promoadid="<?php echo $fields['mallAdID']; ?>" data-bs-target="#promoteThisAd">Boost Ad</span>
                                    <span class="btn-sm btn-primary border border-primary text-white me-1 ms-1 fs-sm"><a class="text-white" href="update_Ad?adUp=<?php echo $fields['mallAdID']; ?>">Edit Ad</a></span>
                                    <span class="btn-sm border border-primary fs-sm" href="#deleteAdModal" onclick="sendAdIDToAdPromoPageDel(<?php echo $fields['mallAdID']; ?>)" data-bs-toggle="modal">Delete Ad</span>
                                </div>
                            </div>
                        </div>
                        <div class="ha-mpage-item__metrics">
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-eye m-0 fs-md-2"></i> <span class="badge bg-dark"> <?php echo $adViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-comments-o fs-md-2 m-0 "></i> <span class="badge bg-dark"> <?php echo $chatRequests; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2 "><i class="fa fa-phone fs-md-2 m-0"></i>&nbsp; <span class="badge bg-dark"> <?php echo $phoneNoViews; ?></span></div>
                            <div class="fs-md my-0 my-md-2 my-lg-2"><i class="fa fa-share fs-md-2 m-0"></i> <span class="badge bg-dark"> <?php echo $socialShares; ?></span></div>
                        </div>
                    </a>
                </div>
    <?php     }
        } else {
            echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
            <img class="img-fluid mx-auto" src="./assets/images/bg-19.png" id="adverts1">
            <div class="fs-title-4 fw-bolder" id="adverts2">No expired Ad found</div>
            <div class="fs-md" id="adverts3">No content availiable</div>
        </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
        }
    }
}

if (isset($_GET['pointer'])) {
    $msgID = $_GET['msgID'];
    $pointer = $_GET['pointer'];
    ?>

    <?php
    $allChats = $message_ob->getLastUserMsgsByMsgID($msgID, $pointer);
    if ($allChats['status'] == 1) {
        $chatsEach = $allChats['message'];
    ?>

        <div class="mt-3 fs-md text-end d-flex <?php echo ($chatsEach['mallMsgSenderID'] == $pageUsrID__) ? "justify-content-end" : "justify-content-start"; ?>">
            <div class="p-2 px-4 border-0  rounded-3 <?php echo ($chatsEach['mallMsgSenderID'] == $pageUsrID__) ? "bg-primary my-reply " : "bg-white user-reply "; ?>">
                <?php echo $chatsEach['mallMsgValue']; ?>
                <p class="m-0 p-0 fs-md">
                    <?php echo date("d M, y. h:m", $chatsEach['mallMsgTime']);
                    echo ($chatsEach['mallMsgSenderID'] == $pageUsrID__) ? "<i class='fa fa-check-double '></i> " : ""; ?>
                </p>
            </div>
        </div>
    <?php } else {
        echo "error";
    } ?>
<?php } ?>

<?php
if (isset($_GET['sendmsg'])) {
    $msgID = $_GET['msgID'];
    $sendMsgNow = $message_ob->sendMsgUsrToUsr($_GET['sender'], $msgID, $_GET['receiver'], $_GET['msgContent'], "usr_to_usr", $msgID);
    if ($sendMsgNow['status'] == 1) {
        echo $sendMsgNow['message'];
    }
}

if (isset($_GET['disableChat'])) {
    echo $accManager_ob->updateUsrChats($_GET['by'], $_GET['status'])['status'];
}
if (isset($_GET['disableFeed'])) {
    echo $accManager_ob->updateUsrFeed($_GET['by'], $_GET['status'])['status'];
}

if (isset($_GET['deleteAccount'])) {
    echo $accManager_ob->deleteUsrAccount($_GET['by'], $_GET['delReason'], $_GET['delOtherReason'])['status'];
}

if (isset($_GET['followvendor'])) {
    echo json_encode($accManager_ob->followVendor($_GET['followvendor'], $_GET['user'], $_GET['vendor']));
}
//="+followStatus+"&user=" + loggedUser + "&vendor="+ VendorUser
?>