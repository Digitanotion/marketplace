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
    <style>

      .plan-announce {
        margin-left: 1.5em;
        margin-top: 2em;
        margin-bottom: 5em;
      }
      @media (max-width: 806px) {
        .plan-announce {text-align: center; margin-left:0; margin-bottom: 2em;}
      }
      .select-hover {
        background: #f3faff;
      }

      .subject {
        max-width: 23em;
        margin-left: 2em;
        margin-top: 11em;
        background: none !important;
        border: none;
        color #fff !important;
        font-weight: bolder !important;
        
      }

      .subject ul .list-group-item {
        background: none !important;
        border: none !important;
      }
      


      .card.details {
        width: 10rem; 
        flex: 0 0 auto;
      }
      
  
      @media (max-width: 806px) {
         .card-wrapper{padding:0 !important;}
        .card.details {width: 90%;}
        .flex-lg-nowrap{ overflow-x: hidden;}
      }
      .card.details .card-header {
          font-size: 16px; 
          font-weight: bolder; background: skyblue;
          color: #fff;
          
      }



      .card-wrapper ul li:nth-child(1) {
         font-size: 15px;
         font-weight: bolder;
         padding: 15px 0;
      }

      .select-head {
          font-size: 25px; font-weight: bolder; background: #2a84eb; color: #fff;
      }
      .select-card {
        border:4px solid #2a84eb;
      }
      .hello li:hover ~ .list-group-flush li{
          background: #f3faff;
      }
      
    </style>

</head>
<body class="bg-light-blue">
    <?php include "header-top.php";?>
        <div class="wrapper">
          <div class="plan-announce">
              <h6><strong>Choose the plan in Others that works for you</strong></h6>
            </div>
          <div class="wrapper-container row justify-content-center justify-content-lg-around">
            <div class="card col-lg-3 d-none d-sm-none d-md-none d-lg-block  text-right mb-5 p-0 subject" >

              <ul class="list-group list-group-flush">
                <li class="list-group-item a">Extra Clients</li>
                <li class="list-group-item b">Ad Duration</li>
                <li class="list-group-item m">More</li>
                <li class="list-group-item c">Client Support</li>
                <li class="list-group-item d">Help center access</li>
              </ul>
            </div>

            <div class="card-wrapper col-12 col-lg-9 ">

              <div class="d-flex flex-wrap flex-lg-nowrap" style="" >
                  <?php 
                      $getAllPromoList=$adsManager_ob::getAllPromoList();
                      if ($getAllPromoList['status']==1) {
                        foreach ($getAllPromoList["message"] as $promoLists) { 
                  ?>
                  <div class="card shadow mb-4 mx-auto mx-lg-2 text-center details">
                    <div class="card-header p-3 fw-bold">
                     <?php echo $promoLists['mallAdPromoName'];
                        if ($promoLists['mallAdPromoName'] == "Business") {
                          $client = "2X";
                          $dur = "7 Days";
                          $supp = "Email Support";
                        } elseif ($promoLists['mallAdPromoName'] == "Bronze") {
                          $client = "7X";
                          $dur = "14 Days";
                          $supp = "Priority Email Support";
                        }
                        elseif ($promoLists['mallAdPromoName'] == "Silver") {
                          $client = "10X";
                          $dur = "1 Month";
                          $supp = "Phone & email Support";
                        }
                        elseif ($promoLists['mallAdPromoName'] == "Gold") {
                          $client = "13X";
                          $dur = "1.3 Months";
                          $supp = "Phone & email Support";
                        }
                        elseif ($promoLists['mallAdPromoName'] == "Diamond") {
                          $client = "16X";
                          $dur = "2 Months";
                          $supp = "Phone & email Support";
                        }
                        elseif ($promoLists['mallAdPromoName'] == "Executive") {
                          $client = "20X";
                          $supp = "Phone & email Support";
                        }
                     ?>
                    </div>
                    <ul class="list-group list-group-flush hello">
                      <li class="list-group-item first"><?php echo $adsManager_ob::CURRENCY . number_format($promoLists['mallAdPromoCost']);?></li>
                      <li class="list-group-item a"><?= $client; ?></li>
                      <li class="list-group-item b"><?= $dur; ?></li>
                      <li class="list-group-item m">
                        <form>                        
                          <select style="border: none; background: transparent; outline: none;">
                            <?php 
                            $getAllPromoListByName=$adsManager_ob::getAllPromoListByName($promoLists['mallAdPromoName']);
                            if ($getAllPromoList['status']==1){
                                foreach ($getAllPromoListByName["message"] as $otherValues) {
                                ?>
                            <option value="<?php echo $otherValues['mallAdPromoType']?>" promocost="<?php echo $otherValues['mallAdPromoCost'] ?>" promotype="<?php echo $promoLists['mallAdPromoType']?>">
                                <?php echo $otherValues['mallAdPromoDuration']?> days
                            </option>
                            <?php }
                            }
                          ?>
                          </select>
                        </form>
                      </li>
                      <li class="list-group-item c"><?= $supp; ?></li>
                      <li class="list-group-item d"><i class="fa fa-check text-success"></i></li>
                    </ul>
                 </div>

                <?php } } ?>
                
               </div>  <!-- card flex ends here -->

              </div>           

            </div>

          </div>
        </div>
        

    <?php include "footer.php"; ?>

   <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <!-- <script src="./assets/js/main.js"></script>d -->
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <!-- <script src="./assets/js/main.js"></script> -->

    <script>
      $(".card.details").click(function(){
        $('.card').removeClass("select-card");
        $(this).addClass("select-card");
        $('.card-header').removeClass("bg-primary").removeClass('text-light');
        $(this).find('.card-header').addClass("bg-primary").addClass('text-light');
      })
    </script>

    <script>
      $("li").hover(function(){

        var a = $(this).attr('class').split(' ').pop();
        $('.'+a).addClass('select-hover');
      }, function(){
        var a = $(this).attr('class').split(' ').pop();
        $('.'+a).removeClass('select-hover');
      })
    </script>

</body>
</html>






   