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
    <title>Our Billing Policy | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
</head>
<body class="bg-light-blue">
<?php include "header-top.php";?>
  
    <section class="container-fluid m-0 p-0"> 
        <div class="row m-0 px-4 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <h1 class="mt-4">BILLING POLICY</h1>
            <ol class="fs-6">
                <li>You can use premium services provided by the Administrator in order to promote your announcements and attract more users to them.</li>
                <li>The administrator offers premium services such as Boost Plans and Top Ads Promo packages. If you order a Boost Plan, your announcements will be raised overall free offers. They will be displayed on all pages of a column, a section, or a region. Top announcements are placed in a separate block over the ordinary announcements. Detailed information on the duration, price, payment methods and other conditions of premium services are available at:</li>
                <li>To order premium services on the website, you should visit or choose a preferred premium service while creating an announcement. If you use a mobile application, you should visit “Premium Services” section in your Profile or choose a preferred premium service while creating an announcement.</li>
                <li>When ordering premium services, you will be able to choose a payment method that is most suitable for you. If you choose to pay by card, bank transfer or eWallet, you will be redirected to the checkout page of a relevant payment processor. All data entered by you on payment screens will be secured. The administrator will only receive information that relates to the payment. If you choose to pay by cash, you will receive payment details, which you can present at the nearest bank branch and pay.</li>
                <li>Administrator reserves the right, in its sole discretion, to change the features and types of premium services, fees, and acceptable payment methods from time to time and for any reason.</li>
                <li>Unless otherwise provided by mandatory rules of the applicable law, any fees paid for premium services are non-refundable due to the online nature of the services. You acknowledge and agree that we will not make any prorated refunds in the event your announcement violates Terms of Use and is removed from the Platform, or when you deactivate an announcement by yourself.</li>
                <li>Top Ad Promo package is subject to a 12 months validity period from the date of purchase (the “Service Validity Period”). You acknowledge and agree that if you fail to use the Top Ads Promo package during the Service Validity Period, you will have neither the right to initiate or use such Top Ad Promo package nor any right to refund, compensation or replacement.</li>
                <li>Available premium services are not subscription-based, therefore you should renew their validity period manually each time you need them.</li>
                <li>Administrator does not guarantee any results of premium services and will not be held liable if they do not meet your expectations.</li>
                <h4>Referral Program</h4>
                <li>Our referral program allows you to earn bonuses (the “Referral Bonuses”) for each Valid Referral. The referral is valid if: (i) the referred friend creates an account on the Platform, and (ii) the referred friend confirms his/her phone number on the Platform.</li>
                <li>Referral Bonuses in the amount specified on the Platform will be credited on your bonus account within 24 hours from the date of each Valid Referral.</li>
                <li>Referral Bonuses may be (i) used to pay for premium services on the Platform; or (ii) used to recharge your mobile by phone number. 1 Referral Bonus will be equal to 1 Nigerian Naira.</li>
                <li>Referral Bonuses must be used within 12 months from the date they are credited on your account (the “Referral Bonuses Validity Period”), after which they will expire. You acknowledge and agree that if you fail to use the Referral Bonuses during the Referral Bonuses Validity Period, you will lose your right to use them and you will not be entitled for any refund, compensation or replacement.</li>
                <li>You shall be solely responsible for any tax consequences that may result from your use of the Referral Bonuses.</li>
                <li>All Referral Bonuses may be forfeited if your account is suspended or terminated for any reason, at our sole and absolute discretion without prior notice, including, but not limited to, for the following reasons:</li>
                <ul>
                    <li>your account on the Platform is inactive (i.e., not used or logged into) for one year;</li>
                    <li>you fail to comply with the Terms (Terms of Use, Privacy Policy);</li>
                    <li>we suspect fraud or misuse by you of the Referral Bonuses and the service;</li>
                    <li>we suspect any other unlawful activity associated with your account;</li>
                    <li>we are acting to protect the service, any of our users, or our reputation.</li>
                </ul>
                <li>You will not receive money or other compensation for unused Referral Bonuses when your account is closed whether such closure was voluntary or involuntary.</li>
                <h4>Credits</h4>
                <li>You can purchase credits on the Platform (the “Credits”) that may only be used to pay for the premium services. When you pay for the premium services, 1 Credit will be equal to 1 Nigerian Naira.</li>
                <li>Credits must be used within 12 months from the date of purchase (the “Credits Validity Period”), after which they will expire. You acknowledge and agree that if you fail to use the Credits during the Credits Validity Period, you will lose your right to use them and you will not be entitled for any refund, compensation or replacement.</li>
                <li>You agree that purchased Credits have no monetary value and do not constitute actual currency or property of any type. The Credits may never be sold, transferred, traded or exchanged through any legally acceptable payment method, goods or other items of monetary value from us or anyone else.</li>
                <li>You may not buy or sell any Credits or your account in exchange for legally acceptable money or otherwise exchange them for any other kind of value through any means other than that established by us.</li>
                <li>All Credits may be forfeited if your account is suspended or terminated for any reason, at our sole and absolute discretion without prior notice, including, but not limited to, for the following reasons:</li>
                <ul>
                    <li>your account on the Platform is inactive (i.e., not used or logged into) for one year;</li>
                    <li>you fail to comply with the Terms (Terms of Use, Privacy Policy);</li>
                    <li>we suspect fraud or misuse by you of the Credits and the service;</li>
                    <li>we suspect any other unlawful activity associated with your account;</li>
                    <li>we are acting to protect the service, any of our users, or our reputation.</li>
                </ul>
                <li>You will not receive money or other compensation for unused Credits when your account is closed whether such closure was voluntary or involuntary.</li>
            </ol>
            <p>Last update: 12 August 2020</p>
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