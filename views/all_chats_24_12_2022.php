<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
  require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
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
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Chats | Gaijinmall</title>
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
</head>

<body>
  <?php include "header-top.php"; ?>
  <section class="container-fluid m-0 p-0 sectiona">
    <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
      <div class="col-md-4 col-lg-4 col-sm-12 shadow-sm bg-white rounded-3 p-0  ha-message__contents">
        <div class="px-3 ha-profile__section mb-4">
          <div class="mx-auto pt-4 d-flex justify-content-between">
            <span class="fs-title-2 fw-bolder messagetext">My messages</span>
            <!-- <div class="dropdown">
                            <button class="dropdown-toggle mb-2 bg-white border-0" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bars fa-3x"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton2">
                                <li><a class="dropdown-item " href="#">Archived chats</a></li>
                                <li><a class="dropdown-item" href="#">Spam chats</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Add to archive</a></li>
                            </ul>
                        </div> -->
          </div>
        </div>

        <div class="bg-light-blue w-100 pt-1 add-scroll-y">
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
              <div class="px-3 pb-3 my-1 bg-white">
                <a href="all_chats.php?queue=<?php echo $msgID ?>&seller=<?php echo $msgReceiverID; ?>&ad=<?php echo $msgAdID; ?>" class="text-dark">
                  <div class="border-0 text-start bg-white mainsection" style="position: relative;">
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
      <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 ps-1 mb-5 ha-message__contents">
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
          <div class="px-4 py-3 d-flex justify-content-between">
            <div class="ha-image-profile-smaller__holder bg-purple rounded-circle " style="">
              <?php echo ($getReceiverBizInfo_chat['status'] == 1) ? $getReceiverBizInfo_chat['message']['mallBizName'][0] : "..."; ?>
            </div>
            <div class="">
              <span class="fs-title-3 fw-bolder messagetext" id="prof1"><?php echo ($getReceiverBizInfo_chat['status'] == 1) ? $getReceiverBizInfo_chat['message']['mallBizName'] : "N/A"; ?></span>
            </div>
            <div>
              &nbsp;
              <!-- <button class="text-center bg-white flagicon"><i class="fa fa-flag fa-2x messagetext"></i></button>
                        <i class="fa fa-ellipsis-vertical fa-2x ms-5 messagetext"></i> -->
            </div>
            <div>
              &nbsp;
            </div>
          </div>
          <hr class="m-0 bg-hr-light">
          <div class="px-4 py-2 d-flex justify-content-between">
            <div class="d-flex">
              <div class=""><i class="fa fa-business-time fa-3x"></i></div>
              <div class="" id="prof1">
                <span class="fs-md"> <?php echo $msgAdName ?></span> <br>
                <span class="fs-md messagetext"><?php echo CURRENCY . " $msgAdAmount"; ?></span>
              </div>
            </div>

            <div>
              <button class="w-100 border-0 p-2 bg-primary text-center phonenum" id="demo1" onclick="showPhoneNo('<?php echo $msgAdUserPhone ?>')"><i class="fa fa-phone"></i> SHOW CONTACT</button>
            </div>
          </div>

          <div class="bg-light-blue p-2 mainchat">
            <input type="hidden" value="<?php echo $_GET['queue']; ?>" id="msgID">
            <div class="fs-md text-center mt-3 p-2 mx-auto border rounded-pill w-75"><i class="fa fa-triangle-exclamation fa-2x"></i> It's better to stay in Gaigimall chat, we can't protect you in other messaging apps.</div>

            <?php
            $allMsgID = $_GET['queue'];
            $allChats = $message_ob->getAllUserMsgsByMsgID($allMsgID);
            if ($allChats['status'] == 1) {
              $count = count($allChats['message']);
            ?>
              <input type="hidden" value="<?php echo $count; ?>" id="lastCount">
              <?php
              foreach ($allChats['message'] as $chatsEach) {
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
            } ?>

          </div>
        <?php
        }
        ?>
        <!-- Chat Box Footer (Fixed) -->
        <div class="px-3 py-2 d-flex justify-content-between sender">
          <input type="hidden" value="<?php echo $msgUsrLoggedReceiverSel; ?>" id="msgRecieverUser">
          <input type="hidden" value="<?php echo $pageUsrID__; ?>" id="msgSenderUser">
          <input type="hidden" value="<?php echo $_GET['ad']; ?>" id="msgAdID">
          <input type="hidden" value="<?php echo $_GET['queue']; ?>" id="msgID">
          <textarea placeholder="Write your message here" id="msgContent" class="border-0 fs-md form-control inputWH" rows="2"></textarea>
          <div><i class="fa fa-thumbtack fa-3x"></i></div>
          <button class="bg-transparent btn btn-sm" id="sendMessageToUser"><i class="fa fa-paper-plane fa-2x messagetext"></i>
          </button>
        </div>

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
  <script src="assets/js/messages.js"></script>

</body>

</html>