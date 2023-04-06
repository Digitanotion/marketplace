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
    <title>FAQs | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/translate.css">
    
</head>
<body>
<?php include "header-top.php";?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-md-4 col-lg-4 col-sm-12 rounded-3 p-0">
                  <div class="container-fluid border p-3 bg-primary">
                      <form>
                          <span><input class="form-control me-2 change01" onkeypress="change02()" type="search" placeholder="Search in Frequently Asked Questions" aria-label="Search in Frequently Asked Questions"></span>
                      </form>
                  </div>
                    <div class="" id="key01">
                        <div class="bg-white w-100 pt-3">
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md premium00" onclick="premium()" id="premium01">What are Promoted Ads?</span>
                                    </div>
                                </div>
                                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md premium00" onclick="limit01()" id="limit01">What Does "Ads limit" mean?</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md premium00" onclick="sell01()" id="sells01">How Can i Sell on Gaijinmall?</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md premium00" onclick="buy()">How to Buy Something on Gaijinmall</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md premium00">How to Leave Feedback About the Seller?</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md">How to report illegal activities?</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md">How Do I contact Support team?</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md">How to get paid for inviting people on Gaijinmall?</span>
                                    </div>
                                </div>
                                <hr class="bg-success mb-0">
                            </a>
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md">How to Buy Something on Gaijinmall</span>
                                    </div>
                                </div>
                                <hr class="bg-success">
                            </a>
                        </div>
                        <div class="px-3 fs-md w-100">
                            <p>POSTING AD</p>
                        </div>
                        <div class="bg-white w-100 pt-3">
                            <a href="#" class="text-dark">
                                <div class="px-3 my-1 bg-white">
                                    <div class="ha-profile-menu__items">
                                    <span class="fs-md">How to Buy Something on Gaijinmall</span>
                                    </div>
                                </div>
                                <hr class="bg-success">
                            </a>
                        </div>
                    </div>
                    </div>
            <div class="col-md-7 col-lg-7 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex ">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="header01">What are Premium Serives?</span>
                    </div>
                    <div class="text-center">
                        
                    </div>
                    
                </div>
                <hr class="m-0 bg-hr-light">
                <div class="">
                    <div class="ha-none__display w-50 justify-content-center mt-5 mx-auto" id="premiums">
                        <p><strong><a href="#" class="text-dark">Promoted Ads</a></strong> are specifically designed tools for the sellers that help to promote the items more.</p>
                        <p>As a result, you will sell all the goods much faster and get <strong>up to 100x more clients</strong></p>
                        <p>To learn more about the types of Premium Services we offer, go <strong><a href="https://gaijinmall.com/promotion_list.php">here</a></p></strong>

                    </div>
                </div>

                <div class="">
                    <div class="w-100 justify-content-center mt-5 px-4 mx-auto limits" id="limitation">
                        <p>Ads limit defines the number of available listings in a particular category.</p>
                        <p>You can check all the limits <a href="#">here</a>Choose the category that fits your product best and check the number of free listings available for it.</p>
                        <p>To learn more about the types of Premium Services we offer, go <strong><a href="#">here</a></p></strong>
                        <img src="./assets/images/img009.jpg" class="w-100" alt="images">
                        <p>If you reach your limit of free ads and still need to post more, choose the Premium Package that suits your needs and budget.</p>
                        <p>You can compare the number of free listings to the number of listings available for different packages <a href="#">here</a>right after choosing the category you’re interested in.</p>
                        <p>If you feel unsure of the Package to choose, request a call from the Gaijinmall Manager for recommendation.</p>
                        <p>We wish you huge sales!</p>
                    </div>
                </div>

                <div class="">
                    <div class="w-80 justify-content-center mt-5 px-5 mx-auto limits" id="sales01">
                        <p class="px-3 fs-6">Post your ads on Gaijinmall effortlessly by following the steps below:</p>
                        <ol class="incre01">
                            <li class="my-3"><strong>Sign in</strong> to your profile on Gaijinmall;</li>
                            <li class="my-3">Click on the Button <strong>"Sell"</strong> or just click <a href="#"> here</a></li>
                            <li class="my-3"><strong> Complete all the information.</strong>Choose a proper category, upload the photos of your item, and write a clear title with a detailed description of what you're selling. After that, enter a fair price and double-check the information you've entered;</li>
                            <li class="my-3">If everything looks fine, click on the <strong> "Post Ad"</strong>str button;</li>
                        </ol>
                        <p class="px-3 fs-6">As soon as you're done with all these steps,<strong>your ad goes for a review.</strong> We check it to ensure that it's in line with our rules and that all the information is correct. Once your advert is live, you will receive a notification email. If there's something wrong, we'll specify all the edits you should make.</p>
                        <p class="px-3 fs-6">That's all you need to do! Welcome to our great Gaijinmall Fam!</p>
                        <p class="px-3 fs-6">P.S. We also suggest you watch a short video guide for a better understanding<p>
                    </div>
                </div>

                <div class="w-80 justify-content-center mt-5 px-5 mx-auto limits" id="buy01">
                    <p class="px-3">To fully enjoy shopping on Gaijinmall, follow our simple guide:</p>
                    <ol>
                        <li>Search for the item</li>
                        <p>Use a search panel with filters and find what you need. We have over a million adverts, so you can choose exactly what you are looking for.</p>
                        <li>Contact a seller</li>
                        <p>You may chat on Gaijinmall or call a seller via phone and set up a meeting face to face, discuss some details or negotiate about the price.</p>
                        <li>Take your item or order a delivery</li>
                        <p>We check our sellers carefully, but it's always better to check twice, right? Meet with the seller in a public place and be sure to pay only after you get the item.
                        <li>Leave your feedback about the seller</li>
                        <p>Feel free to share your experience: write your feedback on the seller's page. Not sure how to do that? Check out the guide here. Other buyers will thank you one day </p>
                        </p>
                        <p>P.S. We also suggest you watch a short video guide for a better understanding</p>
                    </ol>
                </div>
            </div>
            
        </div>
    </section>
    <?php include "footer.php";?>

    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="./assets/js/userAdmin.js"></script>

    
</body>
</html>