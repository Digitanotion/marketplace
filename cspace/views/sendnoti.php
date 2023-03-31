<?php
    header("content-type: json");
    require_once(__DIR__ ."\../../vendor/autoload.php");
    USE services\AdS\AdManager;
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;

    $messenger = new messagingManager();
    $response = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], $_POST['message']);

    if($response['status'] == 1){
        echo 1;
    }else if($response['status'] == 500){
        echo 500;
    }