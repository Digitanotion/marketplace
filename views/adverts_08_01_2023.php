<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
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
USE services\AccS\AccountManager;
$securityManager_ob=new SecurityManager();
$adsManager_ob=new AdManager();
$usrAccManager_ob=new AccountManager();
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$sys_msg=[];
if (!$securityManager_ob->is_user_auth__()){
    header("location: ".MALL_ROOT."/Signin.php");
}
if (isset($_GET['logout'])&&$_GET['logout']==1){
    if ($securityManager_ob->endUserSession()){
        header("location: ".MALL_ROOT);
    }
    else{
        $sys_msg['msg_type']=500;
        $sys_msg['msg']="Could not log out";
    }
} 

$pageUsrID__=$_SESSION['gaijinmall_user_'];
$getUsrInfo=$usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrBizInfo="";
if (isset($_GET['view_business'])){
    $getUsrBizInfo=$usrAccManager_ob->getUsrBizInfoByID($_GET['view_business'])['message'];
}
else{
    $getUsrBizInfo=$usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
}
if (isset($_POST['promoteAd_btn']) && isset($_POST['mallAdPromoID'])){
    $promoteAdEndP=$adsManager_ob->recievePromoPayment($_POST['mallAdPromoID'],$_POST['promoteAd_CustEmail'],$_POST['promoteAd_AdCustID']);
}

if(isset($_POST['adIDForDelete_btn'])){
    $adIDForDelete=$_POST['adIDForDelete'];
    $adsManager_ob->delAdNow($adIDForDelete);
    $sys_msg['msg_type']=1;
        $sys_msg['msg']="Ad Deleted Successfully";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Adverts | Gaijinmall</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    
</head>
<body>
<?php include "header-top.php";?>
    <section class="container-fluid m-0 p-0">
        <div class="row m-0 mx-sm-5 mx-md-5 mx-lg-5 mt-2 gx-0 gx-md-5 gx-lg-5 justify-content-between">
        <?php include "sidebar.php";?>
            <div class="col-md-8 col-lg-8 col-sm-12 p-0 shadow-sm bg-white rounded-3 p-0">
                <div class="px-4 py-4 d-flex justify-content-between">
                    <div class="">
                        <span class="fs-title-3 fw-bolder" id="prof1">Adverts</span>
                    </div>
                    <div class="text-center">
                       
                        <span class="btn text-primary fs-md p-2 acts0" onclick="myActv()" id="actvs"> Active <span class="ha-count__Active badge bg-primary"><?php echo $adsManager_ob->getAllAdsCountByUsrID($pageUsrID__,"active")['message'];?></span></span>
                        <div class="vr"></div>
                        <span class="btn text-secondary fs-sm p-2" onclick="myRev1()" id="revws"> In Review <span class="ha-count__Active badge bg-secondary"><?php echo $adsManager_ob->getAllAdsCountByUsrID($pageUsrID__,"inreview")['message'];?></span></span>
                        <div class="vr"></div>
                        <span class="btn text-danger fs-sm p-2" onclick="myDecl()" id="decls"> Declined <span class="ha-count__Active badge bg-danger"><?php echo $adsManager_ob->getAllAdsCountByUsrID($pageUsrID__,"declined")['message'];?></span></span>
                        <span class="btn text-secondary fs-sm p-2 feedsico" id="feedsicon0" onclick="myExpiredvd()"> Expired <span class="ha-count__Active badge bg-secondary"><?php echo $adsManager_ob->getAllAdsCountByUsrID($pageUsrID__,"expired")['message'];?></span></span>
                        
                    </div>
                    
                </div>
                <hr class="m-0 bg-hr-light">
                <input type="hidden" value="<?php echo $pageUsrID__?>" id="ha-userID" name="ha-userID">
                <div class="ha-profile-url-data__body mx-4 my-2" >
                    
                </div>
            </div>
            
        </div>
    </section>
    <!-- Boost Ad -->
<div class="modal fade" id="promoteThisAd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="" method="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Promote this Ad</h5>
      </div>
      <div class="modal-body">
        <div class="promoteAdContainer row justify-content-center">
                            <p class="sell-fs-base sell-text-color text-center text-muted text-center fs-md" style="">Please, choose one of the following plans to promote your ad</p>
                            
                             <?php 
                                $getAllPromoList=$adsManager_ob::getAllPromoList();
                                if ($getAllPromoList['status']==1){
                                    foreach ($getAllPromoList["message"] as $promoLists) {
                                    ?>
                                    <div class="sell-promote-ad-select p-2 w-75 mb-2" style="border:2px solid rgba(13, 110, 253, 0.4);">
                                        <p class="fw-bold fs-5" style=""><?php echo $promoLists['mallAdPromoName'];?> </p>
                                        <div class="d-flex">
                                            <div>
                                                <?php 
                                                $getAllPromoListByName=$adsManager_ob::getAllPromoListByName($promoLists['mallAdPromoName']);
                                                if ($getAllPromoList['status']==1){
                                                    foreach ($getAllPromoListByName["message"] as $otherValues) {
                                                    ?>
                                                <input type="radio" class="btn-check " promocost="<?php echo $otherValues['mallAdPromoCost'] ?>" promotype="<?php echo $promoLists['mallAdPromoType']?>" promooldcost="<?php echo $otherValues['mallAdPromoOldCost'] ?>" name="mallAdPromoID" id="<?php echo $otherValues['mallAdPromoType']?>" autocomplete="off" value="<?php echo $otherValues['mallAdPromoType']?>"><label class="btn btn-outline-primary fs-md" for="<?php echo $otherValues['mallAdPromoType']?>"><?php echo $otherValues['mallAdPromoDuration']?> days</label>
                                                <?php }
                                                }
                                                ?>
                                            </div>
                                            <div class="d-flex justify-content-end flex-grow-1">
                                                <p class="pe-3 sell-fs-base fs-title fw-bold"><?php echo $adsManager_ob::CURRENCY;?><span class="<?php echo $promoLists['mallAdPromoType']?>"><?php echo number_format($promoLists['mallAdPromoCost']);?></span> <br>
                                            <span class="badge bg-danger text-decoration-line-through fs-md"><?php echo $adsManager_ob::CURRENCY;?><span class="old_<?php echo $promoLists['mallAdPromoType']?>"><?php echo number_format($promoLists['mallAdPromoOldCost']);?></span></span></p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                }
                            else{
                                echo "<div class='alert bg-info'>No Promo list found, Don't worry we are working on it.</div>";
                            }
                            ?>
                           
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-grey" data-bs-dismiss="modal">Close</button>
        <input type="hidden" name="promoteAd_CustEmail" value="<?php echo $getUsrInfo['mallUsrEmail']?>">
        <input type="hidden" name="promoteAd_AdCustID" id="promoteAd_AdCustID" value="<?php echo $getUsrInfo['mallUsrID']?>">
        <button type="submit" class="btn btn-primary" name="promoteAd_btn" disabled id="promoteAd_btn">Proceed to Pay</button>
      </div>
    </form>
  </div>
</div>
<div id="deleteAdModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<form class="modal-content" action="" method="post">
			<div class="modal-header flex-column">
				<div class="icon-box">
					<i class="material-icons">&#xE5CD;</i>
				</div>						
				<h4 class="modal-title w-100">Are you sure?</h4>	
			</div>
			<div class="modal-body">
				<p>Do you really want to delete these records? This process cannot be undone.</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <input type="hidden" id="adIDForDelete" name="adIDForDelete">
				<button type="submit" class="btn btn-danger" name="adIDForDelete_btn">Delete</button>
			</div>
        </form>
	</div>
</div>     
    <?php include "footer.php";?>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <script src="assets/js/userAdmin.js"></script>
    <script>
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
            if (isset($sys_msg) && !empty($sys_msg)) {
                switch ($sys_msg['msg_type']) {
                    case '1':
                        echo 'toastr.success("' . $sys_msg['msg'] . '");';
                        break;
                    default:
                        echo 'toastr.error("' . $sys_msg['msg'] . '");';
                        break;
                }
            }
            ?>
        });
        myActv();
        </script>
</body>
</html>