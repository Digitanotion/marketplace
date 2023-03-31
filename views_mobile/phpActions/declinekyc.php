<?php
    require_once(__DIR__ ."\../../../vendor/autoload.php");
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;


    $accManage = new AccountManager();
    $response = $accManage->updateKycStatus($_POST["userID"], 2 );


    echo $response['status'];

    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $messenger = new messagingManager();
        $response = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], $_POST['message']);
    }

    
 