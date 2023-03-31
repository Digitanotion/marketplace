<?php
    //Confirm if file is local or Public and add the right path
    $url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../../../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
elseif(strpos($url,'192.168.')){
    require_once(__DIR__ . "\../../vendor/autoload.php");
}
    USE services\AdS\AdManager;
    USE services\MsgS\messagingManager;


    $adManage = new AdManager();
    $response = $adManage->setAdStatus($_POST["adID"], $_POST["adminID"], 4 );

    echo $response['status'];

    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $messenger = new messagingManager();
        $ad = $_POST["adID"];
        $adDetails=$adManage->getAllAdByID($ad);
        //var_dump($adDetails);
        if ($adDetails['status']==1){
            $adTitle=$adDetails['message']['mallAdTitle'];
        $resp = $messenger->sendAdNotification($_POST['userID'], $_POST['adminID'], $_POST["adID"], "Your ad ($adTitle) has been deleted",$ad,"deleteAd");
        }
    }
    ?>