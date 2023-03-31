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
/* 
<tr >
                          <td data-bs-dismiss="modal" aria-label="Close" style="position: relative;" class="ha-category-locate__filter" dvalue="mallAdLoc_Jekunp"><a href="javascript:void" class="text-dark">Zachary Taylor <span class="badge bg-purple ms-3">92428 Ad</span> <span class="ha-category-locate-modal__arrow"><i class="fa fa-angle-right fs-5"></i></span> </a></td>
                        </tr>
                      
                      <tr>
                        <td data-bs-dismiss="modal" aria-label="Close" style="position: relative;" class="ha-category-locate__filter" dvalue="mallAdLoc_Japnii"><a href="javascript:void" class="text-dark">Warren G. Harding <span class="badge bg-purple ms-3">92428 Ad</span> <span class="ha-category-locate-modal__arrow"><i class="fa fa-angle-right fs-5"></i></span></a></td>
                      </tr>
                      <tr>
                        <td data-bs-dismiss="modal" aria-label="Close" style="position: relative;" class="ha-category-locate__filter" dvalue="mallAdLoc_Ekini"><a href="javascript:void" class="text-dark">John Quincy Adams <span class="badge bg-purple ms-3">92428 Ad</span> <span class="ha-category-locate-modal__arrow"><i class="fa fa-angle-right fs-5"></i></span></a></td>
                      </tr> 
                      <span class="badge bg-purple ms-3">92428 Ad</span>*/
if (isset($_GET['filter_states'])){
    $stateID=$_GET['filter_states'];
  $stateID=$securityManager_ob->sanitizeItem($stateID,"string");
  $getCitiesResponse=$filter_ob->getAllLocationCity($stateID);
    if($getCitiesResponse['status']==1){
      foreach ($getCitiesResponse['message'] as $cityEach) {
          echo '<tr>
          <td data-bs-dismiss="modal" aria-label="Close" style="position: relative;" class="ha-category-locate__filter" dvalue="mallAdLoc_'.$cityEach['mallLocCity'].".".$cityEach['mallLocState'].'"><a href="javascript:void" class="text-dark">'.$cityEach['mallLocCity'].' <span class="ha-category-locate-modal__arrow"><i class="fa fa-angle-right fs-5"></i></span> </a></td>
        </tr>';
      } 
    }
    else{
        echo "<h1>No City found</h1>";
    }  
}
else{
    echo "<h1>Internal Error</h1>";
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
<script>
  $(function() {
          var $input = $("input[name='mark-keyword__input']");
          $context = $(".ha-cities__tbdata tr");
          $input.on("input", function() {
            var term = $(this).val();
            $context.show().unmark();
            if (term) {
              $context.mark(term, {
                done: function() {
                  $context.not(":has(mark)").hide();
                }
              });
            }
          });
        });
</script>