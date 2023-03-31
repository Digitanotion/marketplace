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
    <link rel="stylesheet" href="./assets/css/translate.css">
</head>
<body>
<?php include "header-top.php";?>
<section class="container-fluid m-0 p-0">
    <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between bg-white p-4">
        <div class="ha-cookie__content">
            <div class="pt-4 mx-auto">
                <h1><strong>Cookie policy</strong></h1>
                <p><strong>Gaijinmall</strong> may use cookies to provide better services.</p> 
                <p>Once you agree to allow our website to use cookies, you also agree to use the data it collects
                regarding your online behavior (analyze web traffic, web pages you spend the most time on, and
                websites you visit).</p>
                <p>The data we collect by using cookies is used to customize our website to your needs. After we use
                the data for statistical analysis, the data is completely removed from our systems.</p>
                <p>Please note that cookies don't allow us to gain control of your computer in any way. They are
                strictly used to monitor which pages you find useful and which you do not,  so that we can provide
                a better experience for you. If you want to disable cookies, you can do it by accessing the settings of your internet browser.
                </p>
                
            </div>

            <div class="  mx-auto">
                    <h3 class=""><strong>Cookies purposes</strong></h3>
                    <ol>
                        <p>The following purposes for Cookies are use:</p>
                            <ul class="px-0 fs-md-2 fw-bold">
                            
                            <li>To make our Service and the advertising displayed on it more relevant to your interests.</li>
                            <li>To measure advertising performance.</li>
                            <li>To prevent fraudulent activity and improve security.</li>
                            <li>To personalize the content of the Service.</li>
                            <li>To analyze the performance of the Service and fix bugs.To analyze the performance of the Service and fix bugs.</li>
                            <li>To speed up the loading of pages.</li>
                            <li>To recognize you the next time you visit our Service. As a result, the information, which you have earlier entered in certain fields on the Service, may automatically appear the next time you use our Service.</li>
                            <li>To recognize and count the number of visitors, to know which pages are the most and least popular, and to see how visitors move around our Service when they are using it. As a result, we will be able to improve the way our Service works, for example, by ensuring that users are finding what they are looking for easily.</li>

                            </ul>  
                                    <p class="">The detailed instructions on how to control your cookies through browser settings can also be found here: <a href="https://www.aboutcookies.org/how-to-control-cookies/.">https://www.aboutcookies.org/how-to-control-cookies/.</a></p>
                                    <p class="">Please note that blocking all cookies will have a negative impact upon the usability of many services. If you block cookies, you will not be able to use all the features on our Service.</p>
                                    <p class="">To opt out of data usage by Google Analytics follow instructions: <a href="https://tools.google.com/dlpage/gaoptout">https://tools.google.com/dlpage/gaoptout.</a></p>
                                    <p class="">To reset your device identifier or opt-out of personalized advertising, follow <a href="#">Goggle instructions</a> or <a href="#">Apple instructions</a> </p>
                                                  

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
                <p class="fw-bold">Last updated: 25 May 2022</p>
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