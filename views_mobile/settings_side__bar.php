<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Settings</title>
    <meta name="theme-color" content="#c3e6ff">
    <link rel="shortcut icon" href="./assets/images/favicon.png">
    <link rel="stylesheet" href="views/assets/css/personal-buiness.css">
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/node_modules/toastr/build/toastr.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/fonts/inter/style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="assets/css/seller.css">
    <link rel="stylesheet" href="./assets/css/vertical-menu.css">
    <link rel="stylesheet" href="./assets/css/adverts.css">
    <link rel="stylesheet" href="./assets/fonts/fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="assets/css/cute-alert.css">
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

<body>
    <div class="col-md-3 col-lg-3 col-sm-12 shadow-sm bg-white rounded-3 p-0 h-100">
        <div class="px-3 ha-profile__section m-3 ">
            <div class="mx-auto text-center">
                <span class="fs-title-2 fw-bolder">Settings</span>
            </div>
        </div>
        <hr class="bg-light mb-0">

        <div class="bg-light-blue w-100 pt-3">
            <a href="personal_details_update.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Personal details</span>
                    </div>
                </div>
            </a>
            <a href="business_information_update.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Business information</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-light-blue w-100 pt-3">
            <a href="user_phone_update.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Change phone number</span>
                    </div>
                </div>
            </a>
            <a href="user_email_update.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Change Email</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-light-blue w-100 pt-3">
            <a href="disable_chats.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Disable chats</span>
                    </div>
                </div>
            </a>
            <a href="disable_feedback.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Disable Feedback</span>
                    </div>
                </div>
            </a>
            <!-- <a href="manage_notifications.php" class="text-dark">
                        <div class="px-3 my-1 bg-white">
                            <div class="ha-profile-menu__items">
                                <span class="fs-md">Manage notifications</span>
                            </div>
                        </div>
                    </a> -->
        </div>

        <div class="bg-light-blue w-100 pt-3">
            <a href="change_password.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Change password</span>
                    </div>
                </div>
            </a>
            <a href="delete_account.php" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Delete my account permanently</span>
                    </div>
                </div>
            </a>
            <a href="?logout=1" class="text-dark">
                <div class="px-3 my-1 bg-white">
                    <div class="ha-profile-menu__items">
                        <span class="fs-md">Log out</span>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <!-- translation -->
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>

</body>

<script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
<script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dependencies/node_modules/toastr/build/toastr.min.js"></script>
<script src="./assets/js/vertical-menu.js"></script>
<script src="./assets/js/userAdmin.js"></script>
<script src="./assets/js/jquery-ui.js"></script>
<script src="./assets/js/settings.js"></script>

</html>
