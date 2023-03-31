<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
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

$adsManager_ob=new AdManager();

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
    <link rel="stylesheet" href="./assets/css/translate.css">
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

        <div class="flex-wrapper d-flex flex-column justify-content-between" style="min-height: 100vh;">
            <div class="container mx-auto my-auto">
                <div class="flex-wrapper d-md-flex justify-content-between mt-5">
                    <div class="p-0 d-none d-lg-block" style="max-width: 500px;">
                        <img src="./assets/images/istockphoto-1000379636-612x612-removebg-preview.png" alt="header_pic" class="img-fluid">
                    </div>
                    <div class="table-responsive flex-lg-grow-1">
                        <?php 
                           $info = isset($_GET["promo_name"]) ? "Details for {$_GET["promo_name"]}" : "Check out our promotions"
                        ?>
                        <h5 class="text-center text-secondary"><?php echo $info ?></h5>
                        <table class="table table-hover shadow p-5 mb-5 bg-body rounded table-bordered text-center text-muted text-center">
                          <thead class="" style="font-size: 14px;">
                            <tr class="text-secondary fw-100">
                              <th scope="col">S/N</th>
                              <th scope="col">Name</th>
                              <th scope="col">Duration</th>
                              <th scope="col">Cost</th>
                              <th scope="col">Old Cost</th>
                            </tr>
                          </thead>
                          <tbody class="" style="font-size: 14px;">

                            <?php 

                                $id = 1;
                                if (!isset($_GET["promo_name"])) {
                                     $getAllPromoList=$adsManager_ob::getPromoList();
                                 if ($getAllPromoList['status']==1) { ?>
                                    <?php 
                                        
                                        foreach ($getAllPromoList["message"] as  $promo) {?>
                                            <tr>
                                              <th scope="row"><?php echo $id++ ?></th>
                                              <td><?php echo $promo["mallAdPromoName"] ?></td>
                                              <td><?php echo $promo["mallAdPromoDuration"] ?> Days</td>
                                              <td><?php echo $adManager_ob::CURRENCY . number_format($promo["mallAdPromoCost"]) ?></td>
                                              <td> <?php echo $adManager_ob::CURRENCY . number_format($promo["mallAdPromoOldCost"]) ?></td>
                                            </tr>
                                    <?php }

                                    ?>
                                
                                
                            <?php } 


                             }  elseif (isset($_GET["promo_name"])) {
                                    $getAllPromoList=$adsManager_ob::getAllPromoListByName($_GET["promo_name"]);
                                    if ($getAllPromoList['status']==1) {
                                        foreach ($getAllPromoList["message"] as  $promo) {?>
                                            <tr>
                                              <th scope="row"><?php echo $id++ ?></th>
                                              <td><?php echo $promo["mallAdPromoName"] ?></td>
                                              <td><?php echo $promo["mallAdPromoDuration"] ?> Days</td>
                                              <td><?php echo $adManager_ob::CURRENCY . number_format($promo["mallAdPromoCost"]) ?></td>
                                              <td> <?php echo $adManager_ob::CURRENCY . number_format($promo["mallAdPromoOldCost"]) ?></td>
                                            </tr>
                                    <?php }
                                     }  
                                }?>
                            
                          </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <?php include "footer.php"; ?>
        </div>
        
    <!-- </div> -->


   <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <!-- <script src="./assets/js/main.js"></script>d -->
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <!-- <script src="./assets/js/main.js"></script> -->

</body>
</html>






   