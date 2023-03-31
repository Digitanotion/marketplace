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


$security_ob = new SecurityManager();
$accManager_ob = new AccountManager();
$adManager_ob=new AdManager();
//$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";

//if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="search_ad_"){
    //$searchString=$_POST['searchString'];
    $json["error"] = false;
  $json["errmsg"] = "";
  $json["icon"] = array();
  $json["text"]=array();
  $icons=array();
  $responds=$adManager_ob->getAllMallParentCategory();
  if ($responds['status']==1){
    //array_push($json["data"],$lists);
    $i=0;
    foreach ($responds['message'] as $value) {
        $countAds=$adManager_ob->countCategoryHasChild($value['mallCategID'])['message'];
        # code...
        $icons[]= [
            "icon" => "views/assets/images/categoryicons/".$value['mallCategIcon']."-48.png", 
            "text" => $value['mallCategName']." ($countAds)",
            "catID" => $value['mallCategID'],
            "catChild" => $value['mallCategChild'], 
         ];
        //$icons["text"]=$value['mallCategName'];
        //$icons[$i][]=$value['mallCategIcon'];
        $i++;
    }
    //array_push($json["icon"],$icons);
    //var_dump($icons);
    //echo json_encode($json);
    //echo json_encode($responds['message']['mallCategIcon']);
  }else{
    $json["error"] = true;
      $json["errmsg"] = "Oops! No match found";
  }
  header('Content-Type: application/json');
  echo json_encode($icons,JSON_UNESCAPED_SLASHES);
//}
?>