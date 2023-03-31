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
    <title>Our Cookie Policy | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<?php include "header-top.php";?>
<section class="container-fluid m-0 p-0">
    <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between bg-white p-4">
        <div class="ha-cookie__content">
            <div class="pt-4 mx-auto">
                <h1><strong>Cookie policy</strong></h1>
                <p>This cookie policy explains how we use cookies and other similar technologies when you use our Service.
                It also explains how you can control their use. Your continued browsing of the Service indicates your consent to our  of cookies.
                </p>
                <p><strong>Gaijinmall Online Marketplace Nigeria Limited</strong> is an entity responsible for the use of cookies on theService. If you have any questions, you can contact us at <a href="#"> support@gaijinmall.com.</a>
                </p>
                <p>Cookies</p>
                <p>A cookie is a small text file which is placed onto your device (eg computer, smartphone, or other electronic device)when you visit our Service to store a range of information, for example, your language preference, or browser and deviceyou are using to view the website or the app. Those cookies are set by us and called first-party cookies.
                We also use third-party cookies, which are cookies from a domain different that the domain of our Service, for ouradvertising and marketing efforts.
                </p>
                <p>Session cookies expire each time you close your browser and do not remain on your device afterwards. These cookies allowour website to link your actions during a particular browser session.</p>
                <p>Persistent cookies will remain on your device for a period of time and will expire on a set expiration date, or when youdelete them manually from your cache.
                These cookies are stored on your device in between browser sessions and allow your preferences and actions across ourService to be remembered.
                </p>
            </div>

            <div class="  mx-auto">
                    <p class=""><strong>How can you manage your cookies and similar technologies?</strong></p>
                    <ol>
                        <strong><li>Browser and device setting</li></strong>
                        <p>Most browsers allow you to refuse to accept cookies and to delete cookies. The methods for doing so vary from browser to browser, and from version to version. You can however obtain up-to-date information about blocking and deleting cookies via these links:</p>
                            <ul class="px-0 fs-md-2 fw-bold">
                                <li><a href="#">Chrome</a></li>
                                <li><a href="#">Firefox</a></li>
                                <li><a href="#">Opera</a></li>
                                <li><a href="#">Internet explorer</a></li>
                                <li><a href="#">Safari</a></li>
                                <li><a href="#">Edge</a></li>
                            </ul>  
                                    <p class="">The detailed instructions on how to control your cookies through browser settings can also be found here: <a href="https://www.aboutcookies.org/how-to-control-cookies/.">https://www.aboutcookies.org/how-to-control-cookies/.</a></p>
                                    <p class="">Please note that blocking all cookies will have a negative impact upon the usability of many services. If you block cookies, you will not be able to use all the features on our Service.</p>
                                    <p class="">To opt out of data usage by Google Analytics follow instructions: <a href="https://tools.google.com/dlpage/gaoptout">https://tools.google.com/dlpage/gaoptout.</a></p>
                                    <p class="">To reset your device identifier or opt-out of personalized advertising, follow <a href="#">Goggle instructions</a> or <a href="#">Apple instructions</a> </p>
                                                  

                        <strong><li>Opt-out of internet-based advertising</li></strong>
                            <p>The third-party advertisers, ad agencies and vendors with which we work may be members of the Network Advertising Initiative, the Digital Advertising Alliance Self-Regulatory Program for Online Behavioral Advertising, the European Digital Advertising Alliance. To opt-out of interest-based advertising from the participating companies, visit the following links:</p>
                            <ul class="px-0 pb-4">
                                <li>Network Advertising Initiative-<a href=" http://optout.networkadvertising.org/">http://optout.networkadvertising.org/</a></li>
                                <li>Digital Advertising Alliance – <a href="http://optout.aboutads.info/">http://optout.aboutads.info/</a></li>
                                <li>Digital Advertising Alliance (Canada) – <a href="http://youradchoices.ca/choices">http://youradchoices.ca/choices</a></li>
                                <li>Digital Advertising Alliance (EU) –  <a href="http://www.youronlinechoices.com/">http://www.youronlinechoices.com/</a></li>
                                <li>DAA AppChoices page – <a href="http://www.aboutads.info/appchoices">http://www.aboutads.info/appchoices</a></li>

                                <p class="pt-4"><Strong>Cookies purposes</Strong></p>
                                <p>Cookies are used by us for the following purposes:</p>
                                <li>To speed up the loading of pages</li>
                                <li>To recognize you the next time you visit our Service. As a result, the information, which you have earlier entered in certain fields on the Service may automatically appear the next time you use our Service.</li>
                                <li>To recognize and count the number of visitors, to know which pages are the most and least popular, and to see how visitors move around our Service when they are using it. As a result, we will be able to improve the way our Service works, for example, by ensuring that users are finding what they are looking for easily.</li>
                                <li>To make our Service and the advertising displayed on it more relevant to your interests.</li>
                                <li>To measure advertising performance.</li>
                                <li>To prevent fraudulent activity and improve security.</li>
                                <li>To personalize the content of the Service.</li>
                                <li>To analyze the performance of the Service and fix bugs.</li>
                            </ul>
                    </ol>
            </div>

            <div class=" px-5 pt-4 mx-auto">
                <!-- <p><strong>Cookies list</strong></p>
                <p>You can find more information about the individual cookies we use and the purposes for which we use them in the table below:</p>
                <table class="table">
                   <tbody>
                       <tr>
                           <td>
                               <p><strong>Cookies</strong></p>
                           </td>
                           <td>
                               <p><strong>Type</strong></p>
                           </td>
                           <td>
                               <p><strong>Description</strong></p>
                           </td>
                       </tr>
                       <tr>
                           <td>
                               <p>_gid</p>
                           </td>
                           <td>
                               <p>First-party</p>
                           </td>
                           <td><p>This cookie is set by Google Analytics. It stores and updates a unique value for each page visited and is used to count and track pageviews.</p></td>
                       </tr>
                       <tr>
                        <td>
                            <p>_ga</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>This cookie name is associated with Google Universal Analytics - which is a significant update to Google's more commonly used analytics service. This cookie is used to distinguish unique users by assigning a randomly generated number as a client identifier. It is included in each page request in a site and used to calculate visitor, session, and campaign data for the site's analytics reports.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>uid</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>This cookie provides a uniquely assigned, machine-generated user ID and gathers data about activity on the website. This data may be sent to a 3rd party for analysis and reporting.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>Personalisation id</p>
                        </td>
                        <td>
                            <p>Third party</p>
                        </td>
                        <td><p>This cookie carries out information about how the end user uses the website and any advertising that the end user may have seen before visiting the said website.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>IDE</p>
                        </td>
                        <td>
                            <p>Third party</p>
                        </td>
                        <td><p>This cookie is set by Doubleclick and carries out information about how the end user uses the website and any advertising that the end user may have seen before visiting the said website.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>MUID</p>
                        </td>
                        <td>
                            <p>Third party</p>
                        </td>
                        <td><p>This cookie is widely used by Microsoft as a unique user identifier. It can be set by embedded microsoft scripts. Widely believed to sync across many different Microsoft domains, allowing user tracking.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>ANONCHK</p>
                        </td>
                        <td>
                            <p>Third party</p>
                        </td>
                        <td><p>This cookie carries out information about how the end user uses the website and any advertising that the end user may have seen before visiting the said website.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>_gat_gtag_UA_137059257_1</p>
                        </td>
                        <td>
                            <p>first-party</p>
                        </td>
                        <td><p>This cookie is part of Google Analytics and is used to limit requests (throttle request rate).</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>CLID</p>
                        </td>
                        <td>
                            <p>Third-party</p>
                        </td>
                        <td><p>This cookie is usually set by Dstillery to enable sharing media content to social media. It may also gather information on website visitors when they use social media to share website content from the page visited.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>test_cookie</p>
                        </td>
                        <td>
                            <p>Third-party</p>
                        </td>
                        <td><p>This cookie is set by DoubleClick (which is owned by Google) to determine if the website visitor's browser supports cookies.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>SRM_B</p>
                        </td>
                        <td>
                            <p>Third-party</p>
                        </td>
                        <td><p>This is a Microsoft MSN 1st party cookie that ensures the proper functioning of this website.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>_gcl_au</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Google AdSense for experimenting with advertisement efficiency across websites using their services.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>SM</p>
                        </td>
                        <td>
                            <p>Third-party</p>
                        </td>
                        <td><p>This is a Microsoft MSN 1st party cookie that we use to measure the use of the website for internal analytics.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>fbp</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Facebook to deliver a series of advertisement products such as real-time bidding from third-party advertisers.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>_ga_V7SNPVJK6G</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>rid</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p></p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>app</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>_clck</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>_clsk</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>_js2</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>app_sid</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>first_visit</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                    <tr>
                        <td>
                            <p>muc_ads</p>
                        </td>
                        <td>
                            <p>First-party</p>
                        </td>
                        <td><p>Used by Gaijinmall to facilitate website operation.</p></td>
                    </tr>
                   </tbody>
                </table> -->

                <p class="mt-4"><strong>Changes to this policy</strong> </p>
                <p>We may change this policy from time to time, when we do, we will inform you by updating the “Last updated” date below.</p>
                <p class="fw-bold">Last updated: 3 February 2022</p>
            </div>
        </div>
    </div>
    <hr class="m-0 bg-hr-light my-5">


</section>
<?php include "footer.php";?>

<script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
<script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
</body>
</html>