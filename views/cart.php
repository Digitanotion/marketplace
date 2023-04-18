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
    <link rel="stylesheet" href="./assets/css/cart.css">
  <link rel="stylesheet" href="./assets/css/style.css">
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
      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
      box-shadow: none;
      border:0; outline:none
    }

    /* search box  */
@media screen and (min-width: 100px) {
    .input__div {
    /* padding-top: 100px; */
    margin-left: 30px;
    padding-bottom: 15px;
    margin-right:15px;
  }
  #input {
    border: 1.5px solid #6eeb83;
    font-size:13px;
    /* border: 1.5px solid #198754; */
    padding: 10px;
    /* width: 100%; */
    background-color: #f3faff;
    /* height: 60px; */
  }

  input::placeholder {
    font-size: 13px;
    padding-left: 0px;
  }
}
@media screen and (min-width: 576px) {
}
@media screen and (min-width: 768px) {
    .input__div {
    /* padding-top: 100px; */
    margin-right: 80px;
  }
}
@media screen and (min-width: 1200px) {
  .input__div {
    /* padding-top: 100px; */
    margin-right: 40px;
  }
  #input {
    border: 1.5px solid #6eeb83;
    /* border: 1.5px solid #198754; */
    padding: 15px;
    padding-right:20px;
    font-size:13px;
    /* width: 100%; */
    background-color: #f3faff;
    /* height: 60px; */
  }

  input::placeholder {
    font-size: 13px;
    padding-left: 0px;
  }
}
  </style>
</head>

<body class="bg-light-blue">
    <?php include 'header-top.php'; ?>
<section>
  <section>
        <div>
          <div class="text-end input__div">
            <input
              type="text"
              name="search"
              id="input"
              placeholder="Search by product"
            />

            <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
          </div>
        </div>
      </section>
    <div class="cart">
          <div>
            <p class="cart__header">Cart</p>
          </div>
          <div class="d-flex justify-content-between flex-wrap">
            <div>
              <div
                class="d-flex justify-content-between flex-wrap cart__checkout"
              >
                <!-- <div> -->
                <p class="cart__checkout-paragraph">
                  The Product has been added to your cart.
                </p>
                <!-- </div> -->
                <div class="cart__checkout-link">
                  <a href="#">Checkout</a>
                </div>
              </div>
              <div id="cartItemOne">
                <div
                  class="d-flex flex-wrap justify-content-between cart__item"               
                >
                  <div>
                    <img
                      src="https://unsplash.it/600/400"
                      alt="cart-img"
                      class="img-fluid cart__placeholder-img"
                    />
                  </div>
                  <div class="cart__item-header mx-3 mx-md-0">
                    <p>Gold</p>
                  </div>
                  <div class="text-center order-3 order-sm-0">
                    <p class="cart__item-quantity" style="font-weight:bold">Quantity</p>
                    <div class="my-2">
                      <button id="increaseButton" class="increaseBtn bg-primary">+</button>
                      <input
                        type="number"
                        value="1"
                        id="cartItemQuantity"
                        readonly
                        class="quantityInput mx-3 text-center"
                      />
                      <button id="decreaseButton" class="decreaseBtn bg-primary">-</button>
                    </div>
                  </div>
                  <div class="order-4 order-sm-0">
                    <p class=" cart__item-quantity" style="font-weight:bold">Price</p>
                    <p class="my-2 cart__item-price">Y3,000</p>
                  </div>
                  <div>
                    <button
                      type="button"
                      class="cart__item-button order-2 order-sm-0"
                      id="deleteDiv"
                      style=""
                    >
                      Delete
                    </button>
                  </div>
                </div>
                <hr>
              </div>
            </div>
            <div>
              <div>
                <div class="cart__product">
                  <p>Add product to your Cart</p>
                </div>
                <div>
                  <div class="AddToCart">
                    <div class="d-flex align-items-start my-3">
                      <div>
                        <img
                          src="https://unsplash.it/600/400"
                          class="img-fluid AddToCart__img"
                        />
                      </div>
                      <div class="mx-3">
                        <div>
                          <p class="AddToCart__header">Laptop</p>
                        </div>
                        <div class="my-2">
                          <div style="">
                            <p  class="AddToCart__header-price" style="margin-bottom: 0px">Y5000</p>
                          </div>
                          <div class="my-2">
                            <button type="button" class="buttonDanger">
                              View Product
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr />
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
</section>
    
     <?php include 'footer.php'; ?>

     <script>
      const deleteButton = document.getElementById("deleteDiv");
      const cartItemOne = document.getElementById("cartItemOne");

      deleteButton.addEventListener("click", () => {
        cartItemOne.remove();
      });

      const cartItemQuantity = document.getElementById("cartItemQuantity");
      const increaseButton = document.getElementById("increaseButton");

      increaseButton.addEventListener("click", (value) => {
        cartItemQuantity.value++;
      });

      const decreaseButton = document.getElementById("decreaseButton");
      decreaseButton.addEventListener("click", (value) => {
        if (cartItemQuantity.value > 1) {
          cartItemQuantity.value--;
        }
      });

      // Disable editing of the quantity input field
      cartItemQuantity.setAttribute("readonly", true);
     </script>
</body>