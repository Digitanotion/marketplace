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
$filter_ob=new AdManager();
$securityManager_ob=new SecurityManager();
$mediaManager= new MediaManager();
$allAdCategByID=array();
$allAdCategOptions=array();
$categID="";
$categName="";
$getUsrInfo="";
if (isset($_GET['adcategory'])){
    $categID=$_GET['adcategory'];
  $categID=$securityManager_ob->sanitizeItem($_GET['adcategory'],"int");
  $allAdCategByID=$filter_ob->priceSortFilter($_GET['sortBy'], $_GET['filter_attribs'],$categID);
  $allAdCategOptions=$filter_ob->getCategOptionsByID($categID);
  $categInfo=$filter_ob->getCategInfoByID($categID);
    if($categInfo['status']==1){
      $categName=$categInfo['message']['mallCategName'];
      if ($allAdCategByID['status']!=1){
        //header("location: ".MALL_ROOT);
       ?>
            <div class="ha-none__display w-50 text-center m-5 mx-auto">
                            
                            <img class="img-fluid mx-auto mb-4 w-50" src="./assets/images/notfound.svg">
                            <div class="fs-title-4 fw-bolder">No listing found</div>
                            <div class="fs-md">No content availiable for this category</div>
                        </div>
       <?php
      }
      else{
        //$allAdCategByID=$allAdCategByID['message'];
        //$allAdCategByID=$securityManager_ob->getUserInfoByID($allAdCategByID['mallUsrID']);
        //$getUsrInfo=$getUsrInfo['message'];
                    if ($allAdCategByID['status']==1){ 
                          foreach ($allAdCategByID['message'] as $fields) {
                            $getImageCount=$filter_ob->countAdImagesByID($fields['mallAdID']);
                            $thumbImageName=$mediaManager->getThumbImage($fields['mallAdID']);
                            if ($thumbImageName['status']==1){
                                $thumbImageName=$thumbImageName['message']['mallMediaName'];
                            }
                            else{
                                $thumbImageName="";
                            }
                           ?>
                            <div class="row ha-category-items__wrapper mb-3">
                              <div class="col-4 rounded-start ha-category-item__image" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName?>');">
                              <?php 
                                  if (!empty($fields['mallAdCondition'])){
                                    echo '<span class="ha-category-item__title fs-6 text-light text-center fw-bold opacity-50">'.$fields['mallAdCondition'].'</span>';
                                  }
                                ?>
                                <span class="ha-card__counter"><span id="ha-counter__js"><?php echo $getImageCount['message'];?></span><i class="fa fa-camera ms-1"></i></span>
                                <a class="ha-card-content-icon-1 fw-bolder shadow-sm d-flex justify-content-center align-items-center" href="#">
                                  <i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i><i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i><i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i>
                                </a>
                              </div>
                              <a href="product.php?adID=<?php echo $fields['mallAdID'];?>" class="col-8 rounded-end bg-white text-dark">
                                <div class="">
                                  <div class="my-2 ">
                                    <span class="fs-title-1 fw-bolder"><?php echo $fields['mallAdTitle'] ?></span>
                                  </div>
                                  <div class="">
                                    <span class="ha-category-item__desc fs-md-1"><?php echo $fields['mallAdDesc'] ?></span>
                                  </div>
                                  <div class="mt-2 py-auto">
                                    <span class="badge bg-dark fs-6"><?php echo $filter_ob::CURRENCY.number_format($fields['mallAdPrice']); ?></span><br>
                                    <span class="badge bg-info fs-md mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> <?php $getlocationCateg=explode(".",$fields['mallAdLoc']); echo $getlocationCateg[0].", ",$getlocationCateg[1] ?>, Japan.</span>
                                  </div>
                                </div>
                              </a> 
                            </div>
                    <?php     } 
                    }
                    else{
                        echo "Not Found";
                    }
      }
    }
    else{
        echo "Category not found";
    }  
}
else{
    echo "Internal Error";
}
   /*  if (isset($_GET['categID'])){
        if ($allAdCategByID['status']==1){ 
            foreach ($allAdCategByID['message'] as $key) {
             ?>
              <div class="row ha-category-items__wrapper mb-3">
                <div class="col-4 bg-warning rounded-start ha-category-item__image" style="background-image: url('https://pictures-nigeria.jijistatic.com/86594826_MzAwLTIzOS00NTJlOWY4ZGVj.webp');">
                  <span class="ha-category-item__title fs-6 text-light text-center fw-bold opacity-50">Used</span>
                  <span class="ha-card__counter"><span id="ha-counter__js">3</span><i class="fa fa-camera ms-1"></i></span>
                  <a class="ha-card-content-icon-1 fw-bolder shadow-sm d-flex justify-content-center align-items-center" href="#">
                    <i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i><i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i><i class="fa fa-star mx-auto mx-1 fa-bounce text-warning"></i>
                  </a>
                </div>
                <a href="#" class="col-8 rounded-end bg-white text-dark">
                  <div class="">
                    <div class="my-2 ">
                      <span class="fs-title-1 fw-bolder">Moneypoint Pos Device dsfd dsf dsf dsf sfs dfsdfdsf sdf...</span>
                    </div>
                    <div class="">
                      <span class="ha-category-item__desc fs-md-1">Moneypoint Pos Device available for pick up and fast delivery within and outside lagos. Serves as...</span>
                    </div>
                    <div class="mt-2 py-auto">
                      <span class="badge bg-dark fs-6">N50,000  </span><br>
                      <span class="badge bg-info fs-md mt-2 mt-md-3 mt-lg-3"><i class="fa fa-map-marker m-0"></i> Lagos, Nigeria</span>
                    </div>
                  </div>
                </a> 
              </div>
          <?php } 
        } 
    } */

    ?>
