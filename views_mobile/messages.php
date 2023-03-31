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
if (!$securityManager_ob->is_user_auth__()) {
  header("location: " . MALL_ROOT . "Signin.php");
}
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
$getUsrBizInfo = "";
if (isset($_GET['view_business'])) {
  $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
} else {
  $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Messages</title>
  <meta name="theme-color" content="#c3e6ff">
  <link rel="shortcut icon" href="./assets/images/favicon.png">
  <link rel="stylesheet" href="views/assets/css/personal-buiness.css" />
  <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />
  <link rel="stylesheet" href="./assets/fonts/inter/style.css" />
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/seller.css" />
  <link rel="stylesheet" href="./assets/css/vertical-menu.css" />
  <link rel="stylesheet" href="./assets/css/adverts.css" />
  <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css" />
  <link rel="stylesheet" href="assets/css/messages.css" />
</head>

<body>
  <section class="container-fluid m-0">
    <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 gx-0 gx-md-5 gx-lg-5 justify-content-between">
      <div class="col-md-4 col-lg-4 col-sm-12 shadow-sm  rounded-3 p-0  ha-message__contents">


        <div class="w-100 pt-1">
          <?php
          $getMessagesResponse = $message_ob->getAllUserMsgGroup($pageUsrID__);
          $msgUsrLoggedReceiver = "";
          if ($getMessagesResponse['status'] == 1) {
            foreach ($getMessagesResponse['message'] as $messageEach) {
              $msgAdID = $messageEach['mallAdID'];
              $msgID = $messageEach['mallMsgID'];
              $msgSenderID = $messageEach['mallMsgSenderID'];
              $msgReceiverID = $messageEach['mallMsgReceiverID'];
              //Check who is logged in, to know how to display the message user info
              if ($msgReceiverID == $pageUsrID__) {
                //Then Ad owner is currently logged in
                $msgUsrLoggedReceiver = $msgSenderID;
              } elseif ($msgSenderID == $pageUsrID__) {
                $msgUsrLoggedReceiver = $msgReceiverID;
              } elseif ($msgSenderID == $msgReceiverID) {

                $msgUsrLoggedReceiver = $pageUsrID__;
              }

              //Get User information of reciever
              $getReceiverInfo = $usrAccManager_ob->getUsrBasicInfoByID($msgUsrLoggedReceiver);
              $msgRecieverName = "";
              $msgBizName = "";
              $msgAdName = "";
              $msgAdAmount = "";
              ///$msgAdUserPhone=$getReceiverInfo['message']['mallUsrPhoneNo'];
              $msgInitTime = date("d/m/y", $messageEach['mallMsgStartTime']);
              if ($getReceiverInfo['status'] == 1) {
                $msgRecieverName = $getReceiverInfo['message'];
                $msgRecieverName = $msgRecieverName['mallUsrFirstName'] . $msgRecieverName['mallUsrLastName'];
              } else {
                $msgRecieverName = "Us************";
              }

              //Get Business Info of Business Owner
              $getReceiverBizInfo = $usrAccManager_ob->getUsrBizInfoByID($msgUsrLoggedReceiver);
              if ($getReceiverBizInfo['status'] == 1) {
                $msgBizName = $getReceiverBizInfo['message'];
                $msgBizName = $msgBizName['mallBizName'];
              } else {
                $msgBizName = "Biz**********";
              }

              //Get Ad Information where the chat was initiated
              $getMsgAdInfo = $adManager_ob->getAdByID($msgAdID);
              if ($getMsgAdInfo['status'] == 1) {
                $msgAdInfo = $getMsgAdInfo['message'];
                $msgAdName = $msgAdInfo['mallAdTitle'];
                $msgAdAmount = $msgAdInfo['mallAdPrice'];
              } else {
                //Business not found
                $msgAdName = "Ad**********";
              }

          ?>
              <div class="px-3 pb-3 my-1 bg-light-blue">
                <a href="all_chats.php?queue=<?php echo $msgID ?>&seller=<?php echo $msgReceiverID; ?>&ad=<?php echo $msgAdID; ?>" class="text-dark">
                  <div class="border-0 text-start mainsection" style="position: relative;">
                    <div class="position-absolute mt-2">
                      <div class="ha-image-profile-small__holder mx-auto my-auto bg-purple rounded-circle" style="">
                        <?php echo substr($msgBizName, 0, 1); ?>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="fs-md mt-3 digi"><?php echo $msgBizName; ?></span>
                      <span class="fs-md mt-3"><?php echo $msgInitTime; ?></span>
                    </div>
                    <div class=" fs-6 machine">
                      <strong><?php echo $msgAdName; ?></strong>
                    </div>
                    <!-- <div class=" fs-md reply">You: Okay I hear you.</div> -->
                  </div>
                </a>
              </div>
            <?php }
          } else {
            ?>
            <div class="px-3 bg-white text-center pb-3 my-1" style="">
              <img class="img-fluid w-50 mt-5" src="assets/images/bg-19.png">
              <div class="text-center fs-title-1 mt-2">
                No chat yet
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0 ha-message__contents">
        <hr class="m-5 bg-light" />
        <div class="ha-profile-url-data__body">
          <div class="ha-none__display w-50 text-center mx-auto mt-0">
            <div class="">
              <img src="assets/images/messaging.svg" class="messageim w-75" alt="" />
            </div>
          </div>
          <div class="text-center fs-md mt-2">
            Select a chat to view conversation
          </div>
        </div>
        <hr class="m-5 bg-light" />
        <hr class="m-5 bg-light" />
        <hr class="m-5 bg-light" />
        <hr class="m-5 bg-light" />
      </div>
    </div>
  </section>
  <?php include "footer.php"; ?>
  <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
  <script src="./assets/js/vertical-menu.js"></script>
  <script src="./assets/js/adverts.js"></script>
  <script src="./assets/js/settings.js"></script>
  <script src="./assets/js/personalbusiness.js"></script>
  <script src="./assets/js/postads.js"></script>
</body>

</html>