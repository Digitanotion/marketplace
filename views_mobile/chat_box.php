<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} else if (strpos($url, '192.168')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}

use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\AccS\AccountManager;
use services\MedS\MediaManager;
use services\AudS\AuditManager;
use services\MsgS\messagingManager;

$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$mediaManager_ob = new MediaManager();
$audService_ob = new AuditManager();
$message_ob = new messagingManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg = [];
/* if (!$securityManager_ob->is_user_auth__()) {
  header("location: " . MALL_ROOT . "Signin.php");
} */
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}
$pageUsrID__ = $_GET['user_mob_id__'];
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Messages</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="views/assets/css/personal-buiness.css">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="assets/css/messages.css">
      <script>
        var pageTitle=document.title;
            window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                window.flutter_inappwebview.callHandler('getPageTitles', 1, true, pageTitle)
            });
        </script>
</head>

<body class="bg-light-blue">
    <section class="container-fluid m-0 p-0">
        <div class="row justify-content-between w-100 m-0 p-0">
            <div class="col-12 p-0 w-100 rounded-3 ha-message__content">
                <?php
                $msgUsrLoggedReceiverSel = "";
                if (isset($_GET['queue'])) {
                    $sellerID = $_GET['seller'];
                    $getReceiverBizInfo_chat = $usrAccManager_ob->getUsrBizInfoByID($sellerID);
                    $msgAdName = "";
                    $msgAdAmount = "";
                    //$msgAdUserPhone=$getReceiverInfo['message']['mallUsrPhoneNo'];
                    //Get Ad Information where the chat was initiated
                    $getMsgAdInfo = $adManager_ob->getAdByID($_GET['ad']);
                    if ($getMsgAdInfo['status'] == 1) {
                        $msgAdInfo = $getMsgAdInfo['message'];
                        $msgAdName = $msgAdInfo['mallAdTitle'];
                        $msgAdAmount = $msgAdInfo['mallAdPrice'];
                    } else {
                        //Business not found
                        $msgAdName = "Ad**********";
                    }
                    //GET MESSAGE SENDER AND RECEIVER
                    $getMsgGrpInfoResponse = $message_ob->getMsgGroupInfoByMsgID($_GET['queue']);
                    if ($getMsgGrpInfoResponse['status'] == 1) {
                        $msgInfoDetails = $getMsgGrpInfoResponse['message'];
                        $msgAdID = $msgInfoDetails['mallAdID'];
                        $msgID = $msgInfoDetails['mallMsgID'];
                        $msgSenderID = $msgInfoDetails['mallMsgSenderID'];
                        $msgReceiverID = $msgInfoDetails['mallMsgReceiverID'];
                        //Check who is logged in, to know how to display the message user info
                        if ($msgReceiverID == $pageUsrID__) {
                            //Then Ad owner is currently logged in
                            $msgUsrLoggedReceiverSel = $msgSenderID;
                        } elseif ($msgSenderID == $pageUsrID__) {
                            $msgUsrLoggedReceiverSel = $msgReceiverID;
                        } elseif ($msgSenderID == $msgReceiverID) {

                            $msgUsrLoggedReceiverSel = $pageUsrID__;
                        }
                    }

                ?>

                    <div class="px-4 m-0 bg-white py-2 d-flex justify-content-between shadow-sm w-100">
                        <div class="d-flex w-100">
                            <div class=""><i class="fa fa-business-time fa-3x"></i></div>
                            <div class="" id="prof1">
                                <span class="fs-md"> <?php echo $msgAdName ?></span> <br>
                                <span class="fs-md fw-bold messagetext"><?php echo CURRENCY . " $msgAdAmount"; ?></span>
                            </div>
                        </div>

                        <!--  <div>
              <button class="w-100 border-0 p-2 bg-primary text-center phonenum" id="demo1" onclick="showPhoneNo('<?php echo $msgAdUserPhone ?>')"><i class="fa fa-phone"></i> SHOW CONTACT</button>
            </div> -->
                    </div>

                    <div class="bg-light-blue p-2 mainchat">
                        <input type="hidden" value="<?php echo $_GET['queue']; ?>" id="msgID">
                        <input type="hidden" value="<?php echo $pageUsrID__; ?>" id="currentUSR">
                        <div class="fs-md text-center mt-3 p-2 mx-auto border rounded-pill w-75"><i class="fa fa-triangle-exclamation fa-2x"></i> It's better to stay in Gaiginmall chat, we can't protect you in other messaging apps.</div>

                        <?php
                        $allMsgID = $_GET['queue'];
                        $allChats = $message_ob->getAllUserMsgsByMsgID($allMsgID);
                        if ($allChats['status'] == 1) {
                            $count = count($allChats['message']);
                            $current = 1;
                        ?>
                            <input type="hidden" value="<?php echo $count; ?>" id="lastCount">
                            <?php
                            foreach ($allChats['message'] as $chatsEach) {
                                if ($current < ($count)) {

                            ?>

                                    <div class="mt-3 fs-md text-end d-flex <?php echo ($chatsEach['mallMsgSenderID'] == $pageUsrID__) ? "justify-content-end" : "justify-content-start"; ?>">
                                        <div class="p-2 px-4 border-0  rounded-3 <?php echo ($chatsEach['mallMsgSenderID'] == $pageUsrID__) ? "bg-primary my-reply " : "bg-white user-reply "; ?>">
                                            <?php echo $chatsEach['mallMsgValue']; ?>
                                            <p class="m-0 p-0 fs-md">
                                                <?php echo date("d M, y. h:m", $chatsEach['mallMsgTime']);
                                                echo ($chatsEach['mallMsgSenderID'] == $pageUsrID__) ? "<i class='fa fa-check-double '></i> " : ""; ?>
                                            </p>
                                        </div>
                                    </div>
                        <?php }
                        $current++;
                            }
                        } ?>

                    </div>
                <?php
                }
                ?>
                <!-- Chat Box Footer (Fixed) -->
                <div class="px-3 py-2 bg-light-blue d-flex justify-content-between sender shadow-sm fixed-bottom">
                    <input type="hidden" value="<?php echo $msgUsrLoggedReceiverSel; ?>" id="msgRecieverUser">
                    <input type="hidden" value="<?php echo $pageUsrID__; ?>" id="msgSenderUser">
                    <input type="hidden" value="<?php echo $_GET['ad']; ?>" id="msgAdID">
                    <input type="hidden" value="<?php echo $_GET['queue']; ?>" id="msgID">
                    <textarea placeholder="Write your message here" id="msgContent" class="border-0 fs-md form-control inputWH" rows="2"></textarea>
                    <div><i class="fa fa-thumbtack fa-3x"></i></div>
                    <button class=" btn-mobile btn-sm button_click" id="sendMessageToUser"><i class="fa fa-paper-plane fa-2x messagetext"></i>
                    </button>
                </div>

            </div>

        </div>
    </section>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/settings.js"></script>
    <script src="assets/js/messages.js"></script>

</body>

</html>