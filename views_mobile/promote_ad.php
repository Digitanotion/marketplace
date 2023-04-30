<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
} 
use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\MedS\MediaManager;
use services\InitDB;
use services\AudS\AuditManager;
use services\MsgS\messagingManager;

$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$mediaManager = new MediaManager();
$audService_ob = new AuditManager();
$messaging_ob = new messagingManager();
$adID = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promote Ad | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/translate.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    <!-- google translator  -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'ja' }, 'google_translate_element');
        }
    </script>
         <!-- google translator  -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
</head>

<body class="bg-light-blue">
    <?php include "header-top.php"; ?>
    <div id="ha-header__top" class="row bg-light-blue px-0 px-md-5 px-lg-5 justify-content-center align-items-center fixed-top py-3 ">
        <!--IF NOT SIGNED IN SHOW THIS-->
        <div class="col-md-4 col-lg-4 col-4">
            <div class="logo" style="max-width: 130px;">
                <img src="./assets/images/logo-sm.png" class="img-fluid">
            </div>

        </div>
        <div class="col-md-3 col-lg-3 col-1 text-center ha-social__top ">
            <div class="d-none d-md-block d-lg-block fs-6">
                COOL BUY & SELL
            </div>
        </div>
        <div class="col-md-5 col-lg-5 col-5 text-end ha-fs_7 nav-container fw-bolder">
            <!-- Sign in | Create Account -->
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <ul>
                    <li><a class="active ha-nav__item" href="#"><i class="fa fa-bookmark mx-auto"></i></a></li>
                    <li><a href="#" class="ha-nav__item"><i class="fa fa-envelope mx-auto"></i></a></li>
                    <li><a href="#" class="ha-nav__item"><i class="fa fa-bell mx-auto"></i></a></li>
                    <li><a href="#" class="ha-nav__item"><i class="fa fa-list mx-auto"></i></a></li>
                    <li><a href="#" class="ha-nav__profile">N</a></li>
                </ul>
            </nav>
        </div>
        <!--IF NOT SIGNED IN SHOW THIS-->

    </div>
    <div class="mt-md-5 mt-lg-5 mb-md-5 mb-lg-5">
        <div class="mt-md-5 mt-lg-5">&nbsp;</div>
    </div>

    <section class="container-fluid m-0 p-0">
        <div id="section1">
            <div class="text-center px-3">
                <h3 class="fw-bolder ">Increase your sales with Gaijinmall promoted adverts!</h3>
                <p>Choose the right categories for your ads and start selling faster</p>
            </div>
            
            <div class="text-center my-4">
                <input type="button" value="HOW DOES IT WORK?" class="p-2 px-3 w-sm-100 fs-6 btn-outline-primary rounded-3" onclick="secondSection()">
            </div>
        </div>

        <div class="section2" id="section2">
            <div class="text-center">
                <h3 class="fw-bold">How do Promoted Ads work?</h3>
            </div>
            <div class="row justify-content-center m-5 p-0">
                <div class="col-lg-4 p-4">
                    <img src="./assets/images/0001.png" alt="" class="w-100">
                </div>
                <div class="col-lg-4 p-4">
                    <p>These services are promotion tools for the sellers which help to advertise the item as much as posssible in the particular category, to sell all the goods faster and get in several times for clients.</p>
                    <p class="fw-bold">Create an Ad up and your sales, make money easier with this service, by clicking on Promote Ad button.</p>
                    <button class="px-5 py-2 bg-primary border-0 rounded-2 fs-6 w-100">REQUEST MANAGER'S CALL</button>
                </div>
            </div><br><br>
            <div class="p-1 bg-white">
                <!--  <div class="row justify-content-center m-4 my-lg-5 mt-5">
                    <div class="col-lg-3 bg-primary text-white p-4 rounded-3 m-4">
                        <p class="fw-bold border-bottom text-center">TOP ADS PROMO</p>
                        <P>TOP package is the best choice if you want to push a certain ads to the top of the page and get 15x clients.</P>
                        <p class="fw-bold">TOP package is the best choice if you want to push certain ads to the top of page and get 15x clients.</p>
                    </div>
                    <div class="col-lg-3 bg-primary text-white p-4 rounded-3 m-4">
                        <p class="fw-bold border-bottom text-center">BOOST PLANS</p>
                        <P>Boost plan is a great solution to promote alll your ads for more than a month</P>
                        <p class="fw-bold">Recommended for sellers with more than 5 adverts.</p>
                    </div>
                    <div class="col-lg-3 bg-primary text-white p-4 rounded-3 m-4">
                        <p class="fw-bold border-bottom text-center">BOOST PLANS</p>
                        <P>Boost plan is a great solution to promote alll your ads for more than a month</P>
                        <p class="fw-bold">Recommended for sellers with more than 5 adverts.</p>
                    </div>
                </div> -->
                <div class="row m-4 row-cols-1 row-cols-md-4 my-lg-5 mt-5 text-center ha_promoted-ad__content">
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">1 Star</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title"><?php echo $adManager_ob::CURRENCY;?>10<small class="text-muted fw-light"></small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li>2x more clients</li>
                                    <li>Ad will run for 7 days</li>
                                    <li>Email support</li>
                                    <li>Help center access</li>
                                </ul>
                                <a href="#" class="w-100 btn btn-primary">Get started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">2 Star</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title"><?php echo $adManager_ob::CURRENCY;?>15<small class="text-muted fw-light"></small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li>7x more clients</li>
                                    <li>Ad will run for 14 days</li>
                                    <li>Priority email support</li>
                                    <li>Help center access</li>
                                </ul>
                                <a href="#" class="w-100 btn btn-primary">Get started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm border-primary">
                            <div class="card-header py-3 text-white bg-primary border-primary">
                                <h4 class="my-0 fw-normal">3 Star</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title"><?php echo $adManager_ob::CURRENCY;?>29<small class="text-muted fw-light"></small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li>10x more clients</li>
                                    <li>Ad will run for 1 months</li>
                                    <li>Phone and email support</li>
                                    <li>Help center access</li>
                                </ul>
                                <a href="#" class="w-100 btn btn-primary">Get started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">4 Star</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title"><?php echo $adManager_ob::CURRENCY;?>15<small class="text-muted fw-light"></small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li>20x more clients</li>
                                    <li>Ad will run for 3 months</li>
                                    <li>Priority email support</li>
                                    <li>Help center access</li>
                                </ul>
                                <a href="#" class="w-100 btn btn-primary">Get started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
    <?php include "footer.php"; ?>
    <!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/userAdmin.js"></script>

</body>

</html>