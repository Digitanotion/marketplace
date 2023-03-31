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
//error_reporting(0);
use services\AccS\AccountManager;
USE services\AdS\AdManager;  
 USE services\SecS\SecurityManager;
 USE services\MedS\MediaManager;
$mediA=new MediaManager();
$securiy_ob=new SecurityManager();
$accManager=new AccountManager();
if (isset($_POST['adImgFile'])){
    $adImgFile=$securiy_ob->sanitizeItem($_POST['adImgFile'],"string");
    $adImgUsrID=$securiy_ob->sanitizeItem($_POST['usrID'],"string");
    $adImgAdID=$securiy_ob->sanitizeItem($_POST['adID'],"int");
    $removeSpaceInFileName=explode(" ",$adImgFile);
    $removeSpaceInFileName=implode("_",$removeSpaceInFileName);
    $removeSpaceInFileName=$removeSpaceInFileName.mt_rand(1000,100000000);
    $imageResponse=$mediA->uploadOptiImage($_FILES["imageFile"],$removeSpaceInFileName,"uploads",$adImgUsrID,$adImgAdID);
    echo "<input type='hidden' id='json_images' class='json_images' value='".json_encode($imageResponse['message'])."'>";
    for ($i=0; $i < count($imageResponse['status']); $i++) { 
       if ($imageResponse['status'][$i]==1){
        echo '<div class="ha-imgUpload-each m-1" id="'.$imageResponse['mediaID'][$i].'" style="background: url(../handlers/uploads/thumbs/'.$imageResponse['message'][$i].');">
                <span class="ha-del-image__item" onclick="delImageUpload('.$imageResponse['mediaID'][$i].')">x</span>
                </div>';
       }
       else{
         echo '<div class="ha-imgUpload-each m-1 text-center info_tooltip" data-bs-toggle="tooltip" data-bs-placement="top" id="1'.$i.'01" title="'.$imageResponse['message'][$i].'" style="background: white">
         <span class="ha-del-image__item bg-danger text-white text-center" onclick="delImageUpload(1'.$i.'01)"> i</span>
         </div>';
         ?>
        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
        <?php
       }
    }
    
    // Pass a custom name, or it will be auto-generated
    //$image->setName($name);
   /*  $resMessages=explode("|",$imageResponse['message']);
    $resStatus=explode("|",$imageResponse['status']); */
    /* for ($i=0; $i < count($resMessages); $i++) { 
        if ($resStatus==1){
            echo $resMessages[$i];
            
        }
        else{
            echo '<div class="ha-imgUpload-each m-1" style="background: url(../handlers/uploads/thumbs/'.$resMessages[$i].');">
            <span class="ha-del-image__item">x</span>
            </div>';
        } */
    
   /*  '; */
   
}

if (isset($_POST['delReq'])){
    $mediaIDDel=$securiy_ob->sanitizeItem($_POST['delReq'],"int");
    $imageResponse=$mediA->delMedia($mediaIDDel);
}
if (isset($_POST['usrIDProfile'])){
    $imgUsrID=$securiy_ob->sanitizeItem($_POST['usrIDProfile'],"string");
    $usrImgFilename="usr_img_".$imgUsrID;
    $fileLocate=$accManager->paramsOb::MEDIA_STORE."/usrPictures";
    $imageResponse=$mediA->uploadOptiImage($_FILES["updateUsrIcon"],$usrImgFilename,$fileLocate,$imgUsrID,"");
    //echo "<input type='hidden' id='json_images' class='json_images' value='".json_encode($imageResponse['message'])."'>";
    for ($i=0; $i < count($imageResponse['status']); $i++) { 
       if ($imageResponse['status'][$i]==1){
        $updateUsrIconAcc=$accManager->updateUsrIcon($imgUsrID,$imageResponse['message'][$i]);
        echo "url('../media_store/usrPictures/thumbs/".$imageResponse['message'][$i]."')";
       }
       else{
        echo $imageResponse['message'][$i];
       }
    }
   
}

?>

