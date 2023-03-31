<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}

use services\SecS\SecurityManager;
use services\AdS\AdManager;
use services\MedS\MediaManager;
use services\InitDB;

$sys_msg = [];
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$mediaManager = new MediaManager();
if (isset($_GET['verify_token'])) {
    $tokenData = $securityManager_ob->sanitizeItem($_GET['verify_token'], "string");
    if ($securityManager_ob->verifyToken($tokenData)) {
        $sys_msg['msg_type'] = "1";
        $sys_msg['msg'] = "Email Verified";
        echo '<script>
        setTimeout(function() {
            window.location="' . MALL_ROOT . '";
        },4000)
    </script>';
    } else {
        $sys_msg['msg_type'] = 404;
        $sys_msg['msg'] = "Token does not exist or expired";
        echo MALL_ROOT;
        echo '<script>
        setTimeout(function() {
            window.location="' . MALL_ROOT . '";
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
    <?php include "header-top.php"; ?>
    <header class="header">
        <!-- Image 1 -->
        <img src="./assets/images/istockphoto-1000379636-612x612-removebg-preview.png" alt="header_pic" class="img-fluid header_parent-img-1">

        <!-- Center text -->
        <h3 class="header_text fs-4 align-items-center" style="z-index:1001;">Gaijinmall helps you to buy
            or sell your goods and
            services with ease
            <p>
            <div class="input-group ho-search__bar">
                <input type="text" class="form-control fs-md-1 p-3 searchKey" onkeyup="" placeholder="Let's help you find it...">
                <span class='bg-white bs-none' id='searchProgress__span'>
                    <span>
                <!-- <div class="input-group-btn">
                        <button class="btn btn-outline-primary p-2" type="submit">
                            <i class="fa fa-search m-0 p-0"></i>
                        </button>
                    </div> -->
            </div>
            <div class="bg-white border mt-1 fs-md-1 text-left fw-normal ha_search__result d-none">

            </div>
            </p>
        </h3>

        <!-- Image 2 -->
        <img src="./assets/images/istockphoto-1224013294-612x612-removebg-preview 2.png" alt="header-pic-2" class="img-fluid header_parent-img-2">
    </header>

    <!-- Trending Ads -->
    <!-- <div class="Adspost py-5">
        <h1 class="Adspost_text">
           <b>Work in progress...</b> 
        </h1>
    </div> -->

    <section class="container px-4 px-md-0 mx-auto ">
        <div class="row ">
            <div class="col-sm-3 ha-category__height sticky-md-top col-md-3 col-lg-3 pt-4 shadow-sm border border-1 rounded-bottom mb-3 bg-white" style="">
                <!-- <span class="text-center fw-bold fs-5 ps-3"><i class="fa fa-list-alt"></i> Categories</span> -->
                <div class="row my-3">
                    <div id="accordian" class="">
                        <ul>
                            <?php
                            $getCategories = $adManager_ob->getAllMallParentCategory();
                            if ($getCategories['status'] == 1) {
                                foreach ($getCategories['message'] as  $value) {
                                    $childCategory = $adManager_ob->getCategChildByID($value['mallCategID']);
                            ?>
                                    <li class="">
                                        <h3><a href="javascript:void"><img src="assets/images/categoryicons/<?php echo $value['mallCategIcon'] ?>-48.png" class="img-fluid" style="width: 10%;"> <?php echo $value['mallCategName'] ?></a><label><?php echo number_format($adManager_ob->countCategoryHasChild($value['mallCategID'])['message']); ?> Ads</label>
                                        </h3>
                                        <?php
                                        if ($childCategory['status'] == 1) { ?>
                                            <ul>
                                                <?php
                                                $childCategoryEach = explode(",", $childCategory['message']);
                                                for ($i = 0; $i < count($childCategoryEach); $i++) {
                                                    $childCategName = $adManager_ob->getCategByID($childCategoryEach[$i]);
                                                    if ($childCategName['status'] != 1) {
                                                    } else {
                                                ?>
                                                        <li>
                                                            <a href="category.php?adcategory=<?php echo $childCategName['message'][0]['mallCategID']; ?>"><?php echo $childCategName['message'][0]['mallCategName']; ?>
                                                                <span class="fs-sm badge bg-danger"><?php echo number_format($adManager_ob->countCategory($childCategName['message'][0]['mallCategID'])['message']); ?> Ads</span></a>


                                                            <!-- <ul>
                                                                    <li><a href="#">Today's tasks</a></li>
                                                                    <li><a href="#">Urgent</a></li>
                                                                    <li>
                                                                        <a href="#">Overdues</a>
                                                                        <ul>
                                                                            <li><a href="#">Today's tasks</a></li>
                                                                            <li><a href="#">Urgent</a></li>
                                                                            <li><a href="#">Overdues</a></li>
                                                                            <li><a href="#">Recurring</a></li>
                                                                            <li><a href="#">Settings</a></li>
                                                                        </ul>
                                                                    </li>
                                                                    <li><a href="#">Recurring</a></li>
                                                                    <li><a href="#">Settings</a></li>
                                                                </ul> -->
                                                        </li>

                                                <?php    }
                                                } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                            <?php
                                }
                            } else {
                                echo "<li class='text-center'><a href='javascript:void' class='text-dark fs-title-2 text-center'>No Category found.</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 col-md-8 col-lg-9 ">
                <div class="row ">
                    <div class="col-sm-9 col-md-9 col-lg-9 shadow-sm p-0 mb-3 mb-md-0 mb-lg-0">
                        <div id="carouselExampleIndicators" class="carousel slide " data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="./assets/images/main_premium_services.jpg" class=" w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="./assets/images/main_premium_services.jpg" class="w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="./assets/images/main_premium_services.jpg" class="w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 shadow own-store d-table">
                        <div class="card-block d-table-cell align-middle py-4 py-md-0">
                            <a href="New_Ad.php">
                                <div class="row d-flex justify-content-center">
                                    <button class="rounded-circle btn btn-primary p-3 p-md-2 fs-1 fw-bolder bg-light own-store-icon" style="width: 80px; ">+</button>
                                </div>
                                <div class="row d-flex justify-content-center  fs-6 text-light text-center fw-bold">
                                    Start selling now
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <span class="fw-bold fs-5 ps-3 mt-5 mb-3"><i class="fa fa-shopping-cart"></i> Trending Adverts</span>
                    <?php
                    $getTrendingAds = $adManager_ob->getTrendingAds();
                    if ($getTrendingAds['status'] == 1) {
                        foreach ($getTrendingAds['message'] as $adsItems) {
                            $getImageCount = $adManager_ob->countAdImagesByID($adsItems['mallAdID']) ?>
                            <div class="col-6 col-md-3 g-1">
                                <div class="border rounded-3 bg-white shadow-sm d-table w-100 ha-items__list">
                                    <?php
                                    $thumbImageName = $mediaManager->getThumbImage($adsItems['mallAdID']);
                                    if ($thumbImageName['status'] == 1) {
                                        $thumbImageName = $thumbImageName['message']['mallMediaName'];
                                    } else {
                                        $thumbImageName = "";
                                    }
                                    ?>
                                    <div class="card-block d-table-row align-middle bg-primary ha-card-media ha-item-each__card ha-item-each__cardimg" datavalue="<?php echo $adsItems['mallAdID']; ?>" datavalueTitle="<?php echo str_replace(" ", "-", $adsItems['mallAdTitle']); ?>" style="background-image: url('../handlers/uploads/thumbs/<?php echo $thumbImageName ?>');">
                                        <div class="ha-card--h100p">
                                            <span class="ha-card__counter"><?php echo $getImageCount['message']; ?> <i class="fa fa-camera m-0"></i></span>
                                            <!-- <a class="ha-card-content-icon fw-bolder shadow-sm d-flex justify-content-center align-items-center" href="#">
                                                            <i class="fa fa-bookmark mx-auto fa-bounce"></i>
                                                    </a> -->
                                            <?php $adManager_ob::displayPromoted($adsItems['mallAdID'], $adsItems['mallAdPromoID']); ?>

                                        </div>

                                    </div>
                                    <div class="card-block d-table-row align-middlen ha-item__animate " style="position:relative; max-height:65px; height: 65px !important;">

                                        <a href="product.php?<?php echo str_replace(" ", "-", $adsItems['mallAdTitle']); ?>&adID=<?php echo $adsItems['mallAdID']; ?>">
                                            <div class="mx-2 my-2">
                                                <span class="d-block ha-fs__title ha-item__animate fs-sm"><?php echo (strlen($adsItems['mallAdTitle']) > 28) ? ucwords(substr($adsItems['mallAdTitle'], 0, 28)) . "..." : ucwords($adsItems['mallAdTitle']); ?></span>
                                                <span class="d-block ha-fs__price fw-bolder ha-item__animate"><span class="ha-currency__symbol"><?php echo $adManager_ob::CURRENCY; ?></span><?php echo number_format($adsItems['mallAdPrice'], 0, '.', ',') ?></span>


                                            </div>
                                        </a>
                                    </div>


                                </div>
                            </div>
                        <?php }
                    } else {
                        ?>
                        <div class="ha-none__display w-50 text-center m-5 mx-auto">

                            <img class="img-fluid mx-auto mb-4" src="./assets/images/notfound.svg">
                            <div class="fs-title-4 fw-bolder">No listing found</div>
                            <div class="fs-md">No content availiable</div>
                        </div>
                    <?php } ?>
                    <!--  -->


                </div>
            </div>
        </div>
    </section>

    <!-- Footer section -->
    <!-- <button type="button" class="fw-button qa-fw-button fw-button--type-success fw-button--size-little fw-button--circle fw-button--has-icon">
                                                                    <span class="fw-button__content">
                                                                        <svg stroke-width="0" class="tab-saved-outlined" style="width: 19px; height: 19px; max-width: 19px; max-height: 19px; fill: rgb(61, 184, 58);">
                                                                        <use xlink:href="#tab-saved-outlined"></use>
                                                                        </svg> 
                                                                    </span>
                                                                </button>  -->
    <?php include "footer.php"; ?>

    </div>


    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/vertical-menu.js"></script>
    <!-- <script src="./assets/js/main.js"></script>d -->
    <script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
    <script src="./assets/js/userAdmin.js"></script>
    <!-- <script src="./assets/js/main.js"></script> -->
    <script>
        $(document).mouseup(function(e) {
            var container = $('.ha_search__result');

            container.hide();

        });


        $(".searchKey").keyup(function() {
            var searchFormData = new FormData();
            var keyValue = $(this).val().trim();
            var resultPlaceholder = $('.ha_search__result');
            
            if (keyValue.length > 2) {
                searchFormData.append("searchAd", keyValue);
                $.ajax({
                    url: '../handlers/searchResults.php',
                    type: 'POST',
                    data: searchFormData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#searchProgress__span').addClass('input-group-text');
                        $('#searchProgress__span').html("<div class='spinner-border fs-sm text-secondary' role='status'><span class='visually-hidden'>Loading...</span></div>")
                        
                    },
                    success: function(data) {
                        if (!data.trim()) {
                            resultPlaceholder.hide();
                        } else {

                            resultPlaceholder.removeClass('d-none');
                            resultPlaceholder.show();
                            $(".ha_search__result").html(data);
                        }
                        $('#searchProgress__span').removeClass('input-group-text');
                        $('#searchProgress__span').html("");
                    }
                });
            } else {
                resultPlaceholder.hide();
            }
        })
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
    </script>
</body>

</html>