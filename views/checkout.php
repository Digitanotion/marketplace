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
    .checkout__input {
        background-color: #f3faff !important;
        margin: 10px;
        margin-left: 0px !important;
    }
textarea.form-control:focus {
  /* border: 0; */
  background-color: unset;
  outline: none !important;
  box-shadow: none;
}

    /* .checkout__input > input {
        padding: 5px;
        width: 270px;
    } */


       /* search box  */
@media screen and (min-width: 100px) {
  .orderSummary {
    font-size: 14px;
    margin-top: 30px;
    margin-left: 20px;
    padding: 35px;
    padding-top: 20px;
    /* width: 100%; */
    width: 79vw !important;
    box-shadow: 0px 0px 10px #c2c2c2;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
  }
  .input__div {
    /* padding-top: 100px; */
    /* margin-left: 50px; */
    padding-bottom: 15px;
    margin-right:-25px;
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

  .checkout__input > input {
        padding: 5px;
        /* width: 270px; */
        width:79vw !important;
    }

    textarea {
  box-sizing: border-box;
  width: 79vw !important;
}

select {
    width:79vw !important;
    /* width: 80% !important; */
  }
}
@media screen and (min-width: 576px) {
}
@media screen and (min-width: 768px) {
    .input__div {
    /* padding-top: 100px; */
    margin-right: 40px;
  }
 .checkout__input > input {
        padding: 5px;
        /* width: 270px; */
        width:39vw !important;
    }
}

@media screen and (min-width: 992px) {
   .orderSummary {
    /* border:1px solid #0b5ed7 ; */
    font-size: 14px;
    margin-top: 30px;
    margin-left: 20px;
    padding: 35px;
    padding-top: 20px;
    /* width: 30%; */
     width: 45vw !important;
    height:100%;
    box-shadow: 0px 0px 10px #c2c2c2;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
  }
   .checkout__input > input {
        padding: 5px;
        min-width:22.2vw !important;
        /* width:995vw !important; */
        /* width: 270px; */
        /* width:600px !important; */
    }

  textarea {
    box-sizing: border-box;
    width: 45vw !important;
  }

select {
    width:45vw !important;
    /* width: 80% !important; */
  }

  .checkout__hr {
    margin-top: 5px;
    max-width: 90%;
    height: 1px !important;
    background-color: #5c5c5c !important;
  }
}

@media screen and (min-width: 1200px) {
  .orderSummary {
    /* border:1px solid #0b5ed7 ; */
    font-size: 14px;
    padding: 35px;
    padding-top: 20px;
    /* width: 30% !important; */
    width: 32vw !important;
    height: 100%;
    box-shadow: 0px 0px 10px #c2c2c2;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
  }
  .input__div {
    /* padding-top: 100px; */
    margin-right: 0px;
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

    .checkout__input > input {
        padding: 5px;
        min-width:22.2vw !important;
        /* width:995vw !important; */
        /* width: 270px; */
        /* width:600px !important; */
    }

  textarea {
    box-sizing: border-box;
    width: 45vw !important;
  }

select {
    width:45vw !important;
    /* width: 80% !important; */
  }
.checkout__input-div > div:nth-child(2), div:nth-child(4) {
    padding-left: 0px;
}

  .fa-truck {
    font-size: 50px !important;
    /* width: 200px; */
  }
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
          <p class="cart__header">Checkout</p>
        </div>
      </section>

      <!-- checkout form -->
      <section>
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex justify-content-evenly flex-wrap checkout__form">
            <div>
              <div>
                <div>
                  <p class="checkout__customer-info">
                    Customer information
                  </p>
                  <hr class="checkout__hr" />
                </div>
                <div class="">
                  <form id="checkoutForm">
                    <div class="d-flex flex-wrap align-items-center checkout__input-div">
                      <div class="form-group checkout__input">
                        <label for="inputState" class="inputState">First Name</label>
                        <input
                          class="form-control"
                          type="text"
                          name="name"
                          required
                        />
                      </div>
                      <div class="form-group checkout__input">
                      <label for="inputState" class="inputState">Last Name</label>
                        <input
                          class="form-control"
                          type="text"
                          name="name"
                          required

                        />
                      </div>
                      <div class="form-group checkout__input">
                      <label for="inputState" class="inputState">Email</label>
                        <input
                          type="email"
                          class="form-control"
                          name="email"
                          class="ml-5"
                          required
                        />
                      </div>
                      <div class="form-group checkout__input">
                      <label for="inputState" class="inputState">Phone number</label>
                        <input
                          type="text"
                          class="form-control"
                          minlength="6"
                          name="phoneNumber"
                          required
                       />
                      </div>
                    </div>
                    <div class="my-2 my-xl-2">
                      <label for="inputDeliveryAddress" class="inputState">Delivery Address</label>
                      <div class="form-group">
                        <textarea
                          name="textarea"
                          id="textarea"
                          cols="30"
                          rows="10"
                          required
                          class="form-control"
                        ></textarea>
                      </div>
                    </div>

                    <div class="form-group my-2 my-lg-2 my-xl-3">
                      <label for="inputState" class="inputState">State/Region</label>
                      <select name="" id="inputState" class="form-control" required style="font-size:13px">
                        <option selected>Select a state/region</option>
                        <option>Anambra</option>
                      </select>
                    </div>
                    <div class="form-group my-3 my-lg-2 my-xl-3">
                      <label for="inputState" class="inputState">City</label>
                      <select name="" id="inputState" class="form-control" required style="font-size:13px">
                        <option selected>Select a city</option>
                        <option>Awka south</option>
                      </select>
                    </div>
                    <div class="form-group my-2">
                      <div class="form-check d-flex align-items-md-center">
                        <input class="form-check-input" type="checkbox" required/>
                        <label for="checkLabel" class="form-check-label mx-2" id="agreedInformation">
                          I agree that the informations given are accurate
                        </label>
                      </div>
                    </div>
                    <!-- <button type="submit" class="btn signUp-submit mt-4">
                    checkout
                  </button> -->
                  </form>
                </div>
                <div>
                  <form action="">
                    <div class="my-4">
                      <p class="checkout__customer-info" style="margin-bottom:7px">Method of Delivery</p>
                        <hr class="checkout__hr" >
                    </div>
                    <div class="d-flex">
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input checkbox" type="checkbox" required/>
                          <label for="checkLabel" class="d-flex align-items-center form-check-label">
                              <i class="fa fa-truck text-primary"  style="font-size:50px"></i>
                            Pickup Station 
                          </label>
                        </div>
                      </div>
                      <div class="form-group mx-3">
                        <div class="form-check">
                          <input class="form-check-input checkbox" type="checkbox" required/>
                          <label for="checkLabel" class="d-flex align-items-center form-check-label">
                              <!-- <i class="fa-solid fa-hand-holding-box"></i> version 6 -->
                              <i class="fa fa-gift text-primary" style="font-size:50px"></i>
                              Door delivery 
                          </label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="orderSummary">
            <p class="my-3">Order summary</p>
            <ul class="list-unstyled">
              <div class="d-flex justify-content-between">
                <li>Items</li>
                <p>3</p>
              </div>
              <div class="d-flex justify-content-between my-2">
                <li>Sub Total</li>
                <p>Y500</p>
              </div>
              <div class="d-flex justify-content-between my-2">
                <li>Shipping Cost</li>
                <p>Y700</p>
              </div>
              <div class="d-flex justify-content-between my-2">
                <li>Tax</li>
                <p>Y200</p>
              </div>
              <hr />
              <div class="d-flex justify-content-between my-2">
                <li>Total</li>
                <p>Y700</p>
              </div>
            </ul>
            <button
              type="button"
              class="text-light rounded checkout__proceed"
              id="proceed"
            >
              Proceed
            </button>
          </div>
        </div>
      </section>
    </main>
    
     <?php include 'footer.php'; ?>

<script>

    // this code checks for one delivery method 
 const inputCheckoutCheckboxes = document.querySelectorAll('.checkbox');

 inputCheckoutCheckboxes.forEach(inputCheckoutCheckbox=>{
    inputCheckoutCheckbox.addEventListener('click',()=>{
        if(inputCheckoutCheckbox.checked){
            inputCheckoutCheckboxes.forEach(otherCheckbox=>{
                if(otherCheckbox!==inputCheckoutCheckbox){
                            otherCheckbox.disabled=true;
                }
            })
        }else{
          inputCheckoutCheckboxes.forEach(otherCheckbox=>{
            if(otherCheckbox!==inputCheckoutCheckbox){
                otherCheckbox.disabled=false;
            }
          })  
        }
    })
 })

//  i agree checkbox must be checked before proceeding to the  payment stage 
const checkoutForm=document.querySelector('#checkoutForm')
const agreeInputCheckbox = document.querySelector("#agreedInformation")
const proceedBtn = document.querySelector('#proceed');

agreeInputCheckbox.addEventListener('change',()=>{
    if(agreeInputCheckbox.checked){
        submitBtn.disabled=false
    }else{
        submitBtn.disabled=true
    }
})

checkoutForm.addEventListener('onClick',e=>{
    if(agreeInputCheckbox.checked){
        event.preventDefault();
        alert('Agree to the term and conditions')
    }
})
</script>
</body>