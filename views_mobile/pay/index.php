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
USE services\SecS\SecurityManager;
USE services\AdS\AdManager;
USE services\AccS\AccountManager;
$securityManager_ob=new SecurityManager();
$adsManager_ob=new AdManager();
$usrAccManager_ob=new AccountManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg=[];
if (!$securityManager_ob->is_user_auth__()){
    header("location: ".MALL_ROOT."/Signin.php");
}
if (isset($_GET['logout'])&&$_GET['logout']==1){
    if ($securityManager_ob->endUserSession()){
        header("location: ".MALL_ROOT);
    }
    else{
        $sys_msg['msg_type']=500;
        $sys_msg['msg']="Could not log out";
    }
} 
if (isset($_GET['psi'])&&isset($_GET['lk_tok'])&&$_GET['psi']!=""){
  $verifyPromoPay=$adsManager_ob->verifyPaySession($_GET['psi']); 
  if ($verifyPromoPay->payment_status=="paid"){
    $addNewUsrPromo=$adsManager_ob->addNewUsrPromoRecord($verifyPromoPay->client_reference_id,$_GET['psi']);
    echo '<script>setTimeout(function (e) {
      window.location="./../adverts.php"
  },10000);
  </script>';
  }
  else{
    header("location: ./../");
  }
}
else{
  header("location: ./../");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Adverts | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./../assets/images/favicon.png">
    <link rel="stylesheet" href="../../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./../assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/seller.css">
    <link rel="stylesheet" href="./../assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./../assets/css/adverts.css">
</head>
<body>
  <section class="container">
    <div class="row" style="margin-left:auto;margin-right:auto;margin-top: 40vh;">
      <div class="col">
      <div class="alert bg-light-blue text-center text-white " style="background-color:rgba(0,181,226, 0.9)">
        <div class="fs-6"><i class="fa fa-check-circle fs-3"></i></div>
        <div class="fs-6">
        Congratulations! Transaction is successful, we will redirect you back to your profile. <br> Have any question?
          <a href="mailto:sales@gaijinmall.com">sales@gaijinmall.com</a>.
        </div>
     
      </div>
      </div>
      
    </div>
    <p>
     
    </p>
  </section>
</body>
</html>