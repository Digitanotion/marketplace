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
    <link rel="stylesheet" href="./assets/css/translate.css">
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
            <p class="">This privacy statement applies to your use of any products, services, content, features, technologies, or functions, and all related websites, mobile apps, mobile sites or other online platform or applications offered to you by us (collectively the "Services/Platform"). We collect, use, and share personal information to help the GaijinMall and its affiliate websites work and to keep it safe (details below).</p>
            <p>Information posted on GaijinMall is obviously publicly available. Our servers are located in USA. Therefore, if you choose to provide us with personal information, you are consenting to the transfer and storage of that information on our servers.</p>

            <!-- <ol class="">
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
            </ol> -->
            <ol class="pe-5">
                <h5 class="fw-bold">
                    <li>1. What data do we collect about you?</li>
                </h5>
                <p><strong>1.1 Data provided through direct interactions</strong></p>
                <p><strong>1.1.1 Registration and other account information</strong></p>
                <p>When you register to use our Services, we may collect the following information about you:</p>
                <ul class="px-0 fs-md-2 fw-bold">
                    <li>If you register using your Google account: first name, last name and email address.</li>
                    <li>If you register using your Facebook account: we collect first name and last name as appeared on your Facebook account, Facebook IDs and the URL to your Facebook profile picture. In the event you have provided permission to Facebook through their in-app privacy option (which appears just before you register on our Platform), we may collect your gender, age or email id depending on the permissions granted by you.</li>
                    <li>If you register using your mobile number we collect your mobile number.</li>
                    <li>If you register using your email id we collect your email id</li>
                    <li>If you register using your Apple ID: your first and last name and email address; you can decide whether to share your email address with us when you sign in your Apple account.</li>
                    <li>We also collect your primary email id used to download the application from the app store</li>
                </ul>
                
                
                <p>Depending on the choices you make during the log-in to our Services or during the process of engaging our Services, we may ask you to give the following additional personal data:</p>
                <ul class="px-0 fs-md-2 fw-bold">
                    <li>Your Name</li>
                    <li>E-mail Address</li>
                    <li>Mobile number</li>
                    <li>Your credit card/debit card details in case you choose to purchase our paid services</li>
                </ul>
                
                
                <p>1.1.2. Validation of your account</p>
                <p>We validate the accounts of GaijinMall users by using SMS verification to ensure that each account is associated with a real and unique user. This validation process is initiated once you proceed with posting an ad listing , take an action on an ad listing or initiate a purchase. In order to validate your account, we will send you an SMS on a valid mobile number provided by you. This process is entirely free of charge.</p>
                <p>If you do not agree to validate your account, then your account will remain active and you will be able to use our Services with limited functionality. This limited functionality of an account implies that you cannot publish new ad listings or edit, update, promote, extend, reactivate, deactivate or delete existing ad listings until your account is verified by SMS. You will also not be able to receive or reply to any messages from other users.</p>
                <p>In case you create several accounts using the same mobile number and validate all those accounts via SMS verification, all such accounts will have a limited functionality and you will be asked to choose one of them. The account chosen by you will return to full functionality, and the rest of the accounts will remain to have limited functionality.</p>
                <p>Once you have validated your account, it will remain associated with the mobile number used for the SMS verification. If you wish to change the mobile number associated with your account, you will need to contact our Customer Support team.</p>
                <p>1.1.3. Communication through the chat feature on our Platform</p>
                <p>When you use our chat feature to communicate with other users, we collect information that you choose to provide to other users through this feature.</p>
                <p>1.2. Data we collect automatically when you use of our Services</p>
                <p>When you interact with our Services, we automatically collect the following information about you:</p>
                <p>We collect device-specific information such as operating system version, unique identifiers, IMEI number, and standard web log information. For example, the name of the mobile network that you are using. We associate the device identifiers with your account.</p>
                <p>1.2.1 Device Information</p>
                <p>We may require access to other apps installed on the device, to enhance your experience. You may choose not to grant access to such apps, which will allow you access to use our services, but with limited functionality</p>
                <p>1.2.2. Location information</p>
                <p>Depending on your device permissions, if you post an item on our Platform, we automatically collect and process information about your actual location. We use various technologies to determine location, including IP address, GPS, Wi-Fi access points and mobile towers. Your location data allows you to see user items near you and helps you in posting items within your location. In case we need your location data, we will first show you a pop-up which will ask you to choose to allow or not to allow us to access your location data. If you do not allow us to have access to your location data, you will still be able to use our Services but with limited functionality. If you do allow us to access your location data you can always change this later by going to the settings on our Website or App our Platform and disable the permissions related to location sharing.</p>
                <p>1.2.3. Client and Log data</p>
                <p>Technical details, including the Internet Protocol (IP) address of your device, time zone and operating system. We will also store your login information (registration date, date of last password change, date of the last successful login), type and version of your browser.</p>
                <p>1.2.4. Clickstream data</p>
                <p>We collect information about your activity on our Platform, which includes</p>
                <p>1.2.4.1. The sites from which you accessed our Platform, date and time stamp of each visit, searches you have performed, listings or advertisement banners you clicked, your interaction with such advertisements or listings, duration of your visit and the order in which you visit the content on our Platform.</p>
                <p>1.2.4.2. Computer sign-on data, statistics on page views, traffic to and from GaijinMall and Ad data (all through cookies &ndash; you can take steps to disable the cookies on your browser although this is likely to affect your ability to use the site), Google advertising Id on Android App which is a unique, user-resettable ID for advertising, provided by Google Play services</p>
                <p>1.2.5. Cookies and Similar Technologies</p>
                <p>We use cookies to manage our users&rsquo; sessions, to store your preference language selection and deliver you relevant advertisements. "Cookies" are small text files transferred from a web server to the hard drive of your device. Cookies may be used to collect the date and time of your visit, your browsing history, your preferences, and your username. You can set your browser to refuse all or some cookies, or to alert you when websites set or access cookies. If you disable or refuse cookies, please note that some parts of our Services/Platform may become inaccessible or not function properly</p>
                
                
                <ol class="px-0 fs-md-2 fw-bold" start="2">
                    <li>Why do we process your personal information?</li>
                </ol>
                <P></P>
                <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
                <ul class="px-0 fs-md-2 fw-bold">
                    <li>Where we need to perform the contract we are about to enter into or have entered into with you.</li>
                    <li>Where we share information with our business partners for our business needs.</li>
                    <li>Where it is necessary for our legitimate interests to improve our Services and to provide you a safe and secure Platform.</li>
                    <li>Where we need to comply with a legal or regulatory obligation.</li>
                </ul>
                
                
                <p>In certain circumstances, we may also process your personal data based on your consent. If we do this, we will let you know the purpose and the category of personal data to be processed at the time we seek your consent.</p>
                <p>We have set out below a description of the reasons for which we use your personal data, [and which of the legal bases we rely on to do so. We have also identified what our legitimate interests are, where appropriate].</p>
                <p>2.1. For providing access and delivering Services through our Platform</p>
                <p>2.1.1. If you log in using your mobile number or email id, we use your first name and last name, mobile number and/or e-mail address to identify you as a user and provide access to our Platform.</p>
                <p>2.1.2. If you log in using your Facebook account or Google account or your Apple ID or your account, we use your first name and last name and your email address of such account, as well as the URL to your profile picture (except for Apple ID ) we use your first name and last name from your Facebook profile and the Facebook e-mail address to identify you as a user on our Platform and to provide you access to our Platform.</p>
                <p>2.1.3. We use third party payment service providers to process any payment you make to our Services. Depending on the method of payment, you may be requested to provide us with your payment and credit card details which we will then provide to the payment service provider in order to process your payment. We do not store your credit card information, unless you choose the option to save such information in order to make recurring payments easier without having to re-enter your details each time. In such cases, we only store your card holder name, the card expiry date, your card type and the last four digits of the card number. We do not store any credit card code verification values and merely forward such values and your credit card number in an encrypted manner for the purpose of processing your payment by our payment service provider.</p>
                <p>We process the above information for adequate performance of our contract with you.</p>
                <p>2.1.4. GaijinMall accesses metadata and other information associated with other files stored on your mobile device. This will include photographs, audio and video clips, personal contacts and address book information. If you permit GaijinMall to access the address book on your device, we may collect names and contact information from your address book and share the same with telecom Companies for promotional activities and to facilitate social interactions through our services and for other purposes described in this Policy or at the time of consent or collection. We take reasonable efforts to ensure that third parties adhere to our Privacy policies, rules and guidelines.</p>
                <p>All the information we receive about you are stored on secure servers and we have implemented technical and organizational measures that are necessary to protect your personal data.</p>
                <p>2.2. For improving your experience on the Platform and developing new functionalities of the Platform</p>
                <p>2.2.1. We use clickstream data to:</p>
                <ul class="px-0 fs-md-2 fw-bold">
                    <li>Offer you tailored content, such as giving you more relevant search results when using our Services.</li>
                    <li>To determine how much time you spend on our Platform and in what manner you navigate through our Platform in order to understand your interests and to improve our Services based on this data. For example, we may provide you with suggestions on content that you can visit based on the contents you have clicked.</li>
                    <li>To communicate marketing and promotional offers, to monitor and report the effectiveness of the campaign delivery to our business partners and internal business analysis.</li>
                </ul>
                
                
                <p>2.2.2. If you choose to provide us with your location data, we use your location data for following purposes:</p>
                <ul class="px-0 fs-md-2 fw-bold">
                    <li>To compile anonymous and aggregated information about the characteristics and behavior of users, including for the purposes of business analysis, segmentation and development of anonymous profiles.</li>
                    <li>To enhance the performance of our Services and to personalize the content we direct towards you. For example - with the help of location data we display ad listings which are in your vicinity to improve your buying experience. For this purpose, Google Maps is integrated into our Platform. This means that both Google and we are responsible for the processing of your location data in the context of Google Maps. In any case, we will not process your location data for any other purposes but those explained in this Privacy Statement. However, Google might process such location data for their own purposes as explained in the Google Privacy Policy which can be reviewed Your use of Google Maps through our Platform is subject to Google Maps&rsquo; Terms of Service.</li>
                    <li>To measure and monitor your interaction with the third-party advertisement banners, we place on our Platform.</li>
                </ul>
                
                
                <p>2.2.3. With the help of your account information, which includes your email ID and phone number, we map the different devices (such as desktop, mobile, tablets) used by you to access our Platform. This allows us to associate your activity on our Platform across devices and helps us in providing you a seamless experience no matter which device you use.</p>
                <p>2.2.4. We use the images you upload, the descriptions and prices you provide in your ad listings to train machine learning models to personalize search results in relation to ad ranking and user interest, to improve the identification and presentation of ad listings, to improve the search function and to increase the likelihood of a successful sale. This helps us to improve our Services and to provide you with a better user experience.</p>
                <p>2.2.5. To show and recommend ad listings on our Services that may be of interest to you, we make use of algorithms that use information related to your browsing behavior, items you bought, clickstream data, your user ID and your location data if you have given us permission to use this. We use this form of automated decision-making on the basis of our legitimate interest in improving our Services and provide a better user experience by offering you more relevant ad listings.</p>
                <p>2.2.6. We access and analyze your chat messages with other users conducted through the chat function on our Platform, for product enhancement and to provide you with a better user experience (e.g. to identify sold items and to provide you with active listings only). Therefore, we develop and train machine learning models and algorithms to automatically analyze your chat content. To build and train our machine learning models our machine learning specialists may review exemplary chat content manually. In these circumstances highly restricted access rights apply to selected machine learning specialists analyzing the chat content. During this process, we are de-identifying chat content as much as possible by applying a scanning filter to detect and hide personal data such as names, phone numbers, e-mail addresses. However, there may still be cases beyond our control in which the chat content may show certain personal data that you have chosen to provide.</p>
                <p>We process the above information for adequate performance of our contract with you and on the basis of our legitimate interest to improve your experience of our Services.</p>
                <p>2.3. To take your feedback, promote and offer you Services that may be of your interest</p>
                <p>2.3.1. We use your mobile number, log data and unique device identifiers to administer and protect our Platform (including troubleshooting, data analysis, testing, fraud prevention, system maintenance, support, reporting and hosting of data).</p>
                <p>2.3.2. We access and analyze your chat messages with other users conducted through the chat function on our Services for customer satisfaction, safety and for fraud prevention purposes (e.g. to block spam or abusive messages that may have been sent to you by other users). Therefore, we develop and train machine learning models and algorithms helping us to automatically detect and prevent inappropriate and fraudulent user behavior. During the analyzation and training process, we are de-identifying chat content as much as possible by anonymizing the unique identification values assigned to users. However, there may still be cases beyond our control in which the chat content may show certain personal data that you have chosen to provide. Only in limited cases and circumstances, our customer safety and security specialists review chat content manually, for example, if we have strong indications leading to the urgent suspicion of fraudulent activities. In these circumstances highly restrictive access rights apply to selected customer safety and security specialists analyzing the chat content.</p>
                <p>2.3.3. To resolve disputes, prevent and detect fraud on our Services, we make use of algorithms that recognize suspicious or fraudulent user behavior based on certain information such as user activity and posted content, which may lead to us banning certain user accounts. Automated banning happens when there is a very high degree of certainty that an account or action is fraudulent. Otherwise human review takes place by selecting customer safety and security specialists on the basis of highly restrictive access rights. We use this form of automated decision-making on the basis of our legitimate interest to detect and prevent fraud and to keep our Services safe and secure for our users. If you think that your account was wrongfully banned, you can contact our Customer Support team, in which case our team will review the decision to ban your account.</p>
                <p>2.3.4. We collect certain information from and in relation to the electronic device from which you are accessing our Services on the basis of our legitimate interest in preventing fraud on our Services. The information we collect includes your user ID (depending on whether you are logged in), country domain, IP address, device language settings, device brand and type, device operating system and version, browser type and version, and device specific software information such as fonts, system and browser Time zone, available video and audio formats. The device related information is used to determine whether the same device is being used when users interact with our Services. We associate such information with a user fraud score on the basis of which we may ban certain users. If you think that your account was wrongfully banned, you can contact us through our customer support helpline in which case our team will review the decision to ban your account.</p>
                <p>2.4. To take your feedback, promote and offer you Services that may be of your interest</p>
                <p>2.4.1. We may contact you through your registered mobile number or email id in order to take feedback from you about our Services.</p>
                <p>2.4.2. We use your email address and mobile number (by SMS) to make suggestions and recommendations to you about our Services that may be of interest to you.</p>
                <p>2.4.3. We use clickstream data to monitor and report the effectiveness of the campaign delivery to our business partners and internal business analysis.</p>
                <p>2.4.4. If you choose to provide us with your location data, we may use your location data to measure and monitor your interaction with the third-party advertisement banners we place on our Services.</p>
                <p>We process the above information based on our legitimate interest in undertaking marketing activities to offer you Services that may be of your interest. Specifically, you may receive certain marketing communications from us or our business partners:</p>
                <ul class="px-0 fs-md-2 fw-bold">
                    <li>By any preferred means of communication if you have requested such information from us.</li>
                    <li>By email or phone, regarding similar products and services, if you already use our Services or acquired some of our products.</li>
                    <li>By phone or email, if you provided us with your details when you entered a competition.</li>
                    <li>By phone or email if you registered for a promotion.</li>
                    <li>By phone or email, if you have provided your feedback for our Services through our Platform, social media, or any other means.</li>
                </ul>
                
                
                <p>Being a registered user on our Platform, please note that if you have registered yourself on DND/DNC/NCPR services, you will still receive the above communications.</p>
                <p>You can ask us to stop sending you such marketing communication at any time by clicking on the opt-out link in the email sent to you or by changing your notification settings in your account or by stating our calling agent that you do not wish to be contacted for the above marketing communications.</p>
                <p>You agree to receive an email atleast once a year informing you that in case of non-compliance with the Information Technology (Guidelines for) these terms, policies, GaijinMall has the right to terminate access or usage rights of users immediately or remove non-compliant information, or both.</p>
                <ol class="px-0 fs-md-2 fw-bold" start="3">
                    <li>How will we inform you about changes in our privacy statement?</li>
                </ol>
                <p>Generally, We may update, upgrade, modify (partially &amp;/or fully) this policy at any time, with updates taking effect when you next post or after 30 days, whichever is sooner. If we or our corporate affiliates are involved in a merger or acquisition, we may share personal information with another company, but this policy will continue to apply. Send questions about this policy to <a href="mailto:support@GaijinMall.com">support@GaijinMall.com</a></p>
                <p>You will also receive an e-mail once a year notifying you of any change to this Policy.</p>
                <ol class="px-0 fs-md-2 fw-bold" start="4">
                    <li>Communication</li>
                </ol>
                <p>We will communicate with you by email, SMS or in the app notification in connection with our Services/Platform to confirm your registration, to inform you in case your ad listing has become live/expired and other transactional messages in relation to our Services. As it is imperative for us to provide you such transactional messages you may not be able to opt -out of such messages.</p>
                <ol class="px-0 fs-md-2 fw-bold" start="5">
                    <li>Who do we share your data with?</li>
                </ol>
                <p>We may have to share your personal data with the parties set out below for the purposes set out in section 2 above.</p>
                <p>5.1. Corporate affiliates: We may share your data with our group companies, which are located within as well as outside Nigeria and help us in providing business operation services such as product enhancements, customer support and fraud detection mechanism who help detect or prevent potentially illegal acts and provide joint services (Our corporate affiliates will market only to users who ask them to)</p>
                <p>5.2. Third Party Service Providers: We use third party service providers to help us deliver certain aspect of our Services for example, cloud storage facilities. We conduct checks on our third-party service providers and require them to respect the security of your personal data and to treat it in accordance with the law. We do not allow them to use your personal data for their own purposes and only permit them to process your personal data for specified purposes and in accordance with our instructions.</p>
                <p>5.3. Advertising and analytics providers: In order to improve our Services, we will sometimes share your non-identifiable information with analytics providers that help us analyze how people are using our Platform/Service. We, share your information with them in non-identifiable form for monitoring and reporting the effectiveness of the campaign delivery to our business partners and for internal business analysis.</p>
                <p>5.5. Law enforcement authorities, regulators and others: We may disclose your personal data to law enforcement authorities, regulators, governmental or public bodies and other relevant third parties comply with any legal or regulatory requirements. We also reserve the right to make use of the personal information in any investigation or judicial process relating to fraud on account of such transactions during the period GaijinMall retains such information. We may also disclose personal information to enforce our policies, respond to claims that a posting or other content violates other&rsquo;s rights, or protects anyone&rsquo;s rights, property or safety.</p>
                <p>We may choose to sell, transfer, or merge parts of our business or our assets. Alternatively, we may seek to acquire other businesses or merge with them. If a change happens to our business, then the new owners may use your personal data in the same way as set out in this privacy statement.</p>
                <p>5.7. Access, Modification, and Deletion - You can see, modify or erase your personal information by reviewing your posting or account status page. Contact customer support at&nbsp;&nbsp; <a href="mailto:support@GaijinMall.com">support@GaijinMall.com</a> to review any personal information we store that is not available on GaijinMall. There may be a charge associated with such requests but these will not exceed the amounts permitted by law.</p>
                <ol class="px-0 fs-md-2 fw-bold" start="6">
                    <li>Where do we store your data and for how long?</li>
                </ol>
                <p>The data we collect about you will be stored and processed in secure servers in order to provide the best possible user experience. For example &ndash; for fast website or mobile application build up.</p>
                <p>We will only retain your personal data for as long as necessary to fulfill the purposes we collected it for, including for the purposes of satisfying any legal, accounting, or reporting requirements. We delete personal information when we no longer need it for the purposes we described earlier. We retain personal information as permitted by law to resolve disputes, enforce our policies; and prevent bad guys from coming back.</p>
                <p>To determine the appropriate retention period for personal data, we consider the amount, nature, and sensitivity of the personal data, the potential risk of harm from unauthorized use or disclosure of your personal data, the purposes for which we process your personal data and whether we can achieve those purposes through other means, and the applicable legal requirements.</p>
                <p>In accordance with the Information Technology and Digital Media Ethics Code, we shall retain your information for a period of 180 days or a longer period if required by the court or authorized government agencies after withdrawal or cancellation of your registration.</p>
                <ol class="px-0 fs-md-2 fw-bold" start="7">
                    <li>Technical and organizational measures and processing security</li>
                </ol>
                <p>All the information we receive about you are stored on secure servers and we have implemented technical and organizational measures that are suitable and necessary to protect your personal data (encryption, passwords, physical security)), in accordance with the Information Technology (Reasonable Security Practices and Procedures and Sensitive Personal Information Rules)</p>
                <p>GaijinMall continually evaluates the security of its network and adequacy of its internal information security program, which is designed to (a) help secure your data against accidental or unlawful loss, access or disclosure, (b) identify reasonably foreseeable risks to the security of the network, and (c) minimize security risks, including through risk assessment and regular testing. In addition, we ensure that all payment data are encrypted using SSL technology.</p>
                <p>Unfortunately, no data transmission over the internet can be guaranteed to be completely secure. So while we strive to protect such information, we cannot ensure or warrant the security of any information you transmit to us and you do so at your own risk. Once any personal information comes into our possession, we will take reasonable steps to protect that information from misuse and loss and from unauthorised access, modification or disclosure</p>
                <ol class="px-0 fs-md-2 fw-bold" start="8">
                    <li>Links to third-party websites</li>
                </ol>
                <p>Our Platform may contain links to third party websites or apps. If you click on one of these links, please note that each one will have its own privacy policy. We do not control these websites/apps and are not responsible for those policies. When you leave our Platform, we encourage you to read the privacy notice of every website you visit.</p>
                <p>If you have any queries relating to the processing/ usage of information provided by you or GaijinMall 's Privacy Policy, you may email us at <a href="mailto:support@GaijinMall.com">support@GaijinMall.com</a></p>
                
                <p><strong>Changes to this policy.</strong></p>
                <p>We may change this policy from time to time, when we do, we will inform you by updating the &ldquo;Last updated&rdquo; date below.</p>
                
                <p>Last updated: 2022-05-25</p>
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