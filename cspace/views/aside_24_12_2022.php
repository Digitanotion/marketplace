<?php

//Import Services, AdManager
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
  require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} elseif (strpos($url, '192.168.')) {
  require_once(__DIR__ . "\../../vendor/autoload.php");
} 
use services\AdS\AdManager;
use services\SecS\SecurityManager;
use services\AccS\AccountManager;

$accManager = new AccountManager();



$details = $accManager->getAdminDetailsByID($_SESSION['gaijinmall_user_admin__']);

if ($details['status'] == 1) {
  $fullName = $details['message']['mallUsrFirstName'] . " " . $details['message']['mallUsrLastName'];
} else {
  $fullName = "Not found";
}




echo '<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="cspace.php" class="brand-link bg-light">
  <img src="./assets/images/logo-sm.png" alt="Logo" class="img-fluid" style="opacity: .8;">
</a>

<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img src="./assets/images/Emoji.png" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
      <a href="#" class="d-block">' . $fullName . '</a>
    </div>
  </div>

  <!-- SidebarSearch Form -->
  <div class="form-inline">
    <div class="input-group" data-widget="sidebar-search">
      <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-sidebar">
          <i class="fas fa-search fa-fw"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="cspace" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Dashboard
          </p>
        </a>
       
      </li>
      <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>
                Users Managerment
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="./users.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>All Users</p>
                  </a>
             </li>
              <li class="nav-item">
                  <a href="./emails.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Email verification</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="./phones.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Phone verification</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="./kycs.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>KYC verification</p>
                  </a>
              </li>
              
            </ul>
      </li>
      <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Adverts
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="ads.php" class="nav-link">
                    <i class="far  nav-icon"></i>
                    <p>All Adverts</p>
                  </a>
                </li>

                <li class="nav-item">
                <a href="premium.php" class="nav-link">
                  <i class="far  nav-icon"></i>
                  <p>Promoted Adverts</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="promotions.php" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Add promotion plans</p>
                </a>
              </li>
              
              </ul>
      </li>
      <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Manage Category
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <!--  <li class="nav-item">
                    <a href="../examples/login.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Category</p>
                    </a>
                  </li> -->
                  <li class="nav-item">
                    <a href="?lid=<?php echo $secManager->CSRFToken?>&push=addcateg" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>All Category</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="?lid=<?php echo $secManager->CSRFToken?>&push=categopt" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Category Options </p>
                    </a>
                  </li>
                
                </ul>
      </li>
       <!-- <li class="nav-item">
        <a href="./registration.php" class="nav-link">
          <i class="far fa-circle nav-icon"></i>
          <p>Register new admin
          </p>
        </a>
      </li> -->
      <li class="nav-item">
      <a href="?logout=1" class="nav-link text-danger fw-bolder">
         <i class="far fa-circle nav-icon"></i>
          <p>Log out
          </p>
      </a>
      </li>
    </ul>
        
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
';
