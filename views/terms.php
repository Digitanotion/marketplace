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
    <title>Our Terms & Conditions | Gaijinmall</title>
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
</head>
<body class="bg-light-blue">
<?php include "header-top.php"; ?>

    <section class="container-fluid m-0 p-0">
        <div class="row bg-white row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between bg-white p-4">
            <h1 class="">TERMS OF USE</h1>
            <p>GaijinMall is a marketplace designed for foreigners living in Japan. Join us today and start trading for free at GaijinMall.com</p>
<p>We connect buyers and sellers to exchange goods and services, with millions of listings across hundreds of categories you can buy, sell and find just about everything.</p>
<p>We have thousands of new listings daily in categories like Cars &amp; Bikes, Phones &amp; Computers, Home &amp; Garden, Baby &amp; Children, Sport &amp; Fitness, Clothing &amp; Jewelry and GaijinMall&nbsp; Jobs.</p>
<p>Our main priority and our ambition is to continue to grow a safety community marketplace where all Foreigners can trade and prosper.</p>
<p>GaijinMall is owned by Spyroxx Company Limited registered in Tokyo Japan [SPYROXX YK] www.spyroxxlimited.com (with its registered address at Tokyo-To Taito-ku Ueno 6-10-7 Ameyoko plaza Freedom F88A. JAPAN).</p>
<p>Please read our Terms of Use for more information on our working guidelines and policies.</p>














<p><strong>Terms of Use</strong></p>
<p><strong>Introduction.</strong></p>
<p>Welcome to www. GaijinMall.com ("GaijinMall / Site") owned by Spyroxx Company Limited registered in Tokyo Japan [SPYROXX YK] www.spyroxxlimited.com (with its registered address at Tokyo-To Taito-ku Ueno 6-10-7 Ameyoko plaza Freedom F88A. JAPAN).</p>
<p><strong>. MODIFICATIONS TO THIS AGREEMENT</strong></p>
<p>We reserve the right, at our sole discretion, to change, modify or otherwise alter these terms and conditions at any time.</p>
<p>Such modifications shall become effective immediately upon the posting thereof.</p>
<p>You must review this agreement on a regular basis to keep yourself apprised of any changes.</p>

<p><strong>ACCEPTANCE OF THE TERMS</strong></p>
<p>These Terms of Use (the &ldquo;Terms&rdquo;) constitute a binding and enforceable legal contract between GaijinMall.com Online Marketplace, its affiliated companies (together, the &ldquo;Administrator&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo;) and you. Please read these Terms carefully.</p>
<p>These are the terms and conditions governing your use of the Site. By accessing GaijinMall either through the website or any other electronic device, you acknowledge, accept and agree to the following terms, which are designed to make sure that GaijinMall works for everyone. This Terms is effective from the time you log in to GaijinMall. By accepting this Terms, you are also accepting and agreeing to be bound by the Privacy Policy and the Listing Policy. If you do not agree with any part of these Terms, or if you are not eligible or authorized to be bound by the Terms, then do not access or use the Service.</p>

<p><strong>.</strong><strong>Using GaijinMall&nbsp; </strong><strong>.</strong></p>
<p>You agree and understand that www. GaijinMall.com is an internet enabled electronic platform that facilitates communication for the purposes of advertising and distributing information pertaining to goods and/ or services. You further agree and understand that we do not own any products and services listed on GaijinMall.com. While interacting with other users on our site, with respect to any listing, posting or information we strongly encourage you to exercise reasonable diligence as you would in traditional off line channels and practice judgment and common sense before committing to or complete intended sale or purchasing of any goods or services or exchange of information. We recommend that you read our Policy before doing any activity on our site.</p>
<p>You agreed on the following:</p>
<p>.That you have the legal capacity and you agree to comply with our Terms;</p>
<p>.That if you register yourself as a representative of a legal entity, you are authorized by the company .to enter into an agreement with us and with users of the Platform;</p>
<p>.That you are an adult and above 18 years of age;</p>
<p>.That you will provided true, accurate, and complete information in your Account;</p>
<p>.That you will update your information on your Account to maintain its truthfulness, accuracy, and completeness;</p>
<p>.That you will immediately change data for access to the Platform if you have a suspicion that your Account access details were disclosed or probably used by the third parties;</p>
<p>.That you will notify the Administrator of any unauthorized access to your Account;</p>
<p>.That you will not provide any false or misleading information about your identity or location in your Account;</p>
<p>.That you will use the Service in strict abidance by applicable laws, regulations, rules, guidelines;</p>
<p>.That you will not use the Service for any illegal or unauthorized purpose;</p>
<p>.That you will not post on the Platform announcements that offer for sale or exchange any Prohibited Items or goods;</p>
<p>.That you will not post on the Platform announcements that infringe other person&rsquo;s rights or interests, including any intellectual property rights or any other personal or proprietary rights of any third party.</p>
<p>.That you will not post on the Platform announcements that include:</p>
<p>1.pornographic, overtly sexual materials;</p>
<p>2.depictions that encourage illegal or reckless use of weapons and dangerous objects, or facilitate the purchase of firearms or ammunition;</p>
<p>3.false, misleading or deceptive statements;</p>
<p>4.defamatory, discriminatory, mean-spirited, threatening or harassing, improper, unacceptable materials, vulgar or abusive language;</p>
<p>5.personal or identifying information about minors or other persons without the proper consent;</p>
<p>6.advocacy of hate, violence, discrimination, racism, xenophobia, ethnic conflicts;</p>
<p>7.appeals to violence and unlawful actions;</p>
<p>8.offers of prostitution or other services contradicting moral or legal norms;</p>
<p>9.services, provision of which is prohibited by the applicable law;</p>
<p>10.information of solely promotional nature with no offers of specific goods or services;</p>
<p>11.counterfeit and imitated goods or unauthorized copies. Unauthorized copies include also goods having been acquired by illegal means, pirated or stolen; and</p>
<p>12.direct or indirect references to any other web sites, references, or information about websites competing with the Platform;</p>
<p>.That you will not use software and pursue any other actions aimed to interference with the normal operation of the Platform;</p>
<p>.That you will not promote or distribute unsolicited commercial emails, chain letters, Ponzi schemes through the Platform or by any other means towards other users of the Platform;</p>
<p>.That you will not copy, modify, distribute any other User Content without consent of the respective user;</p>
<p>.That you will not harvest or otherwise collect information about users, including email addresses, phone numbers, without their consent or otherwise violate the privacy of another person;</p>
<p>.That you will not download, store, post, distribute and provide access to, or in any other way use worms, viruses, trojans, and other malware;</p>
<p>.That you have a legal title to the items offered for sale in your announcement;</p>
<p>.That you have the necessary license or are otherwise authorized, as required by applicable law, to offer for sale, to advertise, and distribute goods described in your announcement.</p>
<p><strong>.ACCOUNT REGISTRATION</strong></p>
<p>In order to use certain features of the Service you may need to register an account on the Platform (the &ldquo;Account&rdquo;) and provide certain information about yourself as prompted by the registration form.</p>
<p>You may create an Account as an individual or as an authorized representative of a company.</p>
<p>You can register only one Account. If more than one person accesses its Account from the same device, we may request to upload the proof of identity to avoid duplicate accounts.</p>
<p>You acknowledge that you are solely responsible for safeguarding and maintaining the confidentiality of access details to your Account and that you are fully responsible and liable for any activity performed using your Account access details.</p>
<p>You hereby represent and warrant to us that you have reached the age of majority or are accessing the Service under the supervision of a parent or legal guardian. All users who are minors in the jurisdiction in which they reside (generally under the age of 18) must have the permission of, and be directly supervised by, their parent or guardian to use the Service. If you are a minor, you must have your parent or guardian read and agree to these Terms prior to you using the Service.</p>
<p>We reserve the right to suspend or terminate your Account, or your access to the Service, with or without notice to you, in the event that you breach these Terms.</p>
<p>You agree to immediately notify us of any unauthorized use, or suspected unauthorized use of your Account or any other breach of security. We cannot and will not be liable for any loss or damage arising from your failure to comply with the above requirements.</p>
<p><strong>.Abusing GaijinMall Services.</strong></p>
<p>&nbsp;Please use the flagging system to tell us about any problems or offensive content so that together we can keep the Services site working properly. We may limit or terminate our Services, remove hosted content and take technical and legal steps to keep users off GaijinMall if we think that they are creating problems or acting inconsistently with the letter or spirit of our policies. However, whether we decide to take any of these steps, remove hosted content or keep a user off GaijinMall or not, we do not accept any liability for monitoring GaijinMall or for unauthorized or unlawful content on GaijinMall or use of GaijinMall by users.</p>
<p><strong>.Idemnity </strong></p>
<p>The User agrees to indemnify and hold GaijinMall, its officers, subsidiaries, affiliates, successors, assigns, directors, officers, agents, service providers, suppliers and employees, harmless from any claim or demand, including reasonable attorney fees and court costs, made by any third party due to or arising out of content submitted by the user, users use of the service, violation of the Terms, breach by the user of any of the representations and warranties herein, or user&rsquo;s violation of any rights of another.</p>
<p><strong>.Disclaimers and Limitations of Liability.</strong> The Services are provided &ldquo;as is&rdquo; and &ldquo;as available&rdquo;. You agree not to hold us responsible for things other users post or do. IN NO EVENT SHALL WE (AND OUR AFFILIATES) BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY LOST PROFIT OR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES ARISING FROM THESE TERMS OR YOUR USE OF, OR INABILITY TO USE, THE SERVICE, OR THIRD-PARTY ADS, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. ACCESS TO, AND USE OF, THE SERVICE, AND THIRD-PARTY ADS ARE AT YOUR OWN DISCRETION AND RISK, AND YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTING SYSTEM OR LOSS OF DATA RESULTING THEREFROM.</p>
<p><strong>&nbsp;.CONTENT</strong></p>
<p>You understand that all postings, messages, text, files, images, photos, video, sounds, or other materials ("Content") posted on, transmitted through, or linked from the Service, are the sole responsibility of the person from whom such Content originated. More specifically, you are entirely responsible for each individual item ("Item") of Content that you post, email or otherwise make available via the Service.</p>
<p>You understand that GaijinMall does not control, and is not responsible for Content made available through the Service, and that by using the Service, you may be exposed to Content that is offensive, indecent, inaccurate, misleading, or otherwise objectionable.</p>
<p>Furthermore, the GaijinMall site and Content available through the Service may contain links to other websites, which are completely independent of GaijinMall.</p>
<p>GaijinMall makes no representation or warranty as to the accuracy, completeness or authenticity of the information contained in any such site. Your linking to any other websites is at your own risk. You agree that you must evaluate, and bear all risks associated with, the use of any Content, that you may not rely on said Content, and that under no circumstances will GaijinMall be liable in any way for any Content or for any loss or damage of any kind incurred as a result of the use of any Content posted, emailed or otherwise made available via the Service.</p>
<p>You acknowledge that GaijinMall does not pre-screen or approve Content, but that GaijinMall &nbsp;shall have the right (but not the obligation) in its sole discretion to refuse, delete or remove any Content that is available via the Service, for violating the Terms of Use or for any other reason.</p>
<p><strong>.INTELLECTUAL PROPERTY RIGHTS</strong></p>
<p>Information you submit to us as part of your registration, and any data, text, pictures and other materials that you may submit or post on the Platform (the &ldquo;User Content&rdquo;) remain your intellectual property, and the Administrator does not claim any ownership of the copyright or other proprietary intellectual property rights in such registration information and the User Content. Notwithstanding the foregoing, you agree that the Administrator may retain copies of all registration information and the User Content and use such information and the User Content as reasonably necessary for or incidental to its operation of the Service and as described in these Terms and the Privacy Policy.</p>
<p>You grant the Administrator the non-exclusive, worldwide, transferable, perpetual, irrevocable right to copy, modify, adapt, store, publish, distribute, publicly display and perform, communicate and make available to the public the User Content in connection with the Service as well as for the Administrator&rsquo;s marketing, advertising, and other purposes.</p>
<p>You agree, and represent and warrant, that your use of the Service, or any portion thereof, will neither infringe nor violate the rights of any other party or breach any contract or legal duty to any other parties.</p>
<p>Materials on the Platform, except those posted by the user, including but not limited to texts, software, scripts, graphics, photos, sounds, music, videos, interactive functions, etc. ("Materials") and trademarks, service marks and logos included in it ("Marks") belong to or are licensed by the Administrator representing items of copyright and of any other intellectual property rights. Any use of such Materials and Marks without prior notice of the Administrator is not allowed.</p>

<p><strong>.POSTING OF ANNOUNCEMENTS BY USERS</strong></p>
<p>A user shall provide to the Administrator any documents confirming the legitimacy of posting of announcements and identity documents upon the Administrator&rsquo;s request.</p>
<p>A user, who posts announcements with regard to sale of goods or services on the Platform, shall provide precise and complete information about such goods and services, as well as about the terms and conditions of sale and services provision.</p>
<p>The terms and conditions of sale and services provision developed by the user shall not interfere with these Terms and applicable laws.</p>
<p>Price of goods or services shall be exact. If it is perceived to be changed due to any specific circumstances, this shall be provided in the announcement.</p>



<p><strong>.NOTIFICATION OF CLAIMS OF INFRINGEMENT</strong></p>
<p>If you believe that your work has been copied in a way that constitutes &nbsp;GaijinMall&nbsp; copyright infringement, or your intellectual property rights have been otherwise violated, please notify GaijinMall 's agent for notice of claims of copyright or other intellectual property infringement ("Agent"), at support@ GaijinMall.com or Tokyo-To Taito-ku Ueno 6-10-7 Ameyoko plaza Freedom F88A. JAPAN</p>
<p>Please provide our Agent with the following Notice:</p>
<ol class="fs-md-2 fw-bold">
<li>a) Identify the material on the GaijinMall site that you claim is infringing, with enough detail and a link of the material so that we may locate it on the website;</li>
<li>b) A statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law;</li>
<li>c) A statement by you declaring under penalty of perjury that</li>
</ol>
<p>(1) the above information in your Notice is accurate, and</p>
<p>(2) that you are the owner of the copyright interest involved or that you are authorized to act on behalf of that owner;</p>
<ol class="fs-md-2 fw-bold">
<li>d) Your address, telephone number, and email address; and</li>
<li>e) Your physical or electronic signature. GaijinMall will remove the infringing posting(s</li>
</ol>
<p><strong>.Fees and Services</strong></p>
<p>Using GaijinMall is generally free, but we sometimes charge a fee for certain services.</p>
<p>For instance, we may set limits for publishing announcements in certain categories or offer advertising options for announcements on the Platform.</p>
<p>You are eligible for registering more than one Account if you use payable services on each of them.</p>
<p>The fees we charge for using payable services and payment methods accepted by us are disclosed on the Platform.</p>
<p>We reserve the right, in our sole discretion, to change the fees and acceptable payment methods from time to time and for any reason.</p>
<p>Unless otherwise explicitly provided by mandatory rules of the applicable law, the fees are non-refundable due to the nature of online services.</p>
<p>Your payments for the services are governed by the Billing Policy</p>

<p><strong>.DISCLAIMER OF WARRANTIES</strong></p>
<p>YOU AGREE THAT USE OF THE GaijinMall SITE AND THE SERVICE IS ENTIRELY AT YOUR OWN RISK. THE GaijinMall SITE AND THE SERVICE ARE PROVIDED ON AN "AS IS" OR "AS AVAILABLE" BASIS, WITHOUT ANY WARRANTIES OF ANY KIND. ALL EXPRESS AND IMPLIED WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT OF PROPRIETARY RIGHTS ARE EXPRESSLY DISCLAIMED TO THE FULLEST EXTENT PERMITTED BY LAW. TO THE FULLEST EXTENT PERMITTED BY LAW, GaijinMall F DISCLAIMS ANY WARRANTIES FOR THE SECURITY, RELIABILITY, TIMELINESS, ACCURACY, AND PERFORMANCE OF THE GaijinMall SITE AND THE SERVICE. TO THE FULLEST EXTENT PERMITTED BY LAW, GaijinMall DISCLAIMS A GaijinMall NY WA RRANTIES FOR OTHER SERVICES OR GOODS RECEIVED THROUGH OR ADVERTISED ON THE GaijinMall SITE OR THE SITES OR SERVICE, OR ACCESSED THROUGH ANY LINKS ON THE GaijinMall SITE. TO THE FULLEST EXTENT PERMITTED BY LAW, GaijinMall DISCLAIMS ANY WARRANTIES FOR VIRUSES OR OTHER HARMFUL COMPONENTS IN CONNECTION WITH THE GaijinMall SITE OR THE SERVICE.</p>
<p><strong>MISCELLANEOUS PROVISIONS</strong></p>
<p>Except as otherwise provided, if any provision of these Terms is held to be invalid, void, or for any reason unenforceable, such provision shall be struck out and shall not affect the validity and enforceability of the remaining provisions.</p>
<p>We may transfer and assign any and all of our rights and obligations under these Terms to any other person, by any way, including by novation, and by accepting these Terms you give us consent to any such transfer or assignment.</p>
<p>If we fail to take any action with respect to your breach of these Terms, we will still be entitled to use our rights and remedies in any other situation where you breach these Terms.</p>
<p>In no event shall the Administrator be liable for any failure to comply with these Terms to the extent that such failure arises from factors outside the Administrator's reasonable control.</p>
<p><strong>.Feedback</strong></p>
<p>You may be asked to leave a feedback when messaging with other users on Gaijin. Feedback consist of Positive, Neutral and Negative rating , select one and write reasons why you&rsquo;ve chosen selected feedback. Your submitted feedback, user name, profile photo (if available), and the name of the seller will be made public. User reviews should be left in good faith, ensuring that both you and the other user are depicting your interaction fairly. When leaving a feedback on another user&rsquo;s account, the feedback should:</p>
<ul class="fs-md-2 fw-bold">
<li>be truthful to what happened between you and the other user</li>
<li>not be left in an attempt to harass or abuse another user</li>
<li>not be in left in an attempt to manipulate or mislead other users</li>
</ul>
<p>To ensure the integrity of the review system, users are not able to edit or remove reviews on their own profile or someone else&rsquo;s. GaijinMall will also not mediate review-related disputes. User reviews reflect the opinions of individual GaijinMall users and do not reflect the opinion of GaijinMall.</p>
<p>We reserve the right to remove feedbacks which violate our policies or guidelines.</p>

<p><strong>.LAW GOVERNING THESE TERMS AND JURISDICTION </strong></p>
<p>These Terms shall be governed in accordance with the laws of Japan. The language to be used in the arbitral proceedings shall be Japanese.</p>
<p><strong>.CONTACT</strong></p>
<p>If you want to send any notice under these Terms or have any questions regarding our Service, you may contact us at: support@ GaijinMall.com</p>


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