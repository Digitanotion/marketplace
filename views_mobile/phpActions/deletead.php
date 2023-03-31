<?php
    require_once(__DIR__ ."\../../../vendor/autoload.php");
    USE services\AdS\AdManager;
    USE services\MsgS\messagingManager;


    $adManage = new AdManager();
    $response = $adManage->setAdStatus($_POST["adID"], $_POST["adminID"], 4);

    echo $response['status'];

    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $messenger = new messagingManager();
        $id = $_POST["adID"];
        $resp = $messenger->sendNotification($_POST['userID'], $_POST['adminID'], "Your ad with id $id has been deleted");

    }
     