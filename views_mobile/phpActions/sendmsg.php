<?php
    require_once(__DIR__ ."\../../../vendor/autoload.php");
    USE services\AdS\AdManager;
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;

    $messenger = new messagingManager();
    $response = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], $_POST['message']);

    echo $response['status'];