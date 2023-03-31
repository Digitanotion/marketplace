<?php
ob_start();
//Confirm if file is local or Public and add the right path
session_start();
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
  require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} else {
  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}

use services\AdS\AdManager;
use services\AudS\AuditManager;
use services\MedS\MediaManager;
// use services\InitDB;
use services\AccS\AccountManager;
use services\MsgS\feedbackManager;
use services\SecS\SecurityManager;
use services\MsgS\messagingManager;

$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$mediaManager = new MediaManager();
$audService_ob = new AuditManager();
$messaging_ob = new messagingManager();
$feedback_ob=new feedbackManager();

/* if (!$securityManager_ob->is_user_auth__()){
  header("location: Signin.php");
} */
$adID = "";
$pageUsrID__=$_SESSION['gaijinmall_user_'];
/*

$getCurrentUserInfo=$securityManager_ob->getUserInfoByID($usrID);
$getUsrInfo="";
if ($getUsrInfo['status']!=1){
  header("location: Signin.php");
}
else{
  $getUsrInfo=$getUsrInfo['message'];
}*/
$sys_msg = [];
$allAdByID = array();
$getUsrInfo = "";
$getUsrBizInfo="";
if (isset($_POST['reportAd__btn'])){
     $reportAdResponse=$feedback_ob->reportAd($pageUsrID__,$_POST['reportAd__Select'],$_POST['reportAdMsg__txt']);
    $sys_msg['msg_type'] = $reportAdResponse['status'];
    $sys_msg['msg'] = $reportAdResponse['message'];
   
}
if (isset($_GET['adID'])) {
  $adID = $securityManager_ob->sanitizeItem($_GET['adID'], "string");
  $allAdByID = $adManager_ob->getAdByID($adID);
  if ($allAdByID['status'] == "1") {
    $allAdByID = $allAdByID['message'];
    $getUsrInfo = $securityManager_ob->getUserInfoByID($allAdByID['mallUsrID']);
    $getUsrInfo = $getUsrInfo['message'];
  } else {
    header("Location: ./");
  }
}

//SET AD VIEW
$adManager_ob->updateAdView($adID);
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "null";
if (isset($_POST['makeOffer__btn'])) {
  if ($pageUsrID__ == "null") {
    $sys_msg['msg_type'] = 500;
    $sys_msg['msg'] = "Login to make an offer";
  } else {
    $makeOffer_response = $adManager_ob->usrMakeOffer($pageUsrID__, $adID, $_POST['makeOfferPrice__txt'], $_POST['makeOfferReceiverID']);
    $sys_msg['msg_type'] = $makeOffer_response['status'];
    $sys_msg['msg'] = $makeOffer_response['message'];
  }
}
if (isset($_POST['sendChat__btn'])) {
  if ($pageUsrID__ == "null") {
    $sys_msg['msg_type'] = 500;
    $sys_msg['msg'] = "Login to chat with seller";
  } else {
    $sendChat_response = $messaging_ob->sendMsgUsrToUsr($pageUsrID__, $adID, $getUsrInfo['mallUsrID'], $_POST['sendChatMsg__txt'], "usr_to_usr");
    $sys_msg['msg_type'] = $sendChat_response['status'];
    $sys_msg['msg'] = $sendChat_response['message'];
  }
}
//Get image for favicon
$favImageName = $mediaManager->getThumbImage($allAdByID['mallAdID']);
if ($favImageName['status'] == 1) {
  $favImageName = $favImageName['message']['mallMediaName'];
} else {
  $favImageName = "";
}
if (isset($_POST['saveAd__btn'])) {
  $getAdIDSaved = $_POST['saveAd__adID'];
  $saveAdResponse = $adManager_ob->saveAd($pageUsrID__, $getAdIDSaved);
  $sys_msg['msg_type'] = $saveAdResponse['status'];
  $sys_msg['msg'] = $saveAdResponse['message'];
}

$productRating=$feedback_ob->getProductTotalRating($adID)['message'];
//Generate new CSRF TOken
$newToken = $securityManager_ob->setCSRF();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $allAdByID['mallAdTitle']; ?></title>
  <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <link rel="stylesheet" href="./assets/fonts/inter/style.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="../dependencies/node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/products.css">
  <link rel="stylesheet" href="./assets/css/vertical-menu.css">
  <link rel="stylesheet" href="assets/css/cute-alert.css">
  <link rel="icon" href="../handlers/uploads/thumbs/<?php echo $favImageName; ?>">
  <meta name="Description" content="<?php echo $allAdByID['mallAdDesc']; ?>">
  <meta name="theme-color" content="#c3e6ff">
  <meta property="og:title" content="<?php echo $allAdByID['mallAdTitle']; ?>" />
  <meta property="og:description" content="<?php echo $allAdByID['mallAdDesc']; ?>" />
  <meta property="og:image" content="https://gaijinmall.com/handlers/uploads/thumbs/<?php echo $favImageName; ?>">
  <meta property="og:image:width" content="400" />
  <meta property="og:image:height" content="300" />
  <meta property="og:type" content="Classified Store" />
  <meta property="og:url" content="product.php?adID=<?php echo $adID; ?>" />
  <meta property="og:site_name" content="Gaijinmall" />
</head>

<body class="bg-light-blue">
  <?php include "header-top.php"; ?>

  <div class="container p-0 p-md-0 mx-auto">
    <!--  <div class="row m-0 justify-content-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">All</a></li>
                  <li class="breadcrumb-item"><a href="#">Agriculture & Equipments</a></li>
                  <li class="breadcrumb-item"><a href="#">Farm tools</a></li>
                  <li class="breadcrumb-item"><a href="#">Point of Sale</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data</li>
                </ol>
              </nav>
        </div> -->
    <div class="row m-0">
      <div class="col-md-8 me-md-2 me-md-2 ">
        <div class="container p-0 m-0 g-0">
          <div class="row ha-picture__wrapper">
            <div id="adImageCarousel" class="carousel slide " data-bs-ride="carousel">

              <div class="carousel-inner" style="">
                <?php
                $imageName = $mediaManager->getAllAdImage($adID);
                if ($imageName['status'] == 1) {
                  $imageName = $imageName['message'];
                  $countSlideTo = 0;
                  foreach ($imageName as $imageFileName) {
                ?>
                    <div class="carousel-item <?php echo ($countSlideTo == 0) ? 'active' : ""; ?>">
                      <div class="zoom-item" id="zoom-item">
                        <img src="../handlers/uploads/optimized/<?php echo $imageFileName['mallMediaName'] ?>" class=" w-100 " alt="...">

                      </div>
                    </div>
                <?php $countSlideTo++;
                  }
                } else {
                  $thumbImageName = "";
                }
                ?>


                <span class="ha-card__counter"><i class="fa fa-eye m-0 me-1"></i><span class="ha-views__count"><?php echo $adManager_ob->getAdView($adID)['message']; ?></span> views</span>
                <form method="post">
                  <button <?php echo ($adManager_ob->checkSaveAd($pageUsrID__, $_GET['adID'])['status']) ? "disabled" : "type='submit' formaction=''"; ?> class="ha-card-content-icon <?php echo ($adManager_ob->checkSaveAd($pageUsrID__, $_GET['adID'])['status']) ? "bg-primary text-white" : ""; ?> fw-bolder shadow-sm d-flex justify-content-center align-items-center" name="saveAd__btn">
                    <i class="fa fa-save mx-auto fa-bounce"></i>
                  </button>
                  <input type="hidden" name="saveAd__adID" value="<?php echo $_GET['adID'] ?>">
                </form>

                <a class="ha-card-content-icon-1 d-flex justify-content-center align-items-center text-dark" href="#">
                  <i class="fa fa-map-marker fw-bolder fs-6 mx-1 fa-bounce text-info"></i> <?php $adPosterLoc = explode(".", $allAdByID['mallAdLoc']);
                                                                                            echo ucfirst($adPosterLoc[0]) . ", " . ucfirst($adPosterLoc[1]); ?>, Japan
                </a>
              </div>

              <!-- <ol class="carousel-indicators list-inline">
                                      <?php
                                      /* $imageName1=$mediaManager->getAllAdImage($adID);
                                            if ($imageName1['status']==1){
                                              $imageName1=$imageName1['message'];
                                              $countSlideTo1=0;
                                              foreach ($imageName1 as $imageFileName1) { */ ?>
                                          <li class="list-inline-item"> <button type="button" data-bs-target="#adImageCarousel" data-bs-slide-to="<?php echo $countSlideTo1; ?>" class="<?php echo ($countSlideTo1 == 0) ? 'active' : ""; ?>"  aria-current="true" aria-label="Slide <?php echo $countSlideTo1 + 1; ?>"><img src="../handlers/uploads/thumbs/<?php echo $imageFileName1['mallMediaName'] ?>" class="img-fluid" style="object-fit:cover;"></button></li>
                                         
                                          <?php /* //$countSlideTo1++;
                                          }
                                          } */ ?>
                                    </ol> -->
              <button class="carousel-control-prev" type="button" data-bs-target="#adImageCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#adImageCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>

            </div>

          </div>
          <div class="row my-4 my-md-4 my-lg-4">
            <div class="card m-0">
              <div class="card-body">
                <h4 class="fw-bolder"><?php echo $allAdByID['mallAdTitle']; ?></h4>
                <div class="">
                  <p class="fs-md-1"><?php echo $allAdByID['mallAdDesc']; ?></p>
                  <?php
                  $getActiveAdOptions = $adManager_ob->getAdDetailsOptions($allAdByID['mallAdID']);
                  if (count($getActiveAdOptions['message']) > 0) { ?>
                    <button class="btn btn-outline-primary m-0 w-100" type="button" data-bs-toggle="collapse" data-bs-target="#ha-product__attributes" aria-expanded="false" aria-controls="ha-product__attributes">
                      Show Ad Detail...
                    </button>
                  <?php } ?>
                </div>
                <div class="row collapse mt-4 gy-2" id="ha-product__attributes">
                  <?php
                  if ($getActiveAdOptions['status'] == 1) {
                    foreach ($getActiveAdOptions['message'] as  $key => $value) {
                      //$getImageCount=$adManager_ob->countAdImagesByID($value['mallAdID'])
                      //$childCategory=$adManager_ob->getCategChildByID($value['mallCategID']);
                  ?>
                      <div class="col-6 col-md-3">
                        <div class="card">
                          <div class="card-header fw-bolder">
                            <!-- <i class=""></i> --><?php echo strtoupper($key); ?>
                          </div>
                          <div class="card-body fw-bolder fs-md">
                            <?php echo $value; ?>
                          </div>

                        </div>
                      </div>
                  <?php }
                  } ?>
                </div>

                <div class="container ha-social__share mt-3 d-flex justify-content-center">
                  <a href="mailto:?subject=<?php echo $allAdByID['mallAdTitle']; ?>&body=<?php echo urlencode($url); ?>" target="_blank" rel="nofollow" class="fa fa-envelope-o ms-2"></a>
                  <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>&text=<?php echo $allAdByID['mallAdTitle']; ?>" target="_new" class="fa fa-facebook ms-2"></a>
                  <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url); ?>&text=<?php echo $allAdByID['mallAdTitle']; ?>" target="_new" class="fa fa-twitter ms-2"></a>
                  <a href="whatsapp://send?text=<?php echo urlencode($url . " From Gaijinmall"); ?>" target="_new" class="fa fa-whatsapp ms-2"></a>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-0 my-md-0 my-lg-0">
        <div class="card py-3 px-3 shadow-sm border-0">
          <div class="card-body py-2 m-0">
            <span class="ha-price_side_text"><?php echo $adManager_ob::CURRENCY . number_format($allAdByID['mallAdPrice']); ?></span>
            <div class="form-group">
              <div class="ha-price_button_offer my-2">
                <button class="ob-make-offer__btn btn btn-outline-primary w-100 make_offer_btn" <?php echo ($pageUsrID__ == "null") ? "" : 'data-bs-toggle="modal" data-bs-target="#makeOffer"'; ?> >Make an offer</button>

              </div>


            </div>
            <div class="text-center mt-3 fs-6">
                <span class="bg-primary text-white p-1 me-1 mb-3 fs-title-2" style="line-height: 25px;"><?php echo number_format($productRating,1);?></span> <i class="fa <?php echo ($productRating>0)?"fa-star":"fa-star-o";?> m-0 text-orange"></i><i class="fa <?php echo ($productRating>1)?"fa-star":"fa-star-o";?> m-0 text-orange"></i><i class="fa <?php echo ($productRating>2)?"fa-star":"fa-star-o";?> m-0 text-orange"></i><i class="fa <?php echo ($productRating>3)?"fa-star":"fa-star-o";?> m-0 text-orange"></i><i class="fa <?php echo ($productRating>4)?"fa-star":"fa-star-o";?> m-0 text-orange"></i>
                <br>
                <a href="./customer_review.php?adrec=B787Bbb87bsbd8nsd87877fds8f99fs9fdf8df9df&adtok=<?php echo $allAdByID['mallAdID'];?>" class="fs-sm mt-2 text-primary">View product reviews</a>

            </div>
          </div>
        </div>

        <div class="card py-3 px-3 shadow-sm border-0 my-4">
          <div class="card-body py-2 m-0">
            <a href="#">
              <div class="ha-seller__avatar__wrapper">
                <a href="<?php
                $getUsrBizInfo = (new AccountManager())->getUsrBizInfoByID($getUsrInfo['mallUsrID'])['message']; echo ($getUsrBizInfo['mallBizSlug']=="" || $getUsrBizInfo['mallBizSlug']=="NULL")?"#" : "shop/".$getUsrBizInfo['mallBizSlug']; ?>" class="ha-seller__icon profile-image-init__avatar bg-purple" style="background-image: url('');">
                  <div class="ha-seller-status <?php echo ($getUsrInfo['mallUsrOnline'] == 1) ? "bg-success" : "bg-grey"; ?>"></div>
                  <!---->
                  <?php $initialLetter = $getUsrInfo['mallUsrFirstName'];
                  echo $initialLetter[0]; 
                  ?>
                </a>
                <div class="ha-seller__info">
                  <div class="ha-seller__name fs-title-1 ">
                  <a href="<?php echo ($getUsrBizInfo['mallBizSlug']=="" || $getUsrBizInfo['mallBizSlug']=="NULL")?"#" : "shop/".$getUsrBizInfo['mallBizSlug']; ?>"><?php echo $getUsrInfo['mallUsrFirstName'] . " " . $getUsrInfo['mallUsrLastName']; ?></a>
                    
                  </div>
                  <span class="badge bg-light text-dark fs-sm"><i class="fa fa-clock-o m-0"></i>&nbsp;<span class="badge bg-dark text-light"><?php echo $audService_ob->time_ago($getUsrInfo['mallUsrRegTime']); ?></span> in Gaijinmall</span>
                </div>
              </div>
            </a>

            <div class="form-group">
              <div class="ha-price_button_offer my-2">
                <a class="ob-make-offer__btn btn btn-primary w-100" href="tel:<?php echo $getUsrInfo['mallUsrPhoneNo']; ?>">Call Seller</a>
              </div>
              <div class="form-group">
                <div class="ha-price_button_offer my-2">
                  <button <?php echo ($pageUsrID__ == "null") ? "" : 'data-bs-toggle="modal" data-bs-target="#sendDM"'; ?> class="ob-make-offer__btn btn btn-outline-primary w-100 fw-bolder sendDM" ><i class="fa fa-comments fs-5"></i>Send a Message</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card py-3 px-3 shadow-sm border-0">
          <div class="card-body py-2 m-0">
            <span class="ha-price_side_text">Be Safe</span>
            <p>
            <ul class="b-advert-safety-list">
              <li>
                Don't pay in advance, even for delivery
              </li>
              <li>
                Meet with the seller at a safe public place
              </li>
              <li>
                Inspect the item and ensure it's exactly what you want
              </li>
              <li>
                Make sure that the packed item is the one you've inspected
              </li>
              <li>
                Only pay if you're satisfied
              </li>
            </ul>
            </p>
            <div class="form-group d-flex justify-content-center">
              <div class="ha-price_button_offer my-2">
                <button class="ob-make-offer__btn btn btn-outline-danger btn-sm reportAd__btn" <?php echo ($pageUsrID__ == "null") ? "" : 'data-bs-toggle="modal" data-bs-target="#reportAd"'; ?>><i class="fa fa-flag"></i>Report Ad</button>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <h4 class="m-0 mb-2">Similar Adverts</h4>
            <?php
            $getSimilarAds = $adManager_ob->getSimilarAds($allAdByID['mallAdTitle'], $allAdByID['mallCategID']);
            if ($getSimilarAds['status'] == 1) {
              foreach ($getSimilarAds['message'] as  $value) {
                $getImageCount = $adManager_ob->countAdImagesByID($value['mallAdID']);
                if ($adID!=$value['mallAdID']){
                //$childCategory=$adManager_ob->getCategChildByID($value['mallCategID']);
            ?>
                <div class="row ha-sim-ad-pic__wrapper p-3">
                  <?php
                  $thumbImageName = $mediaManager->getThumbImage($value['mallAdID']);
                  if ($thumbImageName['status'] == 1) {
                    $thumbImageName = $thumbImageName['message']['mallMediaName'];
                  } else {
                    $thumbImageName = "";
                  }
                  ?>
                  <div class="col-4 bg-warning rounded-start ha-sim-ad-img__item ha-item-each__cardimg" datavalue="<?php echo $value['mallAdID'];?>" datavalueTitle="<?php echo str_replace(" ", "-",$value['mallAdTitle']);?>" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                    <?php
                    if (!empty($value['mallAdCondition'])) {
                      echo '<span class="ha-sim-ad-item__title fs-md text-light text-center fw-bold opacity-50">' . $value['mallAdCondition'] . '</span>';
                    }
                    ?>
                    <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1"></i></span>
                    <?php $adManager_ob::displayPromoted($value['mallAdID'], $value['mallAdPromoID']); ?>
                  </div>
                  <div class="col-8 rounded-end bg-white">
                    <div class="">
                      <a href="product.php?adID=<?php echo $value['mallAdID']; ?>" class="text-dark">
                        <div class="my-2 ">
                          <span class="fs-title-1 fw-bolder"><?php echo $value['mallAdTitle'] ?></span>
                        </div>
                        <div class="">
                          <span class="ha-sim-ad-item__desc fs-md-1"><?php echo $value['mallAdDesc'] ?></span>
                        </div>
                        <div class="mt-2 py-auto">
                          <span class="badge bg-dark fs-6"><?php echo $adManager_ob::CURRENCY . number_format($value['mallAdPrice']); ?> </span><br>
                          <span class="badge bg-info"><i class="fa fa-map-marker"></i> <?php $adPosterLoc = explode(".", $value['mallAdLoc']);
                                                                                        echo ucfirst($adPosterLoc[0]) . ", " . ucfirst($adPosterLoc[1]); ?>, Japan</span>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
            <?php
              }}
            } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="makeOffer" tabindex="-1" aria-labelledby="makOfferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Make an Offer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="">
          <div class="modal-body">
            <div class="input-group mb-3">
              <span class="input-group-text"><?php echo $adManager_ob::CURRENCY;?></span>
              <input type="hidden" name="makeOfferReceiverID" value="<?php echo $getUsrInfo['mallUsrID'] ?>">
              <input type="text" class="form-control text-left" name="makeOfferPrice__txt" placeholder="Enter price" aria-label="Amount" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary w-100" name="makeOffer__btn">SEND</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="sendDM" tabindex="-1" aria-labelledby="makOfferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="">
          <div class="modal-body">
            <div class="form-floating">
              <textarea class="form-control" name="sendChatMsg__txt" id="sendChatMsg__txt" placeholder="Leave a comment here"></textarea>
              <label for="sendChatMsg__txt">Message</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="sendChat__btn" class="btn btn-primary w-100">SEND</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="reportAd" tabindex="-1" aria-labelledby="reportAd" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Report this Ad</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="">
          <div class="modal-body">
            <div class="form-floating mb-3">
            <select class="form-select select2" style="text-align: left;" name="reportAd__Select" id="reportAd__Select" >
                                <option value="null"></option>
                                <option value="Illegal/Fraudulent">Illegal/Fraudulent</option>
                                <option value="Wrong price">Wrong price</option>
                                <option value="Category is wrong">Category is wrong</option>
                                <option value="Ad is spam">Ad is spam</option>
                                <option value="Seller is scam">Seller is scam</option>
                                <option value="Product Sold">Product Sold</option>
                                <option value="I can't reach seller">I can't reach seller</option>
                                <option>Other</option>
                            </select>
                            <label for="reportAd__Select">Why do you report this Ad?</label>
            </div>
            <div class="form-floating">
              <textarea class="form-control" name="reportAdMsg__txt" id="reportAdMsg__txt" placeholder="Leave a comment here"></textarea>
              <label for="reportAdMsg__txt">Tell us about this issue</label>
            </div>
          </div>
          <div class="modal-footer">
          <input type="hidden" required value="<?php echo $newToken; ?>" name="form_token__input">
            <button type="submit" name="reportAd__btn" class="btn btn-primary w-100">Report Now</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  <?php include "footer.php"; ?>
  <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/jquery.inputmask.min.js"></script>
  <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="./assets/js/userAdmin.js"></script>
  <script src="assets/js/cute-alert.js"></script>
  <script>
    $('input').inputmask({
      rightAlign: false,
    });
    $(document).ready(function() {
      //$('#zoom-item').zoom();
    })
  </script>
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
        //   "hideMethod": "fadeOut" 08101371375
      }
      <?php
       if (isset($sys_msg) && !empty($sys_msg)) {
        switch ($sys_msg['msg_type']) {
            
            case '1':
              $sys_msg['msg'] = "Your message has been sent";
                echo '
                cuteAlert({
                    type: "success",
                    title: "Operation Successful",
                    message: "' . $sys_msg['msg'] . '",
                    buttonText: "Ok",
                  })';
                break;
            default:
            $sys_msg['msg'] = "Oops! Please, Try again";
            echo '
            cuteAlert({
              type: "error",
              title: "Operation Failed",
              message: "' . $sys_msg['msg'] . '",
              buttonText: "Ok",
            })';
                break;
        }
    }
      ?>
    });

    $(".select2").select2({
            theme: "bootstrap-5",
        })
  </script>
  
</body>

</html>
<?php
ob_end_flush(); ?>