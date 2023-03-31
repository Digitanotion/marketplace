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
USE services\SecS\SecurityManager; 
USE services\AdS\AdManager; 
USE services\MedS\MediaManager;
USE services\InitDB;
USE services\AudS\AuditManager;
USE services\MsgS\messagingManager;

$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$securityManager_ob=new SecurityManager();
$adManager_ob=new AdManager();
$mediaManager=new MediaManager();
$audService_ob=new AuditManager();
$messaging_ob=new messagingManager();
$adID="";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Gaijinmall</title>
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
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <meta name="Description" content="Gaijinmall is a classified marketplace, where you can sell your goods and services at ease.">
    <meta property="og:title" content="About Gaijinmall | A Classified marketplace to sell or buy goods and services " />
    <meta property="og:description" content="Gaijinmall is a classified marketplace, where you can sell your goods and services at ease." />
    <meta property="og:image" content="./assets/images/favicon.png">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:type" content="Classified Store" />
    <meta property="og:url" content="gaijinmall.com" />
    <meta property="og:site_name" content="Gaijinmall Marketplace" />
</head>
<body class="bg-light-blue">
<?php include "header-top.php";?>
    
    <section class="container-fluid m-0 p-0 ">
        <div class="row m-0 px-4 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="mx-sm-5">
                
                    <h2 class="fw-bold">Gaijinmall is the best place to sell anything to real people.</h2><br>
                    <p>GaijinMall is a marketplace designed for foreigners living in Japan. Join us today and start trading for free at GaijinMall.com</p>
<p>We connect buyers and sellers to exchange goods and services, with millions of listings across hundreds of categories you can buy, sell and find just about everything.</p>
<p>We have thousands of new listings daily in categories like Cars &amp; Bikes, Phones &amp; Computers, Home &amp; Garden, Baby &amp; Children, Sport &amp; Fitness, Clothing &amp; Jewelry and GaijinMall&nbsp; Jobs.</p>
<p>Our main priority and our ambition is to continue to grow a safety community marketplace where all Foreigners can trade and prosper.</p>
<p>GaijinMall is owned by Spyroxx Company Limited registered in Tokyo Japan [SPYROXX YK] www.spyroxxlimited.com (with its registered address at Tokyo-To Taito-ku Ueno 6-10-7 Ameyoko plaza Freedom F88A. JAPAN).</p>
<p>Please read our Terms of Use for more information on our working guidelines and policies.</p><br>
                    <p class="fs-6 fw-bold opacity-50">Table of contents</p>
                    <a href="#howToSell" class="text-dark"><p class="bg-white w-100 fw-bold p-2">How to sell on Gaijinmall?</p></a>
                    <a href="#howToBuy" class="text-dark"><p class="bg-white w-100 fw-bold p-2">How to buy on Gaijinmall?</p></a>
                    <div id="howToSell"></div>
                    <a href="#safety" class="text-dark"><p class="bg-white w-100 fw-bold p-2">Safety</p></a>
                    <a href="#sellLikePro" class="text-dark"><p class="bg-white w-100 fw-bold p-2">sell like a professional!</p></a>
                </div><br><br>
                <div class="mb-5">
                    <img src="./assets/images/001.svg" alt="" class="w-100 my-5 pb-5">
                </div>
                <div class="my-5 lh-1">
                    <h3 class="fw-bold">How to buy on Gaijinmall?</h3>
                    <ol>
                        <li>
                            <p class="fw-bold">Search for the item.</p>
                            <p>Find what you need using search panel and filters. We have over a million adverts, choose exactly what you are looking for.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Contact a seller.</p>
                            <p>You may use chat on Gaijinmall or call them via phone. Discuss all the details, negotiate about the price.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Take your item or order a delivery.</p>
                            <p>We check our sellers carefully, but it’s always better to check twice, right? Meet a seller in public place and be sure to pay only after collecting your item.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Leave your feedback about the seller.</p>
                            <p>Feel free to tell us about your purchase. Your feedback will be published online on the seller’s page and will be very helpful for other buyers. Let’s build a safe and professional business community together!</p>
                            <div id="safety"></div>
                        </li>
                    </ol>
    
                </div>
                <div>
                    <img src="./assets/images/003.svg" alt="" width="100%" class="my-5 pt-5">
                </div>
                <div class="my-4">
                    <h3 class="fw-bold" id="sellLikePro">Sell like a professional!</h3>
                    <ol>
                        <li>
                            <p class="fw-bold">Pay attention to the details.</p>
                            <p>Make good photos of your goods, write clear and detailed description.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Answer quickly.</p>
                            <p>Don’t make your buyer wait for your message for days. Be online or get SMS notifications on your messages.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Use Premium Services to get 15x more customers!</p>
                            <p>Your adverts will appear at the top of the page and you will sell faster! How does it work?</p>
                        </li>
                    </ol>
                 
                            <p class="fw-bold">Use our Promote Ads services to get Plus 10X or more customers!</p>
                            <p>Your adverts will appear at the top of the page and you will sell faster! How does it work?</p>
                        </li>
                    </ol>
                    <a href="./New_Ad.php" class="btn bg-primary p-2 w-auto text-white fw-bolder ms-4" >Promote Ads</a>
                </div>
            </div>
            <div class="col-md-5 col-lg-5 col-sm-11">
                <div>
                    <img src="./assets/images/004.png" alt="" width="110%" class="my-4 pb-5">
                </div><br>
                <div>
                    <h3 class="fw-bold mt-5 pt-5">How to sell on Gaijinmall?</h3>
                    <ol class="lh-1">
                        <li>
                            <a href="" class="text-dark fw-bold"><p>Register</p></a>
                            <p>Register using your e-mail and phone number (or do it via Facebook or Google). Make sure you’re entering a correct phone number, so your clients could reach you!</p>
                        </li>
                        <li>
                            <p class="fw-bold">Make photos of your item.</p>
                            <p>Feel free to make a lot of photos using your smartphone. Make sure they show your item in the best light.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Press SELL .</p>
                            <p>Choose a proper category, upload your photos and write a clear title and full description of your item. Enter a fair price, select attributes and send your advert to review!</p>
                        </li>
                        <li>
                            <p class="fw-bold">Answer the messages and calls from your clients!</p>
                            <div id="howToBuy"></div>
                            <p>If everything is ok with your advert, it’ll be on  Gaijinmall in a couple of hours after sending to moderation. We’ll send you a letter and notification when your advert goes live. Check your messages and be ready to earn money! Do you want to sell like a professional? Check out our Premium Services .</p>
                        </li>
                    </ol>
                    <a href="./New_Ad.php" class="btn bg-primary p-2 w-25 text-white fw-bolder ms-4" >SELL</a>
                </div>
                <div class="my-5 pt-4">
                    <img src="./assets/images/007.svg" alt="" width="100%">
                </div><br><br>
                <div class="my-5">
                    <h3 class="fw-bold">Safety</h3>
                    <ol>
                        <li>
                            <p class="fw-bold">General</p>
                            <p>We are highly focused on the security and can solve any issues in short-terms. That’s why we ask you, kindly, to leave a review after purchasing. If you run into any problems with a seller, you can report us and Gaijinmall Team will check this seller as soon as possible.</p>
                        </li>
                        <li>
                            <p class="fw-bold">Personal safety tips.</p>
                            <ul>
                                <li><p>Do not pay in advance, even for the delivery</p></li>
                                <li><p>Try to meet at a safe, public location</p></li>
                                <li><p>Check the item BEFORE you buy it</p></li>
                                <li><p>Pay only after collecting the item</p></li>
                            </ul>
                        </li>
                        <li>
                            <p class="fw-bold">Secure payments.</p>
                            <p>Gaijinmall provides Premium Services for those who want to sell and earn more. We accept both online and offline payments for these services. We guarantee secure and reliable payments on Gaijinmall.</p>
                        </li>
                    </ol>
                </div>
                <div>
                    <img src="./assets/images/006.svg" alt="" width="100%" class="my-5">
                </div>
            </div>
            <a href="faqs.php"><p>Still have questions? Read FAQ.</p></a>
        </div>
    </section>
    <?php include "footer.php";?>

    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/adverts.js"></script>

</body>
</html>