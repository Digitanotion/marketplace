<?php

        include_once './accountManager.php';


        $emailField = $_POST['emailField'];
        $password = $_POST['password'];


        $newAccount = new AccountManager;
        $response = $newAccount->Signin($emailField, $password);  //returns the userID on success or 0 on failed login and -1 on deleted account


        sleep(2);

        if( is_string($response)){
            echo 1;
            exit;

        }else if($response == 0){
            echo 0;
            exit;

        }else if($response == -1){
            echo -1;
            exit;

        }