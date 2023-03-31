<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
  require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} else if (strpos($url, '192.168')) {
  require_once(__DIR__ . "\../vendor/autoload.php");
} else {
  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}

use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\MedS\MediaManager;
use services\InitDB;
use services\AudS\AuditManager;

$sys_msg = [];
$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$mediaManager = new MediaManager();
$audService_ob = new AuditManager();
/* if (!$securityManager_ob->is_user_auth__()){
  header("location: Signin.php");
} */
$adID = "";
/*
$usrID=$_SESSION['gaijinmall_user_'];
$getCurrentUserInfo=$securityManager_ob->getUserInfoByID($usrID);
$getUsrInfo="";
if ($getUsrInfo['status']!=1){
  header("location: Signin.php");
}
else{
  $getUsrInfo=$getUsrInfo['message'];
}*/
$allAdCategByID = array();
$allAdCategOptions = array();
$categID = "";
$categName = "";
$getUsrInfo = "";
if (isset($_GET['adcategory'])) {
  $categID = $securityManager_ob->sanitizeItem($_GET['adcategory'], "int");
  $allAdCategByID = $adManager_ob->getAdByCategID($categID);
  $allAdCategOptions = $adManager_ob->getCategOptionsByID($categID);
  $categInfo = $adManager_ob->getCategInfoByID($categID);
  if ($categInfo['status'] == 1) {
    $categName = $categInfo['message']['mallCategName'];
    if ($allAdCategByID['status'] != 1) {
      //header("location: ".MALL_ROOT);
    } else {
      //$allAdCategByID=$allAdCategByID['message'];
      //$allAdCategByID=$securityManager_ob->getUserInfoByID($allAdCategByID['mallUsrID']);
      //$getUsrInfo=$getUsrInfo['message'];
    }
  } else {
    //header("location: ".MALL_ROOT);
  }
} else {
  //header("location: ".MALL_ROOT);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $categName; ?> </title>
  <meta name="theme-color" content="#c3e6ff">
  <link rel="shortcut icon" href="./assets/images/favicon.png">
  <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <link rel="stylesheet" href="./assets/fonts/inter/style.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/category.css">
  <link rel="stylesheet" href="./assets/css/vertical-menu.css">
</head>

<body class="bg-white">

  <div class="container p-0 p-md-0 mx-auto">
    <!--  <div class="row m-0 justify-content-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">All</a></li>
                  <li class="breadcrumb-item"><a href="#">Agriculture & Equipments</a></li>
                  <li class="breadcrumb-item"><a href="#">Farm tools</a></li>
                  <li class="breadcrumb-item"><a href="#">Point of Sale</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data</li>
                </ol>
              </nav>
        </div> -->
    <div class="row mx-2 mx-md-0 mx-lg-0 p-0">
      <div class="col-12 ms-lg-0 ms-md-0 my-0 me-md-3 me-lg-3  ">
        <div class="p-0 m-0 rounded">
          <div class="p-2 mt-2 text-white fs-md fw-bold rounded-top d-flex justify-content-center ">
            <div class="dropdown me-2 " data-bs-toggle="modal" data-bs-target="#statesJapan">
              <button class="btn-mobile fs-md btn-outline-primary-mobile dropdown-toggle text-secondary d-flex align-items-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="">
                <!-- <i class="fa fa-ellipsis-v ml-1" aria-hidden="true"></i> --><span class="me-1">Location</span> [&nbsp;<div class="selectedLocate_holder text-nowrap_"> All Japan</div>&nbsp;]
              </button>

            </div>

            <div class="dropdown" data-bs-toggle="modal" data-bs-target="#other-filters">
              <button class="btn-mobile fs-md btn-outline-primary-mobile dropdown-toggle text-secondary " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="">
                <!-- <i class="fa fa-ellipsis-v ml-1" aria-hidden="true"></i> --><span class="py-4">Other filters</span>
              </button>

            </div>


          </div>
          <input type="hidden" value="<?php echo $_GET["adcategory"]; ?>" id="ha-categID" name="ha-categID">
          <!-- <p class="ha-categ-content__sidebar ">
                        
                        <ul class="fs-md ha-subcategories__list m-0 p-0 px-3 font-inter">
                          <h3 class="fs-title-1 fw-bold m-0 p-0">Vehicle</h3>
                          <a href="#"><li><span class="fw-bolder font-inter">Cars</span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Cars dsf da dsf dsf</span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Carsdf df f</span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Carss dfdsf dsfd fd fd</span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Cars dsf df dfs fdfafadsf </span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Cars dsf dfd fadsf fds f</span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Carssdf dsf fdsaf dfadfds </span> | <span class="badge bg-purple">92428</span> </li></a>
                          <a href="#"><li><span class="">Carsds  af df sf</span> | <span class="badge bg-purple">92428</span> </li></a>
                        </ul>
                      </p> -->
        </div>

        <!-- <div class="mb-3 px-2 bg-white ha-category-locate rounded" data-bs-toggle="modal" data-bs-target="#statesJapan">
          <div class="p-2" style="position: relative;">
            <span class="fs-title-3 text-grey  fw-bold">Location</span><br>
            <span class="selectedLocate_holder">All Japan</span>
            <span class="ha-category-locate__arrow"><i class="fa fa-angle-right fs-title-2 "></i></span>
          </div>
        </div> -->

      </div>
      <div class="col-12 ">
        <div class="ha-category-sort__wrapper d-flex justify-content-center align-items-center mb-3 mt-1">
          <span class="fs-md fw-bolder">Sort By:</span>
          <div class="mx-2 fs-md-1 ha-category-sort-value__select">
            <i class="fa fa-sort m-0 "></i>
            <span class="ha-category-sort__selected">Recommended</span>
            <div class="ha-category-sort__list bg-white shadow-sm">
              <ul>
                <li class="ha-category-sort__item" dvalue="rec" dfullValue="Recommended">Recommended</li>
                <li class="ha-category-sort__item" dvalue="new" dfullValue="Newest">Newest</li>
                <li class="ha-category-sort__item" dvalue="low" dfullValue="Lowest">Lowest</li>
                <li class="ha-category-sort__item" dvalue="high" dfullValue="Highest">Highest</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="ha-category-items__container">
          <?php
          if ($allAdCategByID['status'] == 1) {
            foreach ($allAdCategByID['message'] as $fields) {
              $getImageCount = $adManager_ob->countAdImagesByID($fields['mallAdID']);
              $thumbImageName = $mediaManager->getThumbImage($fields['mallAdID']);
              if ($thumbImageName['status'] == 1) {
                $thumbImageName = $thumbImageName['message']['mallMediaName'];
              } else {
                $thumbImageName = "";
              }
          ?>
              <div class="row ha-mpage-items__wrapper mb-2 ">
                <div class="col-4 rounded-start ha-category-item__image" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                  <?php
                  if (!empty($fields['mallAdCondition'])) {
                    echo '<span class="ha-mpage-item__title fs-md text-light text-center fw-bold opacity-50">' . $fields['mallAdCondition'] . '</span>';
                  }
                  ?>
                  <span class="ha-card__counter fs-sm"><span id="ha-counter__js"><?php echo $getImageCount['message']; ?></span><i class="fa fa-camera ms-1 me-0"></i></span>
                  <?php
                  if ($adManager_ob->checkPromotedAd($fields['mallAdID'], $fields['mallAdPromoID'])['status']) { ?>
                    <span class="ha-card-content-icon-1 fw-bolder text-dark d-flex justify-content-center align-items-center" href="#">
                      Sponsored Ad
                    </span>
                  <?php } ?>
                </div>
                <a href="product.php?adID=<?php echo $fields['mallAdID']; ?>" class="col-8 rounded-end bg-light-blue text-dark">
                  <div class="">
                    <div class="my-2 ">
                      <span class="fs-md fw-bolder"><?php echo $fields['mallAdTitle'] ?></span>
                    </div>
                    <div class="">
                      <span class="ha-category-item__desc fs-md-1"><?php echo $fields['mallAdDesc'] ?></span>
                    </div>
                    <div class="">
                      <span class="badge bg-dark fs-md mb-2"><?php echo $adManager_ob::CURRENCY . number_format($fields['mallAdPrice']); ?></span><br>
                      <span class="fs-sm-1 text-left "><i class="fa fa-map-marker m-0"></i> <?php $getlocationCateg = explode(".", $fields['mallAdLoc']);
                                                                                            echo $getlocationCateg[0] . ", ", $getlocationCateg[1] ?>, Japan.</span>
                    </div>
                  </div>
                </a>
              </div>
          <?php }
          } else {
            echo '<div class="ha-none__display w-50 text-center m-5 mx-auto">
                                <img class="img-fluid mx-auto" src="./assets/images/notfound3.svg" id="adverts1">
                                <div class="fs-title-4 fw-bolder" id="adverts2">No active Ad found</div>
                                <div class="fs-md" id="adverts3">No content availiable</div>
                            </div>'; //<button type="button" class="btn-md btn-secondary p-1 mt-3 w-75 buttns0" onclick="myLnked()" id="copd0" >Copy my link</button>
          } ?>
        </div>

      </div>
    </div>
  </div>
  <div class="modal fade" id="other-filters" tabindex="-1" aria-labelledby="makOfferLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Filter Category
            <!--<span class="badge bg-purple fs-md">92428 Ad</span>-->
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="my-3 px-2 bg-white ha-category-filter rounded">
            <div class="ps-2 pt-2 pb-2">
              <div class="ha-category-filter__header" style="position: relative;"><span class="text-grey fs-title-3 fw-bold">Price</span> <span class="ha-category-filter__arrow"><i class="fa fa-minus fs-md ha-category-filter__arrowicon"></i></span></div>
              <div class="ha-category-filter__body">
                <form class="my-3 ">
                  <div class="form-group ">
                    <input type="number" name="priceMin" id="priceMin" class="form-control p-2 d-inline fs-md priceMin" style="width:45%" placeholder="Min">&nbsp; - &nbsp;<input type="number" style="width:45%" name="priceMax" id="priceMax" placeholder="Max" class="fs-md d-inline form-control p-2 w-20">
                  </div>
                  <div class="form-group my-3">
                    <input type="range" class="form-range" min="0" max="10000000" value="0" step="1" name="priceMinRange" id="priceMinRange">
                    <input type="range" class="form-range" min="0" max="5000000000" step="200" name="priceMaxRange" id="priceMaxRange">
                  </div>
                  <div class="form-group d-flex justify-content-between ">
                    <input type="reset" class="btn btn-secondary fs-md" value="Clear"> <input type="button" class="btn btn-primary fs-md" id="ha-apply-pricefilter_btn" value="Apply">
                  </div>

                </form>
              </div>
            </div>
          </div>

          <?php foreach ($allAdCategOptions['message'] as $categOptionsEach) {
            $categOptionName = explode("-", $categOptionsEach['mallCategParamName']);
            $categOptionValues = explode(",", $categOptionsEach['mallCategParamValues']);
            if ($categOptionsEach['mallCategParamValues'] != "") {

          ?>
              <div class="my-3 px-2 bg-white ha-category-filter rounded">
                <div class="ps-2 pt-2 pb-2">
                  <div class="ha-category-filter__header" style="position: relative;"><span class="text-grey fs-title-3  fw-bold"><?php echo $categOptionName[1] ?></span> <span class="ha-category-filter__arrow"><i class="fa fa-minus fs-md ha-category-filter__arrowicon"></i></span></div>
                  <div class="input-group">
                    <span class="input-group-text fs-md  fs-md mt-2" id="basic-addon1"><i class="fa fa-search m-0"></i></span>
                    <input type="text" name="mark-filter-keyword__input" class="form-control mark-filter-keyword__input fs-md p-2 mt-2" placeholder="">
                  </div>

                  <div class="ha-category-filter__body">
                    <form class="my-3 ">
                      <?php for ($i = 0; $i < count($categOptionValues); $i++) {

                      ?>
                        <div class="form-check ">
                          <input type="checkbox" name="<?php $cateOptionAll = explode(" ", $categOptionValues[$i]);
                                                        echo trim($categOptionName[0]) . "_" . trim($categOptionValues[$i]); ?>" id="<?php echo trim($categOptionName[0]) . "_" . trim($categOptionValues[$i]); ?>" class="form-check-input p-2 ha-filter__attribs">
                          <label class="form-check-label fs-md ms-2 py-1 font-inter" for="<?php echo trim($categOptionName[0]) . "_" . trim($categOptionValues[$i]); ?>"><?php echo trim(ucwords($categOptionValues[$i])) ?>
                            <!-- <span class="badge bg-purple">6,000 Ads</span> -->
                          </label>
                        </div>
                      <?php } ?>
                    </form>
                  </div>
                </div>
              </div>
          <?php }
          } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="citiesJapan" tabindex="-1" aria-labelledby="makOfferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Locations in Japan
            <!--<span class="badge bg-purple fs-md">92428 Ad</span>-->
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form>
          <div class="modal-body bg-light-blue">
            <input type="text" name="mark-keyword__input" class="form-control mark-keyword__input fs-md p-3" placeholder="Find Location">
            <table class="table table-striped table-hover mt-3 fs-md">
              <tbody class="text-dark ha-cities__tbdata">
              </tbody>
            </table>

          </div>
          <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-primary w-100">SEND</button>
                </div> -->
        </form>

      </div>
    </div>
  </div>
  <div class="modal fade" id="statesJapan" tabindex="-1" aria-labelledby="makOfferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="me-5" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-angle-left"></i></button> -->
          <h5 class="modal-title" id="exampleModalLabel">Locations in Japan
            <!--<span class="badge bg-purple fs-md">92428 Ad</span>-->
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="row m-4 g-0" style="overflow-y: auto; max-height:300px;">
          <?php $getAllStates_responce = $adManager_ob->getAllLocationState();
          if ($getAllStates_responce['status'] == 1) {
            foreach ($getAllStates_responce['message'] as $key) {


          ?>
              <div class="col p-0 mb-1" data-bs-toggle="modal" data-bs-target="#citiesJapan"><span datastateval="<?php echo $key['mallLocState']; ?>" class="ha-state-each__text badge bg-primary fs-title-2"><?php echo $key['mallLocState']; ?></span></div>
          <?php }
          } else {
            echo '<div class="text-center fw-bolder">No location found</div>';
          } ?>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="sendDM" tabindex="-1" aria-labelledby="makOfferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form>
          <div class="modal-body">
            <div class="form-floating">
              <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
              <label for="floatingTextarea">Message</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary w-100">SEND</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="./assets/js/main.js"></script> -->
  <script src="./assets/js/jquery.inputmask.min.js"></script>
  <script src="../dependencies/node_modules/mark.js/dist/jquery.mark.min.js" charset="UTF-8"></script>
  <script src="../dependencies/node_modules/mark.js/dist/jquery.mark.es6.min.js"></script>
  <script>
    //MARK HIGHLITER CODE

    /*    $('input').inputmask({
         rightAlign: false,
       });
       $(document).ready(function () {
         
         //$(".ha-category-filter__body").hide(); //Hide all filter body
       }) */
    $(".ha-state-each__text").click(function() {
      var locationStateSelected = $(this).attr('datastateval');
      var stateListWrapper = $(".ha-cities__tbdata");
      $.ajax({
        url: '../handlers/stateCities.php',
        type: 'GET',
        data: "filter_states=" + locationStateSelected + "&adcategory=" + categID,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          stateListWrapper.html("Loading...")
        },
        success: function(data) {

          stateListWrapper.html(data)
        }
      });
    })
    var categID = "";
    var sortBy = "";
    var priceRange = "";
    var currentLocateSelected = "";
    var filter_attribs = [];
    //var filter_attribs_values=[', Local'];
    //When attributes checkboxs are clicked
    $('.ha-filter__attribs').on('click', function() {
      if ($(this).is(':checked')) {
        filter_attribs.push($(this).attr('name'));
        buildURL()
      } else {
        filter_attribs.splice(filter_attribs.indexOf($(this).attr('name')), 1);
        buildURL()
      }
    })
    $('#ha-apply-pricefilter_btn').click(function() {
      var priceMin = $('#priceMin').val();
      var priceMax = $('#priceMax').val();
      if (priceMax == "" || priceMin == "") {
        //alert("Empty");
      } else {
        if (priceRange == "") {
          priceRange = "mallAdPrice_" + priceMin + "-" + priceMax;
          filter_attribs.push(priceRange);
          buildURL()
        } else {
          filter_attribs.splice(filter_attribs.indexOf(priceRange), 1);
          priceRange = "mallAdPrice_" + priceMin + "-" + priceMax;
          filter_attribs.push(priceRange);
          buildURL();
        }

      }
    })


    $(document).on("click", ".ha-category-locate__filter", function() {
      var locationSelected = $(this).attr('dvalue');
      var splitLocationSelected = locationSelected.split("_");
      if (currentLocateSelected == "") {
        filter_attribs.push(locationSelected);
        buildURL()
        currentLocateSelected = locationSelected;
        $(".selectedLocate_holder").html(splitLocationSelected[1]);
      } else {
        filter_attribs.splice(filter_attribs.indexOf(currentLocateSelected), 1);
        filter_attribs.push(locationSelected);
        buildURL()
        currentLocateSelected = locationSelected;
        $(".selectedLocate_holder").html(splitLocationSelected[1]);
      }
    })





    $(".mark-filter-keyword__input").on("input", function() {
      $context = $(this).parent("div").parent("div").find("form .form-check");
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
    $(".ha-category-filter__header").click(function() {
      var categfilter_icon = $(this).find("i");
      var categFilter_body = $(this).parent("div").parent("div").find('.ha-category-filter__body');
      if (categFilter_body.is(":visible")) {
        categFilter_body.slideUp("fast");
        categfilter_icon.removeClass("fa-minus");
        categfilter_icon.addClass("fa-plus");
        $(this).parent("div").find(".input-group").hide();
      } else {
        categFilter_body.slideDown("fast");
        categfilter_icon.removeClass("fa-plus");
        categfilter_icon.addClass("fa-minus");
        $(this).parent("div").find(".input-group").show();
      }
    })

    $("#priceMinRange").on('input', function() {
      var minRange = $(this).val();
      var minPrice = $(this).parent("div").parent("form").find("#priceMin");
      minPrice.val(minRange);
    })
    $("#priceMaxRange").on('input', function() {
      var maxRange = $(this).val();
      var maxPrice = $(this).parent("div").parent("form").find("#priceMax");
      maxPrice.val(maxRange);
    })


    $(".ha-category-sort-value__select").click(function() {
      if ($(".ha-category-sort__list").is(":visible")) {
        $(".ha-category-sort__list").slideUp("fast");
      } else {
        $(".ha-category-sort__list").slideDown("fast");
      }
    })
    $(".ha-category-sort__item").click(function() {
      var select_value = $(this).attr("dvalue");
      var selectFullValue = $(this).attr("dfullValue");
      $(".ha-category-sort__selected").html(selectFullValue);
      $(".ha-category-sort__item").removeClass("fw-bolder");
      $(".ha-category-sort__item").removeClass("text-primary");
      $(this).addClass("fw-bolder");
      $(this).addClass("text-primary");
      sortBy = select_value;
      buildURL()
    })

    $(document).mouseup(function(e) {
      var catList = $(".ha-category-sort__list");
      if (catList.is(":visible")) {
        catList.slideUp("fast");
      }
    });


    function buildURL() {
      categID = $("#ha-categID").val();
      var currentURL = "?adcategory=" + categID + "&";
      if (sortBy != "") {
        currentURL += "sortBy=" + sortBy;
      }
      if (filter_attribs.length > 0 && sortBy == "") {
        currentURL += "filter_attribs=" + filter_attribs.join(",");
      } else if (filter_attribs.length > 0 && sortBy != "") {
        currentURL += "&filter_attribs=" + filter_attribs.join(",");
      }
      ChangeUrl("", currentURL);
      filterList(currentURL, categID);
    }

    function ChangeUrl(page, url) {
      if (typeof(history.pushState) != "undefined") {
        var obj = {
          Page: page,
          Url: url
        };
        history.pushState(obj, obj.Page, obj.Url);
      } else {
        alert("Browser does not support HTML5.");
      }
    }

    function filterList(urlValues, categID) {
      var filterFormData = new FormData();
      var getUrl = urlValues.split("?");
      var filterListWrapper = $(".ha-category-items__container");
      $.ajax({
        url: '../handlers/filterlist.php',
        type: 'GET',
        data: getUrl[1] + "&adcategory=" + categID,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          filterListWrapper.html("Loading...")
        },
        success: function(data) {

          filterListWrapper.html(data)
        }
      });
    }




    /* function getUrlVars(dataValue) {
      var vars = {};
      var parts = currentURL.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
      function(m,key,value) {
        vars[key] = value;
      });
      return vars.indexOf(dataValue);
    } */
  </script>
</body>

</html>