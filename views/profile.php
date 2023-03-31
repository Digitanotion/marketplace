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
$securityManager_ob=new SecurityManager();

/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg=[];
/* if (!$securityManager_ob->is_user_auth__()){
    header("location: ".MALL_ROOT."/Signin.php");
} */
if (isset($_GET['logout'])&&$_GET['logout']==1){
    if ($securityManager_ob->endUserSession()){
        header("location: ".MALL_ROOT);
    }
    else{
        $sys_msg['msg_type']=500;
        $sys_msg['msg']="Could not log out";
    }
} 



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My profile | Gaijinmall</title>
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
</head>
<body>
    <!--  -->
    <?php include "header-top.php";?>
    
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
            <div class="col-md-3 col-lg-3 col-sm-12 shadow-sm bg-white rounded-3 p-0">
                <div class="px-3 ha-profile__section mb-4">
                    <div class="ha-image-profile__holder mx-auto my-3">
                        <img class="img-fluid mx-auto" src="./assets/images/testImages.png">
                    </div>
                    <div class="mx-auto text-center">
                        <span class="fs-title-2 fw-bolder">Digitanotion Systems</span>
                    </div>
                    <div class="mx-auto text-center my-1">
                        <span class="fs-md-1 badge bg-secondary">080662289879</span>
                    </div>
                </div>
                <hr class="bg-light mb-0">
                <div class="bg-light-blue w-100 pt-3">
                    <a href="#" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                            <span class="fs-md"><i class="fa fa-list fs-title-2 "></i> My Adverts</span>
                            <span class="badge bg-info">1</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                            <span class="fs-md"><i class="fa fa-smile-o  fs-title-2 fw-bold"></i> Feedback</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                            <span class="fs-md"><i class="fa fa-money fs-title-2 fw-bold"></i> Y0 </span><span class="badge bg-dark ">My Balance</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                            <span class="fs-md"><i class="fa fa-bar-chart  fs-title-2 fw-bold"></i> Perfomance</span>
                            </div>
                        </div>
                    </a>
            
                </div>

                <div class="bg-light-blue w-100 pt-3">
                    <a href="#" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                            <span class="fs-md"><i class="fa fa-phone fs-title-2 "></i>Request a call</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                            <span class="fs-md"><i class="fa fa-question-circle fs-title-2 fw-bold"></i> FAQs</span>
                            <span class="badge bg-danger">New</span>
                            </div>
                        </div>
                    </a>
                    
                </div>
                
            </div>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder">Profile</span>
                    </div>
                    <div class="text-center">
                        <span class="btn text-primary fs-md p-2"> Active <span class="ha-count__Active badge bg-primary">0</span></span>
                        <div class="vr"></div>
                        <span class="btn text-secondary fs-sm p-2 "> In Review <span class="ha-count__Active badge bg-secondary">0</span></span>
                        <div class="vr"></div>
                        <span class="btn text-danger fs-sm p-2 "> Declined <span class="ha-count__Active badge bg-danger">0</span></span>
                    </div>
                    
                </div>
                <hr class="m-0 bg-hr-light">
                <div class="ha-profile-url-data__body">
                    <div class="ha-none__display w-50 text-center m-5 mx-auto">
                        <img class="img-fluid mx-auto" src="./assets/images/notfound3.svg">
                        <div class="fs-title-4 fw-bolder">No active Ad found</div>
                        <div class="fs-md">No content availiable</div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <?php include "footer.php";?>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    
</body>
</html>