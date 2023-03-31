<?php
    require_once(__DIR__ ."\../../vendor/autoload.php");
    USE services\AdS\AdManager;
    USE services\SecS\SecurityManager;
    USE services\AccS\AccountManager;

    $accManager = new AccountManager();

    // if(isset($_POST['adspriv'])){
    //     $ads = 1;
    // }else{
    //     $ads = 0;
    // }


    // if(isset($_POST['kycpriv'])){
    //     $kyc = 1;
    // }else{
    //     $kyc = 0;
    // }

    $result = $accManager->new_admin_account($_POST['firstField'], $_POST['lastField'], $_POST['emailField'], $_POST['phoneField'], $_POST['passwordField'], $_POST['adspriv'], $_POST['kycpriv']);
    
    echo $result['status'];
