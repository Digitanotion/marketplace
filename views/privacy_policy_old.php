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
    <title>Our Privacy Policy | Gaijinmall</title>
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
<?php include "header-top.php"; ?>
  
    <section class="container-fluid m-0 p-0"> 
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between bg-white p-4">
            <h1 class="">Privacy Policy</h1>
            <p class="">This Privacy Policy explains what personal data is collected when you use the Gaijinmall any Gaijinmall mobile application (“Gaijinmall”) and the services provided through it (together the “Service”), how such personal data will be used, shared.</p>
            <p class="">BY USING THE SERVICE, YOU PROMISE US THAT (I) YOU HAVE READ, UNDERSTAND AND AGREE TO THIS PRIVACY POLICY, AND (II) YOU ARE OVER 16 YEARS OF AGE (OR HAVE HAD YOUR PARENT OR GUARDIAN READ AND AGREE TO THIS PRIVACY POLICY FOR YOU). If you do not agree, or are unable to make this promise, you must not use the Service. In such case, you must contact the support team via email to request deletion of your account and data.</p>
            <p class="">“Process”, in respect of personal data, includes to collect, store, and disclose to others.</p>
            <h4 class="">TABLE OF CONTENTS</h4>
            <ol class="">
                <a href="#personal" class="text-primary"><h5><li>PERSONAL DATA CONTROLLER</li></h5></a>
                <a href="#categories" class="text-primary"><h5><li>CATEGORIES OF PERSONAL DATA WE COLLECT</li></h5></a>
                <a href="#dataProtect" class="text-primary"><h5><li>DATA PROTECTION PRINCIPLES</li></h5></a>
                <a href="#whatPurpose" class="text-primary"><h5><li>FOR WHAT PURPOSES WE PROCESS PERSONAL DATA</li></h5></a>
                <a href="#legalBases" class="text-primary"><h5><li>UNDER WHAT LEGAL BASES WE PROCESS YOUR PERSONAL DATA</li></h5></a>
                <a href="#yourData" class="text-primary"><h5><li> WITH WHOM WE SHARE YOUR PERSONAL DATA</li></h5></a>
                <a href="#remedies" class="text-primary"><h5><li>AVAILABLE REMEDIES</li></h5></a>
                <a href="#limitationClause" class="text-primary"><h5><li>NO LIMITATION CLAUSE</li></h5></a>
                <a href="#yourRights" class="text-primary"><h5><li>HOW YOU CAN EXERCISE YOUR PRIVACY RIGHTS</li></h5></a>
                <a href="#ageLimitation" class="text-primary"><h5><li>AGE LIMITATION</li></h5></a>
                <div id="personal"></div>
                <a href="#changesPolicy" class="text-primary"><h5><li>CHANGES TO THIS PRIVACY POLICY</li></h5></a>
                <a href="#dataRetention" class="text-primary"><h5><li>DATA RETENTION</li></h5></a>
                <a href="#contact" class="text-primary"><h5><li>CONTACT US</li></h5></a>
            </ol>
            <ol class="pe-5">
                <h5><li>PERSONAL DATA CONTROLLER</li></h5>
                <p>Gaijinmall Online Marketplace Nigeria Limited, a company registered in the Federal Republic of Nigeria (with registered office at 350, Borno Way, Yaba, Lagos, Nigeria) will be the controller of your personal data. </p>
                <p> Cars45 Limited, a company registered in the Federal Republic of Nigeria (with registered office at 4th Floor, CIPM House, CIPM Avenue, CBD, Alausa, Ikeja, Lagos) will be the joint controller of the categories of personal data as follows:</p>
                <p>In relation to certain categories of personal data expressly specified below 2.3.5</p>
                <ul>
                    <div id="categories"></div>
                    <li><p>a) car dealers' transaction data relating to sales at the car auction (paragraph 2.3.5.);</p> </li>
                    <li><p>b) personal ID, Bank ID and САС of car dealers (clause 2.1.);</p> </li>
                    <li><p> c) the Required Information of car sellers and car dealers (clause 2.1.).</p></li>
                </ul>
                <h5><li>CATEGORIES OF PERSONAL DATA WE COLLECT</li></h5>
                <p>We collect data you give us voluntarily (for example, an email address). We also collect data automatically (for example, your IP address).</p>
                <p> 2.1 Data you give us.</p>
                <p> You may be asked to provide us information about yourself when you register for and/or use the Service. This information includes: first name, last name, phone number, email, gender, date of birth (together “Required Information”), as well as your photo, address details, working hours. You will need to provide us with your ID or similar identification document, if you want to post advertisement in Mobile Phones category, unblock your account or make duplicate account primary. You will need to provide us with your Bank ID and САС (certificate of incorporation of legal entity) if you want to participate in car auction as a dealer.</p>   
                <p>To use our Service and register an account, you will need to provide Required Information. You will be able to use the Service even if you do not give this data to us, but some Service’s functionality may be limited to you (for example, if you do not register an account, you will not be able to chat with other users, post ads, see contact details of other users).</p>
                <p>Sometimes you may also need to provide to us additional information in the communication with our Support Team in order to fulfill your request (for example, if your account was previously blocked, we may ask you to confirm your identity by providing an ID document).</p>
                <p>While posting an announcement on the Service, you can decide to provide additional personal information on yourself. For example, you can decide to make available your CV. You acknowledge that by providing your personal data in the announcement you are making such data publicly available. In addition, you acknowledge and agree that we will make public some personal data from your profile to provide the Service, - it will enable us to facilitate communication and transactions between the users.</p>
                <p>You should carefully consider risks associated with the fact that you make certain information – in particular, your phone number, address, or exact location – publicly available.</p>
                <p>2.2. Data provided to us by third parties</p>
                <p>When you decide to log in using Facebook, we get personal data from your Facebook account. This includes your profile image, name, and Facebook ID. Unless you opt-out on the Facebook Login screen, we will also collect other data, such as email address, gender, date of birth, friends list, and location as listed in your Facebook profile.</p>
                <p>For more information, please refer to the Facebook Permissions Reference (describes the categories of information, which Facebook may share with third parties and the set of requirements) and to the Facebook Data policy. In addition, Facebook lets you control the choices you made when connecting your Facebook profile to the App on their Apps and Websites page.</p>
                <p>When you log in with Google, we get your name, email address, profile picture and Google ID. We use your personal data from Google in accordance with this Privacy Policy. To know more about how Google processes your data, visit its Privacy Policy. To remove access granted to us, visit Google Permissions.</p>
                <p>When you decide to log in using Apple, we get Apple ID, name and email from your account. You can use Hide My Email function during signing in with Apple, and it will create and share a unique, random email address that will forward our messages to your personal email.</p>
                <p>Apple lets you revoke access provided to the App on your Apple ID Manage Page by following the next steps.</p>
                <p>When you decide to log in using Truecaller, we get personal data from your Truecaller user profile upon your consent. This includes your phone number, name, addresses (country code; city; street; zip code), profile image, job title, company name, gender, and other information from your profile. For more information, please refer to the Truecaller Privacy Policy.</p>
                <p>If you were invited to create an account in the Service, the person that invited you can provide personal information about you, such as your phone number, email address, social media account or other contact information.</p>
                <p>2.3. Data we collect automatically:</p>
                <p>Data about how you found us</p>
                <p>We collect data about your referring URL (that is, the place on the Web where you were when you tapped on our ad).</p>
                <p>2.3.2. Device and Location data.</p>
                <p>We collect data from your device. Examples of such data include: language settings, IP address, time zone, type and model of a device, device settings, operating system, Internet service provider, mobile carrier, hardware ID, and Facebook ID.</p>
                <p>2.3.3. Usage data</p>
                <p>We record how you interact with our Service. For example, we log the features, and content you interact with, how often you use the Service, how long you are on the Service, what sections you use, how many ads you watch.</p><br>
                <p>2.3.4. Advertising IDs</p>
                <p>We collect your Apple Identifier for Advertising (“IDFA”) or Google Advertising ID (“AAID”) (depending on the operating system of your device). You can typically reset these numbers through the settings of your device’s operating system (but we do not control this).</p>
                <p>2.3.5. Transaction data</p>
                <p>When you make payments through the Service, you need to provide financial account data, such as your credit card number, to our third-party service providers. We do not collect or store full credit card number data, though we may receive credit card-related data, data about the transaction, including: date, time and amount of the transaction, the type of payment method used.</p>
                <div id="dataProtect"></div>
                <p>2.3.6. Cookies</p>
                <p>Please see our Cookies Policy to find out more about our use of cookies.</p>
                <h5><li>DATA PROTECTION PRINCIPLES</li></h5>
                <p>In our data protection practices we strive to, in particular, to provide that personal data is:</p>
                <ul>
                    <li><p>processed in accordance with specific, legitimate and lawful purpose consented to by you;</p></li>
                    <div id="whatPurpose"></div>
                    <li><p>is adequate, accurate and without prejudice to the dignity of human person;</p></li>
                    <li><p>stored only for the period within which it is reasonably needed; and</p></li>
                    <li><p>secured against reasonably foreseeable hazards and breaches such as theft, cyberattack, viral attack, dissemination, manipulations of any kind, damage by rain, fire or exposure to other natural elements.</p> </li>
                </ul>
                <h5><li>FOR WHAT PURPOSES WE PROCESS YOUR PERSONAL DATA</li></h5>
                <p>We process your personal data: <br>
                    4.1. To provide our Service<br>
                    This includes enabling you to use the Service in a seamless manner and preventing or addressing Service errors or technical issues.<br>
                    4.2. To customize your experience<br>
                    We process your personal data to adjust the content of the Service and make offers tailored to your personal preferences and interests.<br>
                    4.3. To manage your account and provide you with customer support<br>
                    We process your personal data to respond to your requests for technical support, Service information or to any other communication you initiate. This includes accessing your account to address technical support requests. For this purpose, we may send you, for example, notifications or emails about the performance of our Service, security, payment transactions, notices regarding our Terms of Use or this Privacy Policy.<br>
                    4.4. To communicate with you regarding your use of our Service<br>
                    We communicate with you, for example, by push notifications or in the chat. As a result, you may, for example, receive a notification whether on the Website or via email that you received a new message on Gaijinmall. To opt out of receiving push notifications, you need to change the settings on your browser or mobile device. To opt out of certain type of emails, you need to follow unsubscribe link located in the footer of the email, by contacting our support team., or in your profile settings.<br>
                    The services that we use for these purposes may collect data concerning the date and time when the message was viewed by our users, as well as when they interacted with it, such as by clicking on links included in the message.<br>
                    4.5. To research and analyze your use of the Service<br>
                    This helps us to better understand our business, analyze our operations, maintain, improve, innovate, plan, design, and develop Gaijinmall and our new products. We also use such data for statistical analysis purposes, to test and improve our offers. This enables us to better understand what features and sections of Gaijinmall our users like more, what categories of users use our Service. As a consequence, we often decide how to improve Gaijinmall based on the results obtained from this processing. For example, if we discover that Jobs section is not as popular as others, we may focus on improving it.<br>
                    4.6. To send you marketing communications<br>
                    We process your personal data for our marketing campaigns. We may add your email address to our marketing list. As a result, you will receive information about our products, such as for example, special offers, and products of our partners. If you do not want to receive marketing emails from us, you can unsubscribe following instructions in the footer of the marketing emails, by contacting our support team., or in your profile settings.<br>
                    We may also show you advertisements on the Website, and send you push notifications for marketing purposes. To opt out of receiving push notifications, you need to change the settings on your device or/and browser.<br>
                    4.7. To personalize our ads<br>
                    We and our partners use your personal data to tailor ads and possibly even show them to you at the relevant time. For example, if you have visited our Website, you might see ads of our products, for example, in your Facebook’s feed.<br>
                    We may target advertising to you through a variety of ad networks and exchanges, using data from advertising technologies on and off of our Services like unique cookie or similar tracking technology, pixel, device identifiers, geolocation, operation system information, email.<br>
                    How to opt out or influence personalized advertising<br>
                    iOS: On your iPhone or iPad, go to “Settings,” then “Privacy” and tap “Advertising” to select “Limit Ad Track”. In addition, you can reset your advertising identifier (this also may help you to see less of personalized ads) in the same section.<br>
                    Android: To opt-out of ads on an Android device, simply open the Google Settings app on your mobile phone, tap “Ads” and enable “Opt out of interest-based ads”. In addition, you can reset your advertising identifier in the same section (this also may help you to see less of personalized ads).<br>
                    To learn even more about how to affect advertising choices on various devices, please look at the information available here.<br>
                    In addition, you may get useful information and opt out of some interest-based advertising, by visiting the following links:<br>
                    <ul>
                        <li><p>Network Advertising Initiative – http://optout.networkadvertising.org/</p></li>
                        <li><p>Digital Advertising Alliance – http://optout.aboutads.info/</p></li>
                        <li><p>Digital Advertising Alliance (Canada) – http://youradchoices.ca/choices</p></li>
                        <li><p>Digital Advertising Alliance (EU) – http://www.youronlinechoices.com/</p></li>
                        <li><p>DAA AppChoices page – http://www.aboutads.info/appchoices</p></li>
                    </ul></p>
                    <p> Google allows its users to opt out of Google’s personalized ads and to prevent their data from being used by Google Analytics.<br>
                    Facebook also allows its users to influence the types of ads they see on Facebook. To find how to control the ads you see on Facebook, please go here or adjust your ads settings on Facebook.<br>
                    Please refer to our Cookies Policy to find out how to manage the use of cookies.<br>
                    4.8. To enforce our Terms and Conditions of Use and to prevent and combat fraud </p>
                    <div id="legalBases"></div>
                    <p>We use personal data to enforce our agreements and contractual commitments, to detect, prevent, and combat fraud. As a result of such processing, we may share your information with others, including law enforcement agencies (in particular, if a dispute arises in connection with our Terms of Use).<br>
                    4.9. To comply with legal obligations<br>
                    We may process, use, or share your data when the law requires it, in particular, if a law enforcement agency requests your data by available legal means.<br>
                    4.10. To process your payments<br>
                    We provide paid products and/or services within the Service. For this purpose, we use third-party services for payment processing (for example, payment processors). As a result of this processing, you will be able to make a payment and use the paid features of the Service.</p>
                <h5><li>UNDER WHAT LEGAL BASES WE PROCESS YOUR PERSONAL DATA
                    </li></h5>
                    <p>We process your personal data, in particular, under the following legal bases: <br>
                    5.1. your consent; <br>
                    On this basis we use your cookies as described in our Cookies Policy . <br>
                    5.2. to perform our contract with you; <br>
                    5.3. for our (or others') legitimate interests; Under this legal basis we, in particular:</p>
                    <ul>
                        <li><p>communicate with you regarding your use of our Service
                            This includes, for example, sending you push notifications reminding you that you have unread messages. The legitimate interest we rely on for this purpose is our interest to encourage you to use our Service more often. We also take into account the potential benefits to you.</p></li>
                        <li><p>research and analyze your use of the Service
                            Our legitimate interest for this purpose is our interest in improving our Service so that we understand users’ preferences and are able to provide you with a better experience (for example, to make the use of our mobile application easier and more enjoyable, or to introduce and test new features).</p></li>
                        <li><p>send you marketing communications
                            The legitimate interest we rely on for this processing is our interest to promote our Service in a measured and appropriate way.</p></li>
                        <li><p>personalize our ads
                            The legitimate interest we rely on for this processing is our interest to promote our Service in a reasonably targeted way.</p></li>
                            <div id="yourData"></div>
                        <li><p>enforce our Terms of Use and to prevent and combat fraud
                            Our legitimate interests for this purpose are enforcing our legal rights, preventing and addressing fraud and unauthorised use of the Service, non-compliance with our Terms of Use.</p></li>
                    </ul>
                    <p>5.4. to comply with legal obligations.</p>
                <h5><li>WITH WHOM WE SHARE YOUR PERSONAL DATA</li></h5>
                <p>We share information with third parties that help us operate, provide, improve, integrate, customize, support, and market our Service. We may share some sets of personal data, in particular, for purposes and with parties indicated in Section 2 of this Privacy Policy. The types of third parties we share information with include, in particular:</p>
                <p>6.1. Service providers</p>
                <p>We share personal data with third parties that we hire to provide services or perform business functions on our behalf, based on our instructions. We may share your personal information with the following types of service providers:</p>
                <ul>
                    <li><p>cloud storage providers (Amazon, DigitalOcean, Hetzner)</p></li>
                    <li><p>data analytics providers (Facebook, Google, Appsflyer)</p></li>
                    <li><p>login service providers (Google, Apple, Facebook, Truecaller)</p></li>
                    <li><p> marketing partners (in particular, social media networks, marketing agencies, email delivery services; such as Facebook, Google)</p></li>
                    <li><p>payment services providers (Solid)</p></li>
                </ul>
                <p>6.2. Law enforcement agencies and other public authorities</p>
                <p>We may use and disclose personal data to enforce our Terms of Use, to protect our rights, privacy, safety, or property, and/or that of our affiliates, you or others, and to respond to requests from courts, law enforcement agencies, regulatory agencies, and other public and government authorities, or in other cases provided for by law.</p>
                <p>We may use and disclose personal data to enforce our Terms of Use, to protect our rights, privacy, safety, or property, and/or that of our affiliates, you or others, and to respond to requests from courts, law enforcement agencies, regulatory agencies, and other public and government authorities, or in other cases provided for by law.</p>
                <p>We may use and disclose personal data to enforce our Terms of Use, to protect our rights, privacy, safety, or property, and/or that of our affiliates, you or others, and to respond to requests from courts, law enforcement agencies, regulatory agencies, and other public and government authorities, or in other cases provided for by law.</p>
                <p>We may use and disclose personal data to enforce our Terms of Use, to protect our rights, privacy, safety, or property, and/or that of our affiliates, you or others, and to respond to requests from courts, law enforcement agencies, regulatory agencies, and other public and government authorities, or in other cases provided for by law.</p>
                <p>We may use and disclose personal data to enforce our Terms of Use, to protect our rights, privacy, safety, or property, and/or that of our affiliates, you or others, and to respond to requests from courts, law enforcement agencies, regulatory agencies, and other public and government authorities, or in other cases provided for by law.</p>
                <p>6.3. Third parties as part of a merger or acquisition</p>
                <div id="remedies"></div>
                <p>As we develop our business, we may buy or sell assets or business offerings. Customers’ information is generally one of the transferred business assets in these types of transactions. We may also share such information with any affiliated entity (e.g. parent company or subsidiary) and may transfer such information in the course of a corporate transaction, such as the sale of our business, a divestiture, merger, consolidation, or asset sale, or in the unlikely event of bankruptcy.</p>
                <h5><li>AVAILABLE REMEDIES</li></h5>
                <div id="limitationClause"></div>
                <p>If there is a data breach that may cause a risk for the rights and freedoms of individuals, we will notify competent supervisory authority, when such notification is mandatory. If the risk is assessed by us as “high”, we will also notify the affected data subjects without undue delay. The time frame for such notification will be developed by us on the basis of number of affected data subjects and time needed to collect contact information for notifications.</p>
                <div id="yourRights"></div>
                <p>If the measures taken or proposed to be taken in response to the breach did not address your concerns, you have the right to lodge a complaint with a competent supervisory authority, or to seek redress in a court of competent jurisdiction.</p>
                <h5><li>NO LIMITATION CLAUSE</li></h5>
                <p>No limitation of liability shall avail us in case we act in breach of the principles set out in Section 3.</p>
                <h5><li>HOW YOU CAN EXERCISE YOUR RIGHTS</li></h5>
                <p>To be in control of your personal data, you have the following rights:</p> 
                <p>Accessing / reviewing / updating / correcting your personal data. You may review, edit, or change the personal data that you had previously provided to Gaijinmall in the settings section on the Website.</p>
                <p>You may also request a copy of your personal data collected during your use of the Service.</p>
                <p> Deleting your personal data. You can request erasure of your personal data by sending us an email.</p>
                <p> When you request deletion of your personal data, we will use reasonable efforts to honor your request. In some cases, we may be legally required to keep some of the data for a certain time; in such event, we will fulfill your request after we have complied with our obligations.</p>
                <p>Objecting to or restricting the use of your personal data (including for direct marketing purposes). You can ask us to stop using all or some of your personal data or limit our use thereof by sending a request.</p>
                <p>The right to lodge a complaint with supervisory authority. We would love you to contact us directly, so we could address your concerns. Nevertheless, you have the right to lodge a complaint with a competent data protection supervisory authority. </p>
                <h5 id="ageLimitation"><li>AGE LIMITATION</li></h5>
                <p>We do not knowingly process personal data from persons under 16 years of age. If you learn that anyone younger than 16 has provided us with personal data, please contact us.</p>
                <h5 id="changesPolicy"><li>CHANGES TO THIS PRIVACY POLICY</li></h5>
                <p>We may modify this Privacy Policy from time to time. If we decide to make material changes to this Privacy Policy, you will be notified through our Service or by other available means and will have an opportunity to review the revised Privacy Policy. By continuing to access or use the Service after those changes become effective, you agree to be bound by the revised Privacy Policy.</p>
                <h5 id="dataRetention"><li>DATA RETENTION</li></h5>
                <p>We will store your personal data for as long as it is reasonably necessary for achieving the purposes set forth in this Privacy Policy (including providing the Service to you), which includes (but is not limited to) the period during which you have a Gaijinmall account. We will also retain and use your personal data as necessary to comply with our legal obligations, resolve disputes, and enforce our agreements.</p>
                <h5 id="contact"><li>CONTACT US</li></h5>
                <p>You may contact us at any time for details regarding this Privacy Policy and its previous versions. For any questions concerning your account or your personal data please contact us.</p>
            </ol>
            <p>Effective as of: 15 February 2022</p>
        </div>
    </section>
    <?php include "footer.php"; ?>

    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/adverts.js"></script>

</body>
</html>