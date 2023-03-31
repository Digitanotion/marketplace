<?php
    require_once(__DIR__ ."\../../../vendor/autoload.php");
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;


    $accManage = new AccountManager();
     $messenger = new messagingManager();
    $response = $accManage->updateUserStatus($_POST["userID"], $_POST['adminID'], 1 );

    echo $response['status'];

    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $messenger = new messagingManager();
        $resp = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], "Your account has been activated successfully");
    
    }