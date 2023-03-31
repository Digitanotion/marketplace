<?php 
if (isset($_GET['logout'])&&$_GET['logout']==1){
    if ($securityManager_ob->endUserSession()){
        header("location: ".MALL_ROOT);
    }
    else{
        $sys_msg['msg_type']=500;
        $sys_msg['msg']="Could not log out";
    }
} 
?>
<div class="col-md-3 col-lg-3 col-sm-12 shadow-sm bg-white rounded-3 p-0">
    <div class="px-3 ha-profile__section mb-4">
        <div class="ha-image-profile__holder mx-auto <?php $usrIcon=$getUsrInfo['mallUsrPhoto']; echo ($usrIcon=="" || $usrIcon=="NULL")?"bg-purple":"";?> my-3"
            style="<?php $usrIcon=$getUsrInfo['mallUsrPhoto']; echo ($usrIcon=="" || $usrIcon=="NULL")?"":"background-image: url('../media_store/usrPictures/thumbs/$usrIcon');";?>">
            <?php $usrIcon=$getUsrInfo['mallUsrPhoto']; 
                    if ($usrIcon=="" || $usrIcon=="NULL"){
                        echo $getUsrBizInfo['mallBizName'][0];
                    }
                    ?>
        </div>
        <div class="mx-auto text-center">
            <span class="fs-title-2 fw-bolder"><?php echo $getUsrBizInfo['mallBizName'];?></span>
        </div>
        <div class="mx-auto text-center my-1">
            <span class="fs-md-1 badge bg-secondary"><?php echo $getUsrInfo['mallUsrPhoneNo'];?></span>
        </div>
    </div>
    <hr class="bg-light mb-0">
    <div class="bg-light-blue w-100 pt-3">
        <a href="adverts.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><i class="fa fa-bullhorn fs-title-2"></i> My Adverts</span>
                    <!-- <span class="badge bg-info">1</span> -->
                </div>
            </div>
        </a>
        <a href="feedback.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><a href="feedback.php" class="text-dark fs-md-1"><i
                                class="fa fa-smile-o  fs-title-2 fw-bold"></i> Feedback</a></span>
                </div>
            </div>
        </a>
        <a href="invite.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><a href="invite.php" class="text-dark fs-md-1"><i
                                class="fa fa-share fs-title-2 fw-bold"></i> Invite Friend</a></span>
                </div>
            </div>
        </a>
        <a href="messages.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><i class="fa fa-envelope fs-title-2 fw-bold"></i> My chats </span>
                </div>
            </div>
        </a>
        <a href="saved.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><i class="fa fa-suitcase  fs-title-2 fw-bold"></i> My saved Ads</span>
                </div>
            </div>
        </a>

    </div>

    <div class="bg-light-blue w-100 pt-3">
        <a href="personal_details_update.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><i class="fa fa-cog fs-title-2 "></i>Profile Settings</span>
                </div>
            </div>
        </a>
        <a href="faqs.php" class="text-dark">
            <div class="px-3 my-1 bg-white">
                <div class="ha-profile-menu__items">
                    <span class="fs-md"><i class="fa fa-question-circle fs-title-2 fw-bold"></i> FAQs</span>
                    <span class="badge bg-danger">New</span>
                </div>
            </div>
        </a>

    </div>

</div>