<?php
    //Confirm if file is local or Public and add the right path
    $url = 'http://' . $_SERVER['SERVER_NAME'];
    if (strpos($url,'localhost')) {
        require_once(__DIR__ . "\../../../vendor/autoload.php");
    } else if (strpos($url,'gaijinmall')) {
        require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    }
    elseif(strpos($url,'192.168.')){
        require_once(__DIR__ . "\../../../vendor/autoload.php");
    } else{
        require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    } 
    USE services\AccS\AccountManager;
    USE services\MsgS\messagingManager;


    $accManage = new AccountManager();
    $response = $accManage->verifyBiz($_POST["userID"],$_POST['approveStatus'],$_POST['usrPhone'],$_POST['kycID']);
    echo $response['status'];


    // $response = $accManage->updateKycStatus($_POST["userID"],$_POST['approveStatus'],$_POST['usrPhone'],$_POST['kycID']);
    //echo $_POST["userID"]."-".$_POST['approveStatus']."-".$_POST['usrPhone']."-".$_POST['kycID'];


    if($response['status'] == 1){
        //to avoid sending notification when the same status is to be set
        $messenger = new messagingManager();
        $response = $messenger->sendNotification($_POST['userID'], $_POST['adminId'], "Your Business has been verified");
    }

    
 