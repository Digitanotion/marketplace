<?php

        include_once './accountManager.php';

        //get the user details
        $firstField = $_POST['firstField'];
        $lastField = $_POST['lastField'];
        $emailField = $_POST['emailField'];
        $phoneField = $_POST['phoneField'];
        $passwordField = $_POST['passwordField'];


        $newAccount = new AccountManager;
        $response = $newAccount->Signup($firstField, $lastField, $emailField, $phoneField, $passwordField);

        sleep(2);
        echo $response;
