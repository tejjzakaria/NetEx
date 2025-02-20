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
          <span class="hide-menu">Home</span>
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
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-shopping-cart"></i>
            </span>
            <span class="hide-menu">Offres</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="offers.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List offres</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addOffer.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter offre</span>
              </a>
            </li>

          </ul>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Utilisateurs</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-user"></i>
            </span>
            <span class="hide-menu">Vendeurs</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewUsers.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List vendeurs</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addUser.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter vendeur</span>
              </a>
            </li>

          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-brand-appgallery"></i>
            </span>
            <span class="hide-menu">Boutiques</span>
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
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-users"></i>
            </span>
            <span class="hide-menu">Agents</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewAgents.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List agents</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addAgent.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter agent</span>
              </a>
            </li>

          </ul>
        </li>



        <!-- =================== -->
        <!-- UI Elements -->
        <!-- =================== -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Livraison</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-package"></i>
            </span>
            <span class="hide-menu">Colis</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewParcels.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List colis</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addParcels.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter colis</span>
              </a>
            </li>
            
          </ul>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Warehouse</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="viewProducts.php" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-ticket"></i>
            </span>
            <span class="hide-menu">Stock</span>
          </a>
          
        </li>
 

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-forklift"></i>
            </span>
            <span class="hide-menu">Sourcing</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewSuppliers.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List fournisseurs</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addSupplier.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter fournisseur</span>
              </a>
            </li>
            
          </ul>
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
            <span class="hide-menu">Leads</span>
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
            <li class="sidebar-item">
              <a href="import_lead.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Importer lead</span>
              </a>
            </li>

          </ul>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Paiments</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-wallet"></i>
            </span>
            <span class="hide-menu">CRBT Vendeurs</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewWallet.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List des demandes</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addTransaction.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter une demande</span>
              </a>
            </li>

          </ul>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-wallet"></i>
            </span>
            <span class="hide-menu">CRBT Agents</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewWalletAgent.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List des demandes</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addTransactionAgent.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter une demande</span>
              </a>
            </li>

          </ul>
        </li>

        

        

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Autre</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-bell"></i>
            </span>
            <span class="hide-menu">Notifications</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewNotifications.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List Notifications</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addNotification.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter Notification</span>
              </a>
            </li>

          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <span class="d-flex">
              <i class="ti ti-layers-intersect"></i>
            </span>
            <span class="hide-menu">Annonces</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="viewAnnouncements.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List Annonces</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="addAnnouncements.php" class="sidebar-link">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Ajouter Annonce</span>
              </a>
            </li>

          </ul>
        </li>





        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Contact</span>
          </>
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
            <span class="hide-menu">Feuilles de calcul G.S</span>
          </a>
        </li>

        <div class="alert alert-warning" style="margin-top: 10px;" role="alert">
          <strong>Version bêta : </strong>Vous pouvez rencontrer des problèmes techniques.
        </div>
    </nav>
    <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
      <div class="hstack gap-3">
        <div class="john-img">
          <img src="dist/images/profile/user-1.jpg" class="rounded-circle" width="40" height="40" alt="">
        </div>
        <div class="john-title">
          <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
          <span class="fs-2 text-dark">Designer</span>
        </div>
        <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button" aria-label="logout"
          data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
          <i class="ti ti-power fs-6"></i>
        </button>
      </div>
    </div>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
