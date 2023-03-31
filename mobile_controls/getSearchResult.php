<?php
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

use services\AccS\AccountManager;
use services\SecS\SecurityManager;
use services\AdS\AdManager;


$security_ob = new SecurityManager();
$accManager_ob = new AccountManager();
$adManager_ob = new AdManager();
$pageUserID=$_POST['user_mob_id__'];
if (!empty($_POST["keyword"])) {
    $keyword = $_POST["keyword"];
    $limit = 8;
    $serach = "%" . $_POST['keyword'] . "%";

    $productResult = $adManager_ob->getProductByKeyword($serach, $limit);

    //$featuredProductResult = $adManager_ob->getFeaturedProductByKeyword($serach, $productResult[0]["featured"], Config::MIN_SEARCH_RESULT_COUNT);

    $categoryResult = $adManager_ob->getProductCategoryByKeyword($serach, $limit);

    if ($productResult['status'] == 1) {
        $results_count = count($productResult['message']);
?>
        <div class="d-flex justify-content-center my-2">
            <div class="search_result_note">We found <b><?php echo $results_count; ?></b> adverts for you. </div>
        </div>

        <?php
        foreach ($productResult['message'] as $value) {
            $getCategoryName = $adManager_ob->getCategInfoByID($value['mallCategID'])['message']['mallCategName'];
            $message_title = $value['mallAdTitle'];
            $message_desc = $value['mallAdDesc'];
            $size =0;
            $word = $keyword;
            $words_res=ucfirst(preg_replace(
                '/^.*?(.{0,' . $size . '})(\b\S*' . $word . '\S*\b)(.{0,' . $size . '}).*?$/i',
                '$1$2$3',
                $message_title." ". $message_desc
            ));
        ?>
            <div class="col-12 py-2 search_each__containter">
                <a href="category_search.php?adcategory=<?php echo $value['mallCategID'];?>&adSearchWord=<?php echo $words_res;?>&user_mob_id__=<?php echo $pageUserID;?> " class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="search_img__placeholder" style="background-image: url(../handlers/uploads/thumbs/abc_097497260153805_129263_30117001.png);"></div>
                        <div class="search_title fs-md-1"><?php echo (strlen($words_res) >= 30) ? $words_res . "..." : $words_res . " ";
                                                            echo " <span class='text-primary fw-bold'>in $getCategoryName</span>" ?> </div>
                    </div>
                    <div class="search_arrow_right"><img src="assets/images/arrow_right.svg"></div>
                </a>
            </div>
<?php
        }

        //echo "Results found";
    } else if ($productResult['status'] == 0) {
        echo "Results not found";
    }
}
?>