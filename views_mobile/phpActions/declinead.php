<?php
    require_once(__DIR__ ."\../../../vendor/autoload.php");
    USE services\AdS\AdManager;
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;


    $adManage = new AdManager();
    $response = $adManage->setAdStatus($_POST["adID"], $_POST["adminID"], 2 );

    echo $response['status'];


    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $messenger = new messagingManager();
        $resp = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], $_POST['message']);

    }