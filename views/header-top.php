<?php
USE services\AccS\AccountManager;
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    if ($securityManager_ob->endUserSession()) {
        header("location: " . MALL_ROOT);
    } else {
        $sys_msg['msg_type'] = 500;
        $sys_msg['msg'] = "Could not log out";
    }
}

if (isset($_SESSION['gaijinmall_user_'])){
        if (!isset($getUsrBizInfo) || $getUsrBizInfo==""){
        $pageUsrID__ = $_SESSION['gaijinmall_user_'];
        $getUsrBizInfo = (new AccountManager())->getUsrBizInfoByID($pageUsrID__)['message'];
        }
}
?>
<div id="ha-header__top" class="row bg-light-blue px-0 px-md-5 px-lg-5 justify-content-center align-items-center fixed-top py-3 ">
    <!--IF NOT SIGNED IN SHOW THIS-->
    <div class="col-md-4 col-lg-4 col-4">
        <a href="./">
            <div class="logo" style="max-width: 130px;">
                <img src="./assets/images/logo-sm.png" class="img-fluid">
            </div>
        </a>


    </div>
    <div class="col-md-3 col-lg-3 col-1 text-center ha-social__top ">
        <div class="d-none d-md-block d-lg-block fs-6">
            COOL BUY & SELL
            <div id="google_translate_element"></div>
            <script type="text/javascript">
               function googleTranslateElementInit() {
                   new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');
               }
            </script>
            <script type="text/javascript" 
                    src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
            </script>
            <a href="#" class="flag_link" data-lang="ja"><img class="img-fluid" src="https://flagcdn.com/16x12/jp.png" alt=""></a>
            <a href="#" class="flag_link" data-lang="en"><img class="img-fluid" src="https://flagcdn.com/16x12/gb.png" alt=""></a>
        </div>
    </div>
    <div class="col-md-5 col-lg-5 col-5 text-end text-dark ha-fs_7 nav-container fw-bolder">
        <?php
        if ($securityManager_ob->is_user_auth__()) { ?>
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <ul class="">
                    <li><a class="active ha-nav__item" href="saved.php" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="My Saved Ads"><i class="fa fa-suitcase mx-auto"></i></a></li>
                    <li><a href="messages.php" class="ha-nav__item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="My Messages"><i class="fa fa-envelope mx-auto"></i></a></li>
                    <li><a href="notifications.php" class="ha-nav__item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Notifications"><i class="fa fa-bell mx-auto"></i></a></li>
                    <li><a href="adverts.php" class="ha-nav__item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="My Adverts"><i class="fa fa-bullhorn mx-auto"></i></a></li>
                    <li><a href="New_Ad.php" class="ha-nav__item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="New Advert">SELL</a></li>
                    <!-- <li><a href="New_Ad.php" class="ha-nav__item">PROMOTE AD</a></li> -->
                    <!-- <li class="dropdown">
                                <a href="#" class="ha-nav__profile" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">N</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li> -->
                    <li class="nav-item dropdown ha-dropdown__top">
                        <a class="ha-nav__profile"  href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user m-0"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="<?php echo ($getUsrBizInfo['mallBizSlug']=="" || $getUsrBizInfo['mallBizSlug']=="NULL")?"#" : "shop/".$getUsrBizInfo['mallBizSlug']; ?>">My Page</a></li>
                            <li><a class="dropdown-item" href="feedback" >Feedback</a></li>
                            <hr class="my-1">
                            <li><a class="dropdown-item" href="messages">My messages</a></li>
                            <li><a class="dropdown-item" href="personal_details_update">Settings</a></li>
                            <hr class="my-1">
                            <li><a class="dropdown-item fw-bolder" href="?logout=1">Log out</a></li>


                        </ul>
                    </li>
                </ul>
            </nav>



        <?php
        } else {
            echo '<a href="Signin.php" class="text-dark">Sign in</a>  | <a href="Signup.php" class="text-dark">Create Account</a>';
        }
        ?>

        <!--  -->
    </div>
    <!--IF NOT SIGNED IN SHOW THIS-->

</div>
<div class="mt-4 mt-md-5 mt-lg-5 mb-5 mb-md-5 mb-lg-5">
    <div class="mt-md-5 mt-lg-5">&nbsp;</div>
</div>

<!-- translation -->
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
<script src="./assets/js/translate.js"></script>
