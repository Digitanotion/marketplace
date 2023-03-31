<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}  else if (strpos($url,'192.168')){
    require_once(__DIR__ . "\../vendor/autoload.php");
}
else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
} 
USE services\SecS\SecurityManager; 
USE services\AdS\AdManager; 
USE services\MedS\MediaManager;
USE services\InitDB;
$sys_msg=[];
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob=new SecurityManager();
$adManager_ob=new AdManager();
$mediaManager=new MediaManager();
if (isset($_GET['verify_token'])){
    $tokenData=$securityManager_ob->sanitizeItem($_GET['verify_token'],"string");
    if ($securityManager_ob->verifyToken($tokenData)){
        $sys_msg['msg_type']="1";
        $sys_msg['msg']="Email Verified";
        echo '<script>
        setTimeout(function() {
            window.location="'.MALL_ROOT.'";
        },4000)
    </script>';
    }
    else{
        $sys_msg['msg_type']=404;
        $sys_msg['msg']="Token does not exist or expired";
        echo MALL_ROOT;
        echo '<script>
        setTimeout(function() {
            window.location="'.MALL_ROOT.'";
        },4000)
    </script>';
    }
   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaijinmall | BUY or SELL your goods and services with ease </title>
    <link rel="stylesheet" href="../../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./../assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../assets/css/homepage.css">
    <link rel="stylesheet" href="./../assets/css/style.css">
    <link rel="stylesheet" href="./../assets/css/vertical-menu.css">
    <link rel="shortcut icon" href="./../assets/images/favicon.png">
    <meta name="Description" content="Gaijinmall is a classified marketplace, where you can sell your goods and services at ease.">
    <meta name="theme-color" content="#c3e6ff">
    <meta property="og:title" content="Gaijinmall | BUY or SELL your goods and services with ease " />
    <meta property="og:description" content="Gaijinmall is a classified marketplace, where you can sell your goods and services at ease." />
    <meta property="og:image" content="./assets/images/favicon.png">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:type" content="Classified Store" />
    <meta property="og:url" content="gaijinmall.com" />
    <meta property="og:site_name" content="Gaijinmall Marketplace" />

</head>
<body class="bg-light-blue">
    <div id="ha-header__top" class="row bg-light-blue px-0 px-md-5 px-lg-5 justify-content-center align-items-center fixed-top py-3 ">
    <!--IF NOT SIGNED IN SHOW THIS-->
    <div class="col-md-4 col-lg-4 col-4">
        <a href="../">
            <div class="logo" style="max-width: 130px;">
                <img src="../assets/images/logo-sm.png" class="img-fluid">
            </div>
        </a>


    </div>
    <div class="col-md-3 col-lg-3 col-1 text-center ha-social__top ">
        <div class="d-none d-md-block d-lg-block fs-6">
            COOL BUY & SELL
        </div>
    </div>
    <div class="col-md-5 col-lg-5 col-5 text-end text-dark ha-fs_7 nav-container fw-bolder">
        <?php
        if ($securityManager_ob->is_user_auth__()) { ?>
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <ul class="">
                    <li><a class="active ha-nav__item" href="../saved.php"><i class="fa fa-bookmark mx-auto"></i></a></li>
                    <li><a href="../messages.php" class="ha-nav__item"><i class="fa fa-envelope mx-auto"></i></a></li>
                    <li><a href="../notifications.php" class="ha-nav__item"><i class="fa fa-bell mx-auto"></i></a></li>
                    <li><a href="../adverts.php" class="ha-nav__item"><i class="fa fa-list mx-auto"></i></a></li>
                    <!-- <li class="dropdown">
                                <a href="#" class="ha-nav__profile" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">N</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li> -->
                    <li class="nav-item dropdown ha-dropdown__top">
                        <a class="ha-nav__profile" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user m-0"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../page.php">My Page</a></li>
                            <li><a class="dropdown-item" href="../feedback.php">Feedback</a></li>
                            <hr class="my-1">
                            <li><a class="dropdown-item" href="../messages.php">My messages</a></li>
                            <li><a class="dropdown-item" href="../personal_details_update.php">Settings</a></li>
                            <hr class="my-1">
                            <li><a class="dropdown-item fw-bolder" href="?logout=1">Log out</a></li>


                        </ul>
                    </li>
                </ul>
            </nav>



        <?php
        } else {
            echo '<a href="../Signin.php" class="text-dark">Sign in</a>  | <a href="../Signup.php" class="text-dark">Create Account</a>';
        }
        ?>

        <!--  -->
    </div>
    <!--IF NOT SIGNED IN SHOW THIS-->

</div>
    <header class="header">
            <!-- Image 1 -->
        <img src=".././assets/images/istockphoto-1000379636-612x612-removebg-preview.png" alt="header_pic" class="img-fluid header_parent-img-1">
        
            <!-- Center text -->
        <h3 class="header_text fs-4 align-items-center" style="z-index:1001;">
            <p >
                <div class="input-group ho-search__bar">
                    <input type="text" class="form-control fs-md-1 p-3 searchKey" onkeyup="" placeholder="Let's help you find it...">
                    <!-- <div class="input-group-btn">
                        <button class="btn btn-outline-primary p-2" type="submit">
                            <i class="fa fa-search m-0 p-0"></i>
                        </button>
                    </div> -->
                </div>
                <div class="bg-white border mt-1 fs-md-1 text-left fw-normal ha_search__result d-none">
                    
                </div>
            </p>
            Oop! The page you are looking for is not here.
        </h3>

            <!-- Image 2 -->
        <img src=".././assets/images/istockphoto-1224013294-612x612-removebg-preview 2.png" alt="header-pic-2" class="img-fluid header_parent-img-2">
    </header>

        <!-- Trending Ads -->
    <!-- <div class="Adspost py-5">
        <h1 class="Adspost_text">
           <b>Work in progress...</b> 
        </h1>
    </div> -->

    

    <!-- Footer section -->
     <!-- <button type="button" class="fw-button qa-fw-button fw-button--type-success fw-button--size-little fw-button--circle fw-button--has-icon">
                                                                    <span class="fw-button__content">
                                                                        <svg stroke-width="0" class="tab-saved-outlined" style="width: 19px; height: 19px; max-width: 19px; max-height: 19px; fill: rgb(61, 184, 58);">
                                                                        <use xlink:href="#tab-saved-outlined"></use>
                                                                        </svg> 
                                                                    </span>
                                                                </button>  -->
    <div class="row footer">
        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6  ">
                <div class="footer__sub-class ">
                    <ul class="footer__sub-class--list">
                    <li class="footer__sub-class--header"><h5>Navigation</h5></li>
                    <li><a href="../Signin.php">Sign in</a></li> 
                    <li><a href="../Signup.php">Register</a></li> 
                    <li><a href="../New_Ad.php">Sell</a></li>         
                    </ul>
                </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 offset-lg-1 offset-md-1">
            <div class="footer__sub-class ">
                <ul class="footer__sub-class--list">
                <li class="footer__sub-class--header"><h5>Support</h5></li>
                <li><a href="mailto:support@gaijinmall.com">Support@gaijinmall.com</a></li> 
                <li><a href="billing_policy.php">Billing policy</a></li>  
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="faqs.php">FAQ</a></li>      
                </ul>
            </div>
        </div>
            
        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 offset-lg-1 offset-md-1">
            <div class="footer__sub-class ">
                <ul class="footer__sub-class--list">
                <li class="footer__sub-class--header"><h5>Stay Connected</h5></li>
                <li><img src="./../assets/images/app_download_soon.png" class="img-fluid" width="85%"></li>
                <li><a href="#" class="social-media fa fa-facebook"> </a> <a href="#" class="social-media fa fa-twitter"> </a> <a href="#" class="social-media fa fa-instagram"> </a> </li>
                </ul>
            </div>
        </div>
        

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 offset-lg-1 offset-md-1">
            <div class="footer__sub-class">
                <ul class="footer__sub-class--list">
                <li class="footer__sub-class--header"><h5>Information</h5></li>
                <li><a href="about.php">About us</a></li> 
                <li><a href="cookie_policy.php">Cookie Policy</a></li>
                <li><a href="terms.php">Terms of service</a></li> 
                <li><a href="privacy_policy.php">Privacy policy</a></li>   
                </ul>
            </div>
            
        </div>    
        <div class="text-center fs-sm-1 mt-4 pe-5">  
            <p class="fs-md">© <?php echo date("Y") ?> GaiijinMall.com, All rights reserved</p>
        </div>
        <div class="area" >
            <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
            </ul>
    </div >

    
        
    </div>


    <script src="../../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="../../views/assets/js/vertical-menu.js"></script>
    <script src="../../views/assets/js/userAdmin.js"></script>
    <script src="../../views/assets/js/cute-alert.js"></script>
    <script>
       
       $(document).mouseup(function(e){
            var container = $('.ha_search__result');

              container.hide();
           
        });
        $('.ha-item-each__card').on("click",function(){
            adID=$(this).attr("datavalue");
            window.location="product.php?adID="+adID;
        })
       
       $(".searchKey").keyup(function () {
            var searchFormData = new FormData();
           var keyValue=$(this).val().trim();
           var resultPlaceholder=$('.ha_search__result');
           if (keyValue.length>2){
               searchFormData.append("searchAd",keyValue);
               $.ajax({
                    url: '../handlers/searchResults.php',
                    type: 'POST',
                    data: searchFormData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                       
                    },
                    success: function (data) {
                        if (!data.trim()){
                            resultPlaceholder.hide();
                        }
                        else{
                            
                            resultPlaceholder.removeClass('d-none');
                            resultPlaceholder.show();
                            $(".ha_search__result").html(data);
                        }
                        		
                    }
                });
           }
           else{
            resultPlaceholder.hide();
           }
       })
            $(document).ready(function onDocumentReady() {

                toastr.options = {
            //   "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            //   "preventDuplicates": false,
            //   "onclick": null,
            //   "showDuration": "300",
            //   "hideDuration": "1000",
            //   "timeOut": "5000",
            //   "extendedTimeOut": "1000",
            //   "showEasing": "swing",
            //   "hideEasing": "linear",
            //   "showMethod": "fadeIn",
            //   "hideMethod": "fadeOut"
            }
            <?php 
                if (isset($sys_msg) && !empty($sys_msg)){
                    switch ($sys_msg['msg_type']) {
                        case '1':
                            echo 'toastr.success("'.$sys_msg['msg'].'");';
                            break;
                        default:
                            echo 'toastr.error("'.$sys_msg['msg'].'");';
                            break;
                    }
                }
            ?>
            });
            </script>
</body>
</html>






   