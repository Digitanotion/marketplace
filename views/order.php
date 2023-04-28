<?php
error_reporting(0);
ob_start();
//Confirm if file is local or Public and add the right path
session_start();
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once __DIR__ . '\../vendor/autoload.php';
} elseif (strpos($url, 'gaijinmall')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
} elseif (strpos($url, '192.168')) {
    require_once __DIR__ . '\../vendor/autoload.php';
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
}

use services\AdS\AdManager;
use services\AudS\AuditManager;
use services\MedS\MediaManager;
// use services\InitDB;
use services\AccS\AccountManager;
use services\MsgS\feedbackManager;
use services\SecS\SecurityManager;
use services\MsgS\messagingManager;

$url =
    (isset($_SERVER['HTTPS']) ? 'https' : 'http') .
    "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$mediaManager = new MediaManager();
$audService_ob = new AuditManager();
$messaging_ob = new messagingManager();
$feedback_ob = new feedbackManager();

/* if (!$securityManager_ob->is_user_auth__()){
  header("location: Signin.php");
} */
$adID = '';
$pageUsrID__ = $_SESSION['gaijinmall_user_'];

if (!empty($pageUsrID__)) {
    $getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__);
}

/*

$getCurrentUserInfo=$securityManager_ob->getUserInfoByID($usrID);
$getUsrInfo="";
if ($getUsrInfo['status']!=1){
  header("location: Signin.php");
}
else{
  $getUsrInfo=$getUsrInfo['message'];
}*/
$sys_msg = [];
$allAdByID = [];
$getUsrInfo = '';
$getUsrBizInfo = '';
if (isset($_POST['reportAd__btn'])) {
    $reportAdResponse = $feedback_ob->reportAd(
        $pageUsrID__,
        $_POST['reportAd__Select'],
        $_POST['reportAdMsg__txt']
    );
    $sys_msg['msg_type'] = $reportAdResponse['status'];
    $sys_msg['msg'] = $reportAdResponse['message'];
}
if (isset($_GET['adID'])) {
    $adID = $securityManager_ob->sanitizeItem($_GET['adID'], 'string');
    $allAdByID = $adManager_ob->getAdByID($adID);
    if ($allAdByID['status'] == '1') {
        $allAdByID = $allAdByID['message'];
        $getUsrInfo = $securityManager_ob->getUserInfoByID(
            $allAdByID['mallUsrID']
        );
        $getUsrInfo = $getUsrInfo['message'];
    } else {
        header('Location: ./');
    }
}

//SET AD VIEW
$adManager_ob->updateAdView($adID);
$pageUsrID__ = isset($_SESSION['gaijinmall_user_'])
    ? $_SESSION['gaijinmall_user_']
    : 'null';

//Get image for favicon
$favImageName = $mediaManager->getThumbImage($allAdByID['mallAdID']);
if ($favImageName['status'] == 1) {
    $favImageName = $favImageName['message']['mallMediaName'];
} else {
    $favImageName = '';
}

//Generate new CSRF TOken
$newToken = $securityManager_ob->setCSRF();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $allAdByID['mallAdTitle']; ?></title>
  <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <link rel="stylesheet" href="./assets/fonts/inter/style.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="../dependencies/node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/checkout.css">
  <link rel="stylesheet" href="./assets/css/translate.css">
  <link rel="stylesheet" href="./assets/css/vertical-menu.css">
  <link rel="stylesheet" href="assets/css/cute-alert.css">
  <link rel="icon" href="../handlers/uploads/thumbs/<?php echo $favImageName; ?>">
  <meta name="Description" content="<?php echo $allAdByID['mallAdDesc']; ?>">
  <meta name="theme-color" content="#c3e6ff">
  <meta property="og:title" content="<?php echo $allAdByID['mallAdTitle']; ?>">
  <meta property="og:description" content="<?php echo $allAdByID[
      'mallAdDesc'
  ]; ?>">
  <meta property="og:image" content="https://gaijinmall.com/handlers/uploads/thumbs/<?php echo $favImageName; ?>">
  <meta property="og:image:width" content="400" />
  <meta property="og:image:height" content="300" />
  <meta property="og:type" content="Classified Store" />
  <meta property="og:url" content="product.php?adID=<?php echo $adID; ?>" />
  <meta property="og:site_name" content="Gaijinmall" />

  <style>
       /* search box  */
@media screen and (min-width: 100px) {
  .input__div {
    padding-bottom: 15px;
    margin-right:-25px;
  }
  #input {
    border: 1.5px solid #6eeb83;
    font-size:13px;
    padding: 10px;
    background-color: #f3faff;
  }

  input::placeholder {
    font-size: 13px;
    padding-left: 0px;
  }
   .Order__img{
    height: 90px;
    width: 180px;
  }

  .Orders__open-order:hover{
      background-color: #0D6EFD;
      color:white !important;
      width:100%;
      border-radius:5px;
       border-top-right-radius:0px !important;
      border-top-left-radius:5px !important;

      border-bottom-left-radius: 5px !important;
     border-bottom-right-radius: 0px !important;
    border-radius:5px;
     /* border-bottom-left-radius: 0px;
     border-bottom-right-radius: 0px; */
}
.Orders__open-order:hover>button{
    color:white !important;
    border-radius:5px;
     border-top-right-radius:0px !important;
      border-top-left-radius:5px !important;

      border-bottom-left-radius: 5px !important;
     border-bottom-right-radius: 0px !important;
    border-radius:5px;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.Orders__open-order>button{
/* background-color: #0D6EFD;
      color:white !important; */
      /* border-bottom-left-radius: 0px !important;
     border-bottom-right-radius: 0px !important; */
  padding:7px 17px;
  width:150px;
    /* color:black !important; */
    background-color:transparent;
    border:unset;
    border-radius:5px;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.Orders__closed-order:hover{
    background-color: #0D6EFD;
    color:white !important;
    width:100%;
    border-radius:5px;
    border-top-left-radius: 0px !important;
      border-bottom-left-radius:0px !important;
      border-radius:5px;
      border-top-right-radius: 5px !important;
      border-bottom-left-radius:0px !important;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.Orders__closed-order:hover>button{
    color:white !important;
    border-radius:5px;
    border-top-left-radius: 0px !important;
      border-bottom-left-radius:0px !important;
      border-radius:5px;
      border-top-right-radius: 5px !important;
      border-bottom-left-radius:0px !important;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.Orders__closed-order:active>button{
    color:white !important;
    border-radius:5px;
    border-top-left-radius: 0px !important;
      border-bottom-left-radius:0px !important;
      border-radius:5px;
      border-top-right-radius: 5px !important;
      border-bottom-left-radius:0px !important;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.Orders__closed-order>button{
  padding:7px 17px;
  width:150px;
    background-color:transparent;
    border:unset;
    border-radius:5px;
    /* border-top-right-radius: 5px !important;
    border-top-left-radius: 0px; */
}
.active-open{
  background-color: #0D6EFD !important;
      color:white !important;
      padding:7px 17px !important;
      /* width:100%; */
      /* color:black !important; */
      /* background-color:transparent; */
      border:unset;
      border-top-right-radius:0px !important;
      border-top-left-radius:5px !important;

      border-bottom-left-radius: 5px !important;
     border-bottom-right-radius: 0px !important;
    border-radius:5px;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.active-close{
  background-color: #0D6EFD !important;
      color:white !important;
      padding:7px 17px;
      width:100%;
      /* color:black !important; */
      /* background-color:transparent; */
      border:unset;
      border-top-left-radius: 0px !important;
      border-bottom-left-radius:0px !important;
      border-radius:5px;
      border-top-right-radius: 5px !important;
      border-bottom-left-radius:0px !important;
      /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}
.Order-card{
  border-radius:5px;width:300px;background-color:white;height:6.5vh;box-shadow:0px 0px 10px #d9d9d9;font-size:14px;font-weight:bolder;margin-bottom:30px;
}

.openOrderChild{
border-radius:5px;width:105%;background-color:white;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;margin-left:0px;padding:15px
}
.closeOrderChild{
  border-radius:5px;width:105%;background-color:white;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;margin-left:0px;padding:15px;
}

.openOrderChild-card{
border:1px solid gray;margin:10px;border-radius:5px;padding:10px 20px;box-shadow:0px 0px 10px #ececec;
}

.closeOrderChild-card{
  border:1px solid gray;margin:10px;border-radius:5px;padding:10px 20px;box-shadow:0px 0px 10px #ececec;
}
}
@media screen and (min-width: 576px) {


}
@media screen and (min-width: 768px) {
    .input__div {
    margin-right: 40px;
  }
}

@media screen and (min-width: 992px) {
  .Orders__open-order:hover{
    background-color: #0D6EFD;
    color:white !important;
    width:100%;
    border-radius:5px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
}
.Orders__open-order:hover>button{
    color:white !important;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__open-order>button{
/* background-color: #0D6EFD;
      color:white !important; */
      /* border-bottom-left-radius: 0px !important;
     border-bottom-right-radius: 0px !important; */
  padding:7px 17px;
  /* width:100%; */
  width:150px;
    /* color:black !important; */
    background-color:transparent;
    border:unset;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order:hover{
    background-color: #0D6EFD;
    color:white !important;
    width:100%;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order:hover>button{
    color:white !important;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order:active>button{
    color:white !important;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order>button{
  padding:7px 17px;
  /* width:100%; */
  width:150px;
    background-color:transparent;
    border:unset;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}
.active-open{
  background-color: #0D6EFD !important;
      color:white !important;
      padding:7px 17px !important;
      /* width:100%; */
      /* color:black !important; */
      /* background-color:transparent; */
      border:unset;
      border-top-right-radius:0px !important;
      border-top-left-radius:5px !important;

      border-bottom-left-radius: 5px !important;
     border-bottom-right-radius: 0px !important;
    border-radius:5px;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.active-close{
  background-color: #0D6EFD !important;
      color:white !important;
      padding:7px 17px;
      /* width:100%; */
      /* color:black !important; */
      /* background-color:transparent; */
      border:unset;
      border-top-left-radius: 0px !important;
      border-bottom-left-radius:0px !important;
      border-radius:5px;
      border-top-right-radius: 5px !important;
      border-bottom-left-radius:0px !important;
      /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}
.Order-card{
 border-radius:5px;width:300px;background-color:white;height:6.5vh;box-shadow:0px 0px 10px #d9d9d9;font-size:14px;font-weight:bolder;margin-bottom:30px;
}

.openOrderChild{
border-radius:5px;width:800px;background-color:white;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;margin-left:50px;padding:15px
}
.closeOrderChild{
  border-radius:5px;width:800px;background-color:white;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;margin-left:50px;padding:15px;
}

.openOrderChild-card{
border:1px solid gray;margin:10px;border-radius:5px;padding:10px 50px;box-shadow:0px 0px 10px #ececec;
}

.closeOrderChild-card{
  border:1px solid gray;margin:10px;border-radius:5px;padding:10px 50px;box-shadow:0px 0px 10px #ececec;
}
}

@media screen and (min-width: 1200px) {
  .Order__img{
    height: 120px ;
    width: 140px !important;
  }
  .input__div {
    margin-right: 0px;
  }
  #input {
    border: 1.5px solid #6eeb83;
    padding: 15px;
    padding-right:20px;
    font-size:13px;
    background-color: #f3faff;
  }

  input::placeholder {
    font-size: 13px;
    padding-left: 0px;
  }

 .Orders__open-order {
  border-bottom:1px solid #777 !important;

  }
.Orders__open-order:hover{
      background-color: #0D6EFD;
      color:white !important;
      width:100%;
      border-radius:5px;
     border-bottom-left-radius: 0px;
     border-bottom-right-radius: 0px;
}
.Orders__open-order:hover>button{
    color:white !important;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__open-order>button{
/* background-color: #0D6EFD;
      color:white !important; */
      /* border-bottom-left-radius: 0px !important;
     border-bottom-right-radius: 0px !important; */
  padding:7px 17px;
  width:100%;
    /* color:black !important; */
    background-color:transparent;
    border:unset;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order:hover{
    background-color: #0D6EFD;
    color:white !important;
    width:100%;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order:hover>button{
    color:white !important;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order:active>button{
    color:white !important;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}

.Orders__closed-order>button{
  padding:7px 17px;
  width:100%;
    background-color:transparent;
    border:unset;
    border-radius:5px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
}
.active-open{
  background-color: #0D6EFD !important;
      color:white !important;
      padding:7px 17px;
      width:100%;
      /* color:black !important; */
      /* background-color:transparent; */
      border:unset;
      border-top-right-radius:5px !important;
      border-top-left-radius:5px !important;

      border-bottom-left-radius: 0px !important;
     border-bottom-right-radius: 0px !important;
    border-radius:5px;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}

.active-close{
  background-color: #0D6EFD !important;
      color:white !important;
      padding:7px 17px;
      width:100%;
      /* color:black !important; */
      /* background-color:transparent; */
      border:unset;
     border-top-right-radius: 0px !important;
    border-top-left-radius: 0px !important;
    border-radius:5px;
    /* border-top-right-radius: 0px;
    border-top-left-radius: 0px; */
}
.Order-card{
  border-radius:5px;width:17%;background-color:white;height:13.5vh;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;font-weight:bolder
}

.openOrderChild{
border-radius:5px;width:800px;background-color:white;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;margin-left:50px;padding:15px
}
.closeOrderChild{
  border-radius:5px;width:800px;background-color:white;box-shadow:0px 0px 10px #d9d9d9;font-size:18px;margin-left:50px;padding:15px;
}

.openOrderChild-card{
border:1px solid gray;margin:10px;border-radius:5px;padding:10px 50px;box-shadow:0px 0px 10px #ececec;
}

.closeOrderChild-card{
  border:1px solid gray;margin:10px;border-radius:5px;padding:10px 50px;box-shadow:0px 0px 10px #ececec;
}

}

.closeOrderChild{
  display:none
} 


  </style>
</head>

<body class="bg-light-blue">
    <?php include 'header-top.php'; ?>

      <main class="checkout__main">
      <!-- Search input   -->
      <section>
        <div>
          <div class="text-end input__div">
            <input
              type="text"
              name="search"
              id="input"
              placeholder="Search by product"
            />
          </div>
        </div>
      </section>

      <section>
        <div>
          <p class="cart__header" style="margin-bottom:0px">Orders</p>
        </div>
      </section>

      <section>
        <hr style="margin-top:5px;">
        <div class="d-flex flex-wrap">
            <div class="d-flex flex-xl-column justify-content-start mx-lg-5 Order-card">
                <div class="Orders__open-order" style="font-weight:bolder" >
                   <button style="font-weight:bolder" class="active-open">
                        Open Orders <span style="font-size:13px">(1)</span>
                   </button> 
                </div>
                <!-- <hr> -->
                <div class="Orders__closed-order">
                    <button style="font-weight:bolder">
                        Closed Orders
                    </button>
                </div>
            </div>
            <!-- open order -->
            <div class="orders">
            <div class="openOrderChild">
            <div>
                <div class="d-flex flex-wrap justify-content-between align-items-start openOrderChild-card">
                    <div>
                  <div>
                    <div class="d-flex flex-wrap align-items-start my-3">
                      <div class="my-3 my-lg-0">
                        <img
                          src="https://unsplash.it/600/400"
                          class="img-fluid Order__img"
                        />
                      </div>
                      <div class="mx-sm-3">
                        <div>
                          <p style="font-size:19px;font-weight:bold;margin-bottom:0px">Open Laptop</p>
                        </div>
                        <div class="">
                          <div style="color:#9e9e9e">
                            <p style="margin-bottom: 0px;font-size:16px;font-weight:none">Order
                              <span>
                                396613585
                              </span>
                            </p>
                          </div>
                          <div class="mt-3">
                            <span class="badge bg-primary">
                              Delivered
                            <!-- <i class="fa fa-check-circle p-0 mx-1"></i> -->
                            </span>
                          </div>
                          <div>
                            <p style="margin-top: 3px;font-size:16px;font-weight:bold">On <span>16-04-2024</span>
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <hr /> -->
                  </div>
                </div>
                <div>
                    <a href="">
                        <p class="text-primary" style="font-weight:bold;font-size:13px;">
                            SEE DETAILS
                        </p>
                    </a>
                </div>
            </div>
            </div>
        </div>
        <!--  -->
        <!-- closed order  -->
        <div class="closeOrderChild" style="">
            <div>
                <div class="d-flex flex-wrap justify-content-between align-items-start closeOrderChild-card">
                    <div>
                  <div>
                    <div class="d-flex flex-wrap align-items-start my-3">
                      <div class="my-3 my-lg-0">
                        <img
                          src="https://unsplash.it/600/400"
                          class="img-fluid Order__img"
                        />
                      </div>
                      <div class="mx-lg-3">
                        <div>
                          <p style="font-size:19px;font-weight:bold;margin-bottom:0px">Closed Order</p>
                        </div>
                        <div class="">
                          <div style="color:#9e9e9e">
                            <p style="margin-bottom: 0px;font-size:16px;font-weight:none">Order
                              <span>
                                396613585
                              </span>
                            </p>
                          </div>
                          <div class="mt-3">
                            <span class="badge bg-primary">
                             Cancelled
                            <!-- <i class="fa fa-check-circle p-0 mx-1"></i> -->
                            </span>
                          </div>
                          <div>
                            <p style="margin-top: 3px;font-size:16px;font-weight:bold">On <span>16-04-2024</span>
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <hr /> -->
                  </div>
                </div>
                <div>
                    <a href="">
                        <p class="text-primary" style="font-weight:bold;font-size:13px;">
                            SEE DETAILS
                        </p>
                    </a>
                </div>
            </div>
            </div>
        </div>
      </section>
    </main>
    
     <?php include 'footer.php'; ?>

<script>

// Get the buttons
const openOrdersBtn = document.querySelector('.Orders__open-order button');
const closedOrdersBtn = document.querySelector('.Orders__closed-order button');

// Get the order sections
const openOrdersSection = document.querySelector('.openOrderChild');
const closedOrdersSection = document.querySelector('.closeOrderChild');

// Add click event listeners to the buttons
openOrdersBtn.addEventListener('click', () => {
  // Add the active class to the open orders button
  openOrdersBtn.classList.add('active-open');
  // Remove the active class from the closed orders button
  closedOrdersBtn.classList.remove('active-close');
  // Show the open orders section
  openOrdersSection.style.display = 'block';
  // Hide the closed orders section
  closedOrdersSection.style.display = 'none';
});

closedOrdersBtn.addEventListener('click', () => {
  // Add the active class to the closed orders button
  closedOrdersBtn.classList.add('active-close');
  // Remove the active class from the open orders button
  openOrdersBtn.classList.remove('active-open');
  // Show the closed orders section
  closedOrdersSection.style.display = 'block';
  // Hide the open orders section
  openOrdersSection.style.display = 'none';
});

</script>
</body>