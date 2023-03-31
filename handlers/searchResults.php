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
 USE services\AdS\AdManager;  
 USE services\SecS\SecurityManager;
 USE services\MedS\MediaManager;
$mediA=new MediaManager();
$adManager_ob=new AdManager();
if (isset($_POST['searchAd'])){
    $searchResult=$adManager_ob->searchAds($_POST['searchAd']);
    //echo print_r($searchResult);
    if ($searchResult['status']==1){
        foreach ($searchResult['message'] as $results) {
            $searchResultTitle=(strlen($results['mallAdTitle'])>29)? substr($results['mallAdTitle'],0,29):$results['mallAdTitle'];
            $thumbImageName=$mediA->getThumbImage($results['mallAdID']);
                                                if ($thumbImageName['status']==1){
                                                    $thumbImageName=$thumbImageName['message']['mallMediaName'];
                                                }
                                                else{
                                                    $thumbImageName="";
                                                }
            echo '<a href="product.php?adID='.$results['mallAdID'].'" class="text-dark">
            <div class="d-flex">
            <div class="ha-product-img__holder"><img src="../handlers/uploads/thumbs/'.$thumbImageName.'" class="img-fluid ha-search-result__image"></div>
            <div class="ps-1 p-3 ">
                '.$searchResultTitle.'
            </div>
            </div>
           </a>
           <hr class="m-0 bg-hr-light">';;
        }
    }
    
}
   
   