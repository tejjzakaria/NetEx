<?php
include "../config.php";
// Get the current request URI (full URL path including query parameters)
$current_url = $_SERVER['REQUEST_URI'];

// Extract only the filename from the URL, ignoring the query parameters
$current_page = basename(parse_url($current_url, PHP_URL_PATH));

// Check if the current page is 'offers.php'
$is_offers_page = $current_page === 'offers.php';


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

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Marketplace</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?php echo $is_offers_page ? 'active' : ''; ?>" href="offers.php?page=1"
            aria-expanded="false">
            <span>
              <i class="ti ti-shopping-cart"></i>
            </span>
            <span class="hide-menu">Offres</span>
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
            <span class="hide-menu">Mes Colis</span>
          </a>

        </li>



        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Warehouse</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="viewProducts.php" aria-expanded="false">
            <span>
              <i class="ti ti-ticket"></i>
            </span>
            <span class="hide-menu">Mon Stock</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-brand-appgallery"></i>
            </span>
            <span class="hide-menu">Mes Boutiques</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewStores.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List boutiques</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addStore.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter boutique</span>
              </a>
            </li>

          </ul>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="viewSuppliers.php" aria-expanded="false">
            <span>
              <i class="ti ti-forklift"></i>
            </span>
            <span class="hide-menu">Sourcing</span>
          </a>
        </li>



        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Centre d'appel</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-star"></i>
            </span>
            <span class="hide-menu">Mes Leads</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewLeads.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List leads</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a href="addLead.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter lead</span>
              </a>
            </li>

          </ul>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="import_lead.php" aria-expanded="false">
            <span>
              <i class="ti ti-arrow-bar-up"></i>
            </span>
            <span class="hide-menu">Importer lead</span>
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
            <span class="hide-menu">Mon Portfeuille</span>
          </a>
        </li>


        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">API</span>
        <li class="sidebar-item">
          <a class="sidebar-link" href="docsApi.php" aria-expanded="false">
            <span>
              <i class="ti ti-api-app"></i>
            </span>
            <span class="hide-menu">Google Sheets API Doc</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="viewSpreadsheets.php" aria-expanded="false">
            <span>
              <i class="ti ti-csv"></i>
            </span>
            <span class="hide-menu">Mes Feuilles de calcul G.S</span>
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

        <div class="alert alert-primary mt-6" role="alert">
          <span class="fs-3 fw-semibold">Gestionnaire de compte: </span>
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