<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
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

use services\AdS\AdManager;
use services\SecS\SecurityManager;
use services\AccS\AccountManager;
use services\MsgS\feedbackManager;

$securityManager_ob = new SecurityManager();
$adManager = new AdManager();
$usrAccManager_ob = new AccountManager();
$feedback_ob = new feedbackManager();
/* if (!isset($_SESSION["gaijinmall_user_"])) {
    header("location: Signin.php");
} */
//$mallPrefetchForms=array();
$sys_msg = [];
$usrIDData = $_GET['user_mob_id__'];
$pageUsrID__ = $_GET['user_mob_id__'];
$adManagerVal = $adManager->getAllMallParentCategory();
$categChild = $adManager->getCategChildByID(12345);
// $isUsrPhoneVerified = $usrAccManager_ob->getUserVerifiedNumberByID($pageUsrID__)['status'];
// if ($isUsrPhoneVerified != 1) {
//     $sys_msg['msg_type'] = 4;
//     $sys_msg['msg'] = "You can't proceed, your phone number is not verified yet";
// }
$getUsrInfo = $usrAccManager_ob->getUsrBasicInfoByID($pageUsrID__)['message'];
$getUsrBizInfo = $usrAccManager_ob->getUsrBizInfoByID($pageUsrID__)['message'];
if (isset($_POST["submit"])) {
    if ($securityManager_ob->validateCSRF($_POST['form_token__input'])) {
        isset($_POST["genForms"]) ? $formElements = $_POST["genForms"] : $formElements = [];
        isset($_POST["sub_category"]) ? $categ = $_POST["sub_category"] : $categ = "";
        isset($_POST["mallAdTitle"]) ? $title = $_POST["mallAdTitle"] : $title = "";
        isset($_POST["mallAdDesc"]) ? $desc = $_POST["mallAdDesc"] : $desc = "";
        isset($_POST["mallAdPrice"]) ? $price = $_POST["mallAdPrice"] : $price = "";
        isset($_POST["mallAdPriceFinal"]) ? $finalPrice = $_POST["mallAdPriceFinal"] : $finalPrice = 0;
        isset($_POST["mallAdNegotiable"]) ? $neg = $_POST["mallAdNegotiable"] : $neg = 0;
        isset($_POST["mallAdPromoID"]) ? $promoID = $_POST["mallAdPromoID"] : $promoID = "";
        isset($_POST["location_state"]) && isset($_POST["location_city"]) ? $adLocation = $_POST["location_city"] . "." . $_POST["location_state"] : $adLocation = "";
        isset($_POST["mallAdIDVal"]) ? $mallAdIDVal = $_POST["mallAdIDVal"] : $mallAdIDVal = "";
        $postTime = time();
        isset($_POST["mallAdStatus"]) ? $status = $_POST["mallAdStatus"] : $status = 0;
        $arrData = [
            'mallUsrID' => $usrIDData,
            'mallAdID' => $mallAdIDVal,
            'mallCategID' => $categ,
            'mallAdTitle' => $title,
            'mallAdDesc' => $desc,
            'mallAdPrice' => $price,
            'mallAdNegotiable' => $neg,
            'mallAdPromoID' => $promoID,
            'mallAdNegotiable' => $neg,
            'mallAdLoc' => $adLocation,
            'mallAdMediaList' => "",
            'mallAdPostTime' => $postTime
        ];
        //Get dynamically generated form inputs
        foreach ($_POST["genForms"] as $key => $value) {
            $arrData[$key] = $value;
        };
        /* array_push($arrData,['mallAdPromoID'=>$promoID,
        'mallAdNegotiable'=>$neg,
        'mallAdLoc'=>$adLocation]);
        print_r($arrData); */
        $creatAd = $adManager->createAd($arrData);
        if ($creatAd["status"] == 1) {
            $feedback_ob->updateAdUsrRating($mallAdIDVal, $usrIDData, 0);
            $sys_msg['msg_type'] = 1;
            $sys_msg['msg'] = "Ad Created Successfully";
        } else {
            $sys_msg['msg_type'] = 500;
            $sys_msg['msg'] = "Could not create Ad. Try again.";
        }
    } else {
        $sys_msg['msg_type'] = 0;
        $sys_msg['msg'] = "System Error";
    }
}

$newToken = $securityManager_ob->setCSRF();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="../dependencies/node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="./assets/css/create-ad.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link href="./assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/cute-alert.css">
    <title>New Advert</title>
    <script>
        var pageTitle=document.title;
            window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                window.flutter_inappwebview.callHandler('getPageTitles', 1, true, pageTitle)
            });
        </script>
    <!-- google translator  -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'ja' }, 'google_translate_element');
        }
    </script>
         <!-- google translator  -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
   
</head>

<body class="bg-light-blue">

    <div class="container-fluid">

        <div class="mx-1 mx-md-5 mx-lg-5">


            <!-- Tab pills -->
            <ul class="nav nav-pills mb-3 mt-3 w-100 d-flex justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item  tab-clr-default rounded-start" role="presentation" id="ad-info">
                    <button class="nav-link  w-100 rounded-start active text-start text-white py-3 fw-normal fs-md-2 ps-4" id="pills-ad-info-tab" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fa fa-ad mx-2"></i>Advert info</button>
                </li>
                <li class="nav-item tab-clr-default rounded-end " role="presentation" id="ad-detail">
                    <button class="nav-link w-100 rounded-end  text-start text-white py-3 fw-normal fs-md-2 ps-4" id="pills-ad-detail-tab" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fa fa-info-circle mx-2"></i>Advert detail</button>
                </li>
            </ul>

            <form method="post" class="" id="createAdForm" enctype="multipart/form-data">
                <div class="first">
                    <!-- categories multiselect -->
                    <div class="row justify-content-between mt-2">
                        <div class=" d-flex mb-1  col-md-6 col-sm-12" id="multiselec">
                            <select class="form-select p-3 select2" name="category">
                                <option value="none">Select Category</option>
                                <?php
                                if ($adManagerVal["status"] === 404) { ?>
                                    <option value="" disabled><?php echo ($adManagerVal["message"]); ?></option>
                                    <?php  } else if ($adManagerVal["status"] === 1) {
                                    foreach ($adManagerVal["message"] as $value) { ?>
                                        <option value="<?php echo $value["mallCategID"]; ?>"><?php echo $value["mallCategName"]; ?></option>
                                <?php }
                                }

                                ?>
                            </select>
                        </div>

                        <div class=" d-flex mb-1 col-md-6 col-sm-12" id="multiselec">
                            <select class="form-select p-3 select2 " disabled name="sub_category" id="sub_category">
                                <option value="">Select Sub Category</option>
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-between mt-md-2">
                        <!--  <div class=" d-flex mb-1  col-md-6 col-sm-12" id="multiselec">
                                    <select class="form-select p-3 select2" name="sub_sub_category">
                                        <option value="">Other Category</option>
                                    </select>
                                </div> -->

                        <div class=" d-flex mb-1 col-md-6 col-sm-12" id="multiselec">
                            <select class="form-select p-3 select2" disabled name="location_state" id="location_state">
                                <option>Select Location (Nationwide)</option>
                                <?php
                                $getCitiesResponse = $adManager->getAllLocationState();
                                if ($getCitiesResponse['status'] == 1) {
                                    foreach ($getCitiesResponse['message'] as $cityEach) {
                                        echo "<option value='" . $cityEach['mallLocState'] . "'>" . $cityEach['mallLocState'] . "</option>";
                                    }
                                } else {
                                    echo "<h1>No Location found</h1>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class=" d-flex mb-1 col-md-6 col-sm-12" id="multiselec">
                            <select class="form-select p-3 select2" disabled name="location_city" id="location_city">
                                <option>Select City</option>

                            </select>
                        </div>
                        <div class=" d-flex mb-1 col-md-6 col-sm-12 mt-2" id="multiselec">
                            <div class="form-floating w-100 ">
                                <input name="mallAdTitle" maxlength="60" id="mallAdTitle" disabled type="text" class="form-control" placeholder="Title" />
                                <label for="mallAdTitle">Ad Title *</label>
                            </div>
                        </div>
                        <input type="hidden" name="mallAdIDVal" id="mallAdIDVal" required value="<?php echo $securityManager_ob->generateOtherID(); ?>">
                        <input type="hidden" name="usrIDData" id="usrIDData" required value="<?php echo $usrIDData; ?>">
                        <input type="hidden" name="numUploaded" id="numUploaded" value="0">
                    </div>
                    <div class=" mt-4 text-center">
                        <h5 class="fw-bolder fs-3">Add photo</h5>
                        <div class="text-secondary fw-bold lh-sm fs-title-1">Add atleast 2 photos of this categories</div>
                        <div class="text-secondary lh-sm fs-md mt-1">The Last picture will be displayed first in product page.</div>
                    </div>
                    <div class="row align-items-center  mt-3">
                        <div class="col-md-6 button-container w-100">
                            <div class="d-flex align-items-center justify-content-center svg">
                                <div class="large-button button_click ">
                                    <div class="d-flex">
                                        <i class="fas fa-plus icon-plus fs-3 fw-bolder"></i>
                                    </div>
                                    <input type="file" name="imageFile[]" id="imageFile" accept="image/*;capture=camera" multiple onchange="">

                                </div>
                                <!-- <div class="fs-md ms-2">
                                    Tap the ( + ) to select picture(s)
                                </div> -->
                            </div>

                        </div>

                        <div class="col-md-6 imageDisplay" id="loadImageDisplay">
                        </div>
                    </div>
                    <div class="instruction text-secondary mt-3 text-center">
                        <p class="fs-md-1"> <br>
                            Each picture must not exceed 5mb <br>
                            Supported formats are* .jpg,*.gif and *.png <br>
                            <small><strong>N.B:</strong> For mobile devices select all the pictures once.</small>
                        </p>
                        <strong></strong>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <button data-bs-toggle="tooltip" data-bs-placement="top" title="Product details" type="button" class="btn-mobile btn-primary-mobile w-100 fs-md mb-3" id="next">
                                Next
                            </button>
                        </div>
                    </div>


                </div>

                <div class="second d-none">

                    <div class="row justify-content-between ">
                        <span class="catCom d-none"></span>
                        <div class="row justify-content-between m-0 p-0" id="load-form-inputs"></div>
                        <div class=" d-flex mb-1 col-md-12 col-sm-12 w-100 my-2" id="multiselec">
                            <div class="form-floating w-100">
                                <textarea id="mallAdDesc" maxlength="600" required class=" form-control" placeholder="Description" name="mallAdDesc" style="resize: none; min-height:150px;"></textarea>
                                <label for="mallAdDesc">Description</label>
                            </div>
                        </div>
                        <div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
                            <div class="input-group form-floating">
                                <span class="input-group-text" id="basic-addon1"><?php echo $adManager::CURRENCY; ?></span>
                                <input type="number" class="form-control p-2" required placeholder="Price" id="mallAdPrice" name="mallAdPrice">
                                <label for="mallAdPrice" style="z-index: 10001; padding-left:50px; font-size:14px;">Price</label>
                            </div>
                        </div>

                        <div class="saleDetail"></div>

                        <div class="fs-md-1 my-2" id="multiselec">
                            <div class="form-check ">
                                <input type="checkbox" class="p-2 form-check-input" role="switch" id="mallAdNegotiable" name="mallAdNegotiable">
                                <label for="mallAdNegotiable" class="form-check-label">Negotiatable</label>
                            </div>
                        </div>
                        <div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
                            <div class="form-floating w-100 ">
                                <input name="ad-phone" id="ad-title" disabled type="text" class="form-control" placeholder="Phone" value="<?php echo $getUsrInfo['mallUsrPhoneNo'] ?>" />
                                <label for="ad-phone">Phone</label>
                            </div>
                        </div>
                        <div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
                            <div class="form-floating w-100 ">
                                <input name="ad-name" disabled id="ad-title" type="text" class="form-control" placeholder="Name" value="<?php echo $getUsrBizInfo['mallBizName'] ?>" />
                                <label for="ad-name">Name</label>
                            </div>
                        </div>




                        <!-- <div id="ab"></div> -->

                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <div class="mt-3">
                            <!-- <p class="fw-bold text-center">Promote your ad</p>
                            <p class="sell-fs-base sell-text-color text-center text-muted text-center" style="font-size:17px;">Please, choose one of the following options to post your ad</p>
                            <div class="sell-promote-ad-select mt-3" style="border:2px solid #0d6efd;">
                                <input type="radio" class="btn-check " name="promote" id="option3" autocomplete="off" value="standard">
                                <label class="btn btn-outline-primary p-3 fw-bold sell-fs-base sell-text-color text-center" style="font-size: 26px; width: 100%;" for="option3">Standard Ad</label>
                            </div>
                            <div class="sell-promote-ad-select p-2 mt-4" style="border:2px solid rgba(13, 110, 253, 0.4);">
                                <p class="mt-3 mb-4 ms-3 fs-5 fw-bold" style="font-size: 26px !important;">Top <span class="badge p-2 bg-primary fw-normal sell-fs-base" style="font-size: 11px;">Best Offer</span></p>
                                <div class="d-flex">
                                    <div>
                                        <input type="radio" class="btn-check " name="mallAdPromoID" id="option1" autocomplete="off" value="7">
                                        <label class="btn btn-outline-primary" for="option1">7 days</label>

                                        <input type="radio" class="btn-check " name="mallAdPromoID" id="option2" autocomplete="off" value="30">
                                        <label class="btn btn-outline-primary" for="option2">30 days</label>
                                    </div>
                                    <div class="d-flex justify-content-end flex-grow-1">
                                        <p class="pe-3 sell-fs-base mt-2" style="font-size:25px; font-weight: bolder;">$25</p>
                                    </div>
                                </div>
                            </div>
                            <div class="sell-promote-ad-select  mt-4" style="border:2px solid #0d6efd;">
                                <input type="radio" class="btn-check" style="border: none !important;" name="mallAdPromoID" id="option4" autocomplete="off" value="premium">
                                <label class="btn btn-outline-primary sell-fs-base sell-text-color text-center fw-bold" style="font-size: 26px; border: none !important; width: 100%;" for="option4">Boost Premium</label>
                                <label class="d-flex p-3" for="option4" style=" cursor: pointer;">
                                    <div>
                                        <span class="badge d-inline-block ms-3  sell-fs-base fw-normal p-2 px-3 rounded-pill bg-primary">1 month</span>
                                    </div>
                                    <div class="d-flex justify-content-end flex-grow-1">
                                        <p class="pe-3 sell-fs-base mt-2" style="font-size:25px; font-weight: bolder;">$200</p>
                                    </div>
                                </label>
                            </div>-->
                            <div>
                                <input type="hidden" required value="<?php echo $newToken; ?>" name="form_token__input">
                                <button type="submit" name="submit" id="submit" class="btn-mobile btn-primary-mobile w-100 fs-md">
                                    Post Ad
                                </button>
                            </div>
                        </div>
                    </div>




                    <div class="d-flex justify-content-center">
                        <p class="sell-terms-width sell-fs-base sell-text-color mt-2 text-muted text-center" style="font-size:12px; max-width:370px;">By clicking on Post Ad, you accept the <a href="terms.php" class="sell-clr-primary">Terms of Services</a>, you will abide by the safety Tips, and declare that this posting does not include any probhibited items</p>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script>
        // In your Javascript (external .js resource or <script> tag)

        function focusInput() {
            document.getElementById('selectInput').focus()
            document.getElementById('sell-categories-label-text').classList.remove('sell-fs-base')
            document.getElementById('sell-categories-label').classList.add('sell-label-transition')
            document.getElementById('sell-categories-parent').classList.remove('d-none')
        }

        function looseFocus() {
            document.getElementById('sell-categories-label-text').classList.add('sell-fs-base')
            document.getElementById('sell-categories-label').classList.remove('sell-label-transition')
            //document.getElementById('sell-categories-parent').classList.add('d-none')
        }

        function showSubleveltwo() {
            document.getElementById('sell-categories-level-two').classList.remove('d-none')
            document.getElementById('sell-categories-parent').classList.add('d-none')
        }

        // function passData(myValue) {
        //     var updateContent = document.getElementsByClassName('myTitle');
        //     updateContent.innerHTML=myValue;
        // }
        function passInfo(myValue) {
            var updateContent = document.getElementById("catPlaceholder");
            updateContent.innerHTML = myValue;
        }


        document.getElementById("multiselect").addEventListener('click', focusInput)
        // document.getElementsByClassName('sell-fs-base').addEventListener('click',updateContent)
        document.getElementById("sub1_trigger").addEventListener('click', showSubleveltwo)
        document.getElementById("selectInput").addEventListener('blur', looseFocus)
        // document.getElementById("sc-1").addEventListener('focus', openSubcategory)
    </script>

    <script src="./../dependencies/node_modules/jquery/dist/jquery.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="./assets/js/create-ad.js"></script>
    <script src="./assets/js/adHandler.js"></script>
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="assets/js/cute-alert.js"></script>
    <script src="assets/js/jquery-maxlength.min.js"></script>
    <script>
        var pageTitle=document.title;
            window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                window.flutter_inappwebview.callHandler('getPageTitles', 1, true, pageTitle)
            });
        </script>
    <script>
       /*   window.onload = function() { // can also use window.addEventListener('load', (event) => {
            var pageTitle=document.title;
        window.flutter_inappwebview.callHandler("getPageTitle", pageTitle);
  };
 */
//   if (document.readyState == 'loading') {
//   // still loading, wait for the event
//   var pageTitle=document.title;
//   alert("Workingjjjk");
  
  
// } else {
//   // DOM is ready!
//   //work();
//   alert("Working")
// }


        $(".resetForm").on("click", function(e) {
            $('.form-select option:first').prop('selected', true).trigger("change");
            document.getElementById('createAdForm').reset();
            return false;
        })

        $(".select2").select2({
            theme: "bootstrap-5",
        })
        $("input,textarea").maxlength({
            text: "{total}/{maxLength}",
            position: "right",
            color: "grey",
            fontSize: "12px",
            template: "<div />",
            showTemplate: true
        });

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
                        echo '
                        window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                            window.flutter_inappwebview.callHandler("getPageAlert", "success", "Advert in Review", "Good job, your advert is submitted and under review.")
                        });
                        ';
                        break;
                    case '4':
                        echo '
                        window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                            window.flutter_inappwebview.callHandler("getPageAlert", "error", "Phone Unverified", "' . $sys_msg['msg'] . '")
                        });
                          ';
                        break;
                    default:
                        echo '
                        window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
                            window.flutter_inappwebview.callHandler("getPageAlert", "error", "Something is wrong", "' . $sys_msg['msg'] . '")
                        });';
                        break;
                }
            }
            ?>
        });
        //    TOOL TIP ENABLER
        /* var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl) }) */
    </script>

    <script type="text/javascript">

      $("#mallAdPrice").on('keyup change', function(){

        //get commission

        var categ = $("[name='category']").val();
        $.ajax({
          url: "../handlers/adHandler.php",
          method: "POST",
          data: { cat: categ },
          success: function(response) {
            $('.catCom').text(response);
          },
          error: function() {
            console.log("Error occurred");
          }
        });
        
        
        var price = $(this).val();
        var commission = (price * ($(".catCom").text()/100)).toFixed(2);

        var profit = price - commission;
        profit = profit.toFixed(2);

        // create the elements
        var commElement = $('<label>');
        var profitElement = $('<label>');

        var finalElement = $('<input>');

        // set the IDs and initial text for the elements
        commElement.attr('id', 'comm');
        commElement.text('<?php echo $adManager::CURRENCY;?>' + commission);
        profitElement.attr('id', 'profit');
        profitElement.text('<?php echo $adManager::CURRENCY;?>' + profit);
        finalElement.attr('id', 'finalPrice');
        finalElement.attr('name', 'mallAdPriceFinal');
        finalElement.attr('hidden', 'hidden');
        finalElement.val(profit);

        // update the existing elements and add the new elements to the page
        $("#mallAdPriceFinal").val(profit);
        $('#profit').text(profit);
        $('.saleDetail').html('<div class="d-flex justify-content-between col-md-6 col-sm-12"><label class="fw-bolder">Commission</label></div><div class="d-flex justify-content-between col-md-6 col-sm-12 mt-2"><label class="fw-bolder">Sales Profit</label></div> <div></div>');
        $('.saleDetail').find('div').eq(0).append(commElement);
        $('.saleDetail').find('div').eq(1).append(profitElement);
        $('.saleDetail').find('div').eq(2).append(finalElement);
      });
    </script>
<!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
</body>

</html>