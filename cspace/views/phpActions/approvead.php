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
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;


    $adManage = new AdManager();
    $response = $adManage->setAdStatus($_POST["adID"], $_POST["adminID"], 1 );

    echo $response['status'];
   
    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $ad = $_POST["adID"];
        $messenger = new messagingManager();
        $adDetails=$adManage->getAllAdByID($ad);
        //var_dump($adDetails);
        if ($adDetails['status']==1){
            $adTitle=$adDetails['message']['mallAdTitle'];
            $resp = $messenger->sendAdNotification($_POST['userID'], $_POST['adminID'], $ad,"Your ad ($adTitle) has been approved",$ad,"approveAd");
        }
        

    }