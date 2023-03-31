<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
use services\SecS\SecurityManager;
use services\AdS\AdManager;


$security_ob = new SecurityManager();
$accManager_ob = new AccountManager();
$adManager_ob = new AdManager();
//$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";

//if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="search_ad_"){
//$searchString=$_POST['searchString'];
$json["error"] = false;
$json["errmsg"] = "";
$json["icon"] = array();
$json["text"] = array();
$icons = array();
if (isset($_GET['getSubCategories'])) {
    $subCategories = $_GET['getSubCategories'];
    $responds = $adManager_ob->getCategChildByID($subCategories);
    if ($responds['status'] == 1) {
        //array_push($json["data"],$lists);
        $i = 0;
        $childrenID = explode(",", trim($responds['message']));
        foreach ($childrenID as $value) {
            if (!empty($value)){
                $getSubCategories=$adManager_ob->getCategoryByID($value);

                if($getSubCategories['status']==1){
                    $getSubCategories=$adManager_ob->getCategoryByID($value);
                    $getCategDetails=$getSubCategories['message'];
                    $countAds = number_format($adManager_ob->countCategory($getCategDetails['mallCategID'])['message']); //=$adManager_ob->countCategoryHasChild($value['mallCategID'])['message'];
                    # code...
                    $icons[] = [
                        "icon" => "views/assets/images/categoryicons/" . $getCategDetails['mallCategIcon'],
                        "text" => $getCategDetails['mallCategName'] ,
                        "catID" => $getCategDetails['mallCategID'],
                        "catChild" => $getCategDetails['mallCategChild'],
                        "catAdCount" => $countAds,
                    ];
                }
               
                //$icons["text"]=$value['mallCategName'];
                //$icons[$i][]=$value['mallCategIcon'];
                $i++;
            }
           
        }
        //array_push($json["icon"],$icons);
        //var_dump($icons);
        //echo json_encode($json);
        //echo json_encode($responds['message']['mallCategIcon']);
    } else {
        $json["error"] = true;
        $json["errmsg"] = "Oops! No match found";
    }
    header('Content-Type: application/json');
    echo json_encode($icons, JSON_UNESCAPED_SLASHES);
}
  
//}
