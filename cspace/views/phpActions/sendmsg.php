<?php
    //Confirm if file is local or Public and add the right path
    $url = 'http://' . $_SERVER['SERVER_NAME'];
    if (strpos($url,'localhost')) {
        require_once(__DIR__ . "\../../vendor/autoload.php");
    } else if (strpos($url,'gaijinmall')) {
        require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    }
    else{
        require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    }
    USE services\AdS\AdManager;
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;

    $messenger = new messagingManager();
    $response = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], $_POST['message']);

    echo $response['status'];