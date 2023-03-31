<?php
namespace services\MsgS;
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ ."\../../vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else{
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
use services\MsgS\MessageData;
use services\AdS\AdManager as AdSAdManager;
USE services\InitDB; // Import DB Settings
use services\SecS\SecurityManager;

class feedbackManager{
    function sendFeedBack(int $userID){
        
    }
}

?>