
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
use services\AdS\AdManager;
use services\SecS\SecurityManager;
use services\AccS\AccountManager;

$securityManager_ob = new SecurityManager();
$adManager = new AdManager();
$usrAccManager_ob=new AccountManager();
if (!isset($_SESSION["gaijinmall_user_"])){
    header("location: Signin.php");
}
//$mallPrefetchForms=array();
$sys_msg = [];
$adManagerVal = $adManager->getAllMallParentCategory();
$categChild = $adManager->getCategChildByID(12345);
$usrIDData = isset($_SESSION["_paydirect_"]) ? : header("location: ./");
?>

<h1 style="text-align: center;">Please wait...</h1>
<script>
    setTimeout(function (){
        window.location.href=""
    }, 3000)
</script>