<?php
    require_once(__DIR__ ."\../../vendor/autoload.php");
    USE services\AdS\AdManager;
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;

    $accManager = new AccountManager();

    $result = $accManager->adminLogin($_POST['emailField'], $_POST['passwordField']);

    if($result['status'] == 1){
        
        echo 1;
    }else{
        echo 500;
    }