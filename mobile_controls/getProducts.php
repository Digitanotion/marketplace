<?php
//Confirm if file is local or Public and add the right path
//session_start();
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

use services\AccS\AccountManager;
use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\MedS\MediaManager;

$security_ob = new SecurityManager();
$accManager_ob = new AccountManager();
$adManager_ob = new AdManager();
$mediaManager = new MediaManager();
$pageUsrID__ = (isset($_POST['user_mob_id__']))?$_POST['user_mob_id__']: $_GET['user_mob_id__'];
//$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";

if (isset($_POST['_command_mobile']) && $_POST['_command_mobile'] == "get_ad_home") {
    //echo "I am here";
    $responseJSON = array();
    $getTrendingAds = $adManager_ob->getTrendingAds();
    if ($getTrendingAds['status'] == 1) {
        if ($getTrendingAds['status'] == 1) {
            foreach ($getTrendingAds['message'] as $adsItems) {
                $adID = $adsItems['mallAdID'];
                $checkUserBlocked = $accManager_ob->isThisUsrBlocked($pageUsrID__, $adsItems['mallUsrID']);
                if ($checkUserBlocked['status'] != 1) {
                    $getImageCount = $adManager_ob->countAdImagesByID($adsItems['mallAdID']);
                    $thumbImageName = $mediaManager->getThumbImage($adsItems['mallAdID']);
                    if ($thumbImageName['status'] == 1) {
                        $thumbImageName = $thumbImageName['message']['mallMediaName'];
                    } else {
                        $thumbImageName = "";
                    }
                    $imageFile = $thumbImageName;
                    $res = $mediaManager->checkOptimizedImage($imageFile);
                    $img_ = ($res["status"] == 1) ? $imageFile : "no_image.jpg";
                    $imageCount = $getImageCount['message'];
                    $isPromoted = $adManager_ob->checkPromotedAd($adsItems['mallAdID'], $adsItems['mallAdPromoID'])['status'];
                    $disguiseLink = str_replace(" ", "-", $adsItems['mallAdTitle']);
                    $link = "product.php?$disguiseLink&adID=$adID";
                    $mallAdTitle = $adsItems['mallAdTitle'];
                    $currency = $adManager_ob::CURRENCY;
                    $mallAdPrice = number_format($adsItems['mallAdPrice'], 0, '.', ',');
                    $promoID = $adsItems['mallAdPromoID'];
                    $responseJSON[] = [
                        "adID" => $adID,
                        "adTitle" => $mallAdTitle,
                        "adPrice" => $mallAdPrice,
                        "adImage" => $imageFile,
                        "adImageCount" => $imageCount,
                        "adIsPromoted" => $isPromoted,
                        "adCurrency" => "¥",
                        "adPromoID" => $promoID,
                        "adDisguiseLink" => $disguiseLink,
                        "adLink" => $link,
                    ];
                }
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($responseJSON, JSON_UNESCAPED_SLASHES);
}
