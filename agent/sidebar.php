<?php
include "../config.php";

// Get the current request URI (full URL path including query parameters)
$current_url = $_SERVER['REQUEST_URI'];

// Extract only the filename from the URL, ignoring the query parameters
$current_page = basename(parse_url($current_url, PHP_URL_PATH));

// Check if the current page is 'offers.php'
$is_offers_page = $current_page === 'offers.php';

$sql = "SELECT COUNT(*) AS new FROM leads WHERE agent='pas encore attribué' OR agent='pas encore attributé'";
$result = mysqli_query($conn, $sql);
$newLeads = mysqli_fetch_assoc($result)['new'];
?>


<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="dashboard.php" class="text-nowrap logo-img">
        <img src="dist/images/logos/dark-logo.svg" class="dark-logo" width="130" alt="" />
        <img src="dist/images/logos/light-logo.svg" class="light-logo" width="130" alt="" />
      </a>
      <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8 text-muted"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">
        <!-- ============================= -->
        <!-- Home -->
        <!-- ============================= -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Accueil</span>
        </li>
        <!-- =================== -->
        <!-- Dashboard -->
        <!-- =================== -->
        <li class="sidebar-item">
          <a class="sidebar-link" href="dashboard.php" aria-expanded="false">
            <span>
              <i class="ti ti-home"></i>
            </span>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>







        <!-- =================== -->
        <!-- UI Elements -->
        <!-- =================== -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Livraison</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="viewParcels.php" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-package"></i>
            </span>
            <span class="hide-menu">Colis</span>
          </a>

        </li>







        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Centre d'appel</span>
        </li>



        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="viewLeads.php" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-star"></i>
              </span>
              <span class="hide-menu">Leads</span>
            </div>
            <div class="hide-menu">
              <span class="hide-menu badge bg-secondary fs-2 py-1 px-2"><?php echo $newLeads ?></span>
            </div>
          </a>

        </li>


        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Paiments</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="viewWallet.php" aria-expanded="false">
            <span>
              <i class="ti ti-wallet"></i>
            </span>
            <span class="hide-menu">CRBT</span>
          </a>
        </li>



        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Contact</span>
        </li>
        <!-- =================== -->
        <!-- Dashboard -->
        <!-- =================== -->
        <li class="sidebar-item">
          <a class="sidebar-link" href="support.php" aria-expanded="false">
            <span>
              <i class="ti ti-info-square-rounded"></i>
            </span>
            <span class="hide-menu">Support</span>
          </a>
        </li>





        <div class="alert alert-primary" style="margin-top: 10px; position: absolute; bottom: 0; margin-right: 20px;"
          role="alert">
          <span class="fs-3 fw-bold">Gestionnaire de compte: </span>
          <div class="hstack gap-3 mt-2">
            
            <div class="john-title">
              <h6 class="mb-0 fs-3">Abdellah AIT LHAJ</h6>
              <span class="mt-1">+212 687-866320</span>
            </div>
            <a href="https://wa.me/212687866320" target="_blank">
              <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button"
                aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="wtsp" data-bs-title="logout">
                <i class="ti ti-brand-whatsapp fs-6"></i>
              </button>
            </a>
          </div>
        </div>




    </nav>

    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>