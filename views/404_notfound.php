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
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/homepage.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
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
    <?php include "header-top.php";?>
    <header class="header">
            <!-- Image 1 -->
        <img src="./assets/images/istockphoto-1000379636-612x612-removebg-preview.png" alt="header_pic" class="img-fluid header_parent-img-1">
        
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
        <img src="./assets/images/istockphoto-1224013294-612x612-removebg-preview 2.png" alt="header-pic-2" class="img-fluid header_parent-img-2">
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
    <?php include "footer.php"; ?>
        
    </div>


   <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <!-- <script src="./assets/js/main.js"></script>d -->
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <!-- <script src="./assets/js/main.js"></script> -->
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






   