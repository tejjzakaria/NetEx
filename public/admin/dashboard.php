<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";


$sql = "SELECT SUM(price) AS total_balance FROM parcels2 WHERE status = 'Livré'";
$result = mysqli_query($conn, $sql);
$total_balance = mysqli_fetch_assoc($result)['total_balance'];

$sql = "SELECT SUM(comission) AS total_comission FROM parcels2 WHERE status = 'Livré'";
$result = mysqli_query($conn, $sql);
$total_comission = mysqli_fetch_assoc($result)['total_comission'];



$sql = "SELECT COUNT(*) AS delivered FROM parcels2 WHERE status='Livré'";
$result = mysqli_query($conn, $sql);
$deliveredParcels = mysqli_fetch_assoc($result)['delivered'];

$sql = "SELECT COUNT(*) AS returned FROM parcels2 WHERE status='Retourné'";
$result = mysqli_query($conn, $sql);
$returnedParcels = mysqli_fetch_assoc($result)['returned'];

$sql = "SELECT COUNT(*) AS cancelled FROM parcels2 WHERE status='Annulé'";
$result = mysqli_query($conn, $sql);
$cancelledParcels = mysqli_fetch_assoc($result)['cancelled'];

$sql = "SELECT COUNT(*) AS pending FROM parcels2 WHERE status='Expédié' OR status='nouveau'";
$result = mysqli_query($conn, $sql);
$pendingParcels = mysqli_fetch_assoc($result)['pending'];

$sql = "SELECT COUNT(*) AS unreachable FROM parcels2 WHERE status='injoignable'";
$result = mysqli_query($conn, $sql);
$unreachableParcels = mysqli_fetch_assoc($result)['unreachable'];

$sql = "SELECT COUNT(*) AS confirmed FROM leads WHERE status='Confirmer'";
$result = mysqli_query($conn, $sql);
$confirmedParcels = mysqli_fetch_assoc($result)['confirmed'];

$sql = "SELECT COUNT(*) AS new FROM leads WHERE status='nouveau' OR status='Nouveau colis'";
$result = mysqli_query($conn, $sql);
$newParcels = mysqli_fetch_assoc($result)['new'];

$sql = "SELECT COUNT(*) AS collected FROM leads WHERE status='rammasser' OR status='rammassé'";
$result = mysqli_query($conn, $sql);
$collectedParcels = mysqli_fetch_assoc($result)['collected'];

$sql = "SELECT COUNT(*) AS problems FROM leads WHERE status='Rappel' OR status='Appel X4' OR status='Boite Vocale' OR status='Pas de réponse' OR status='Occupé' OR status='Msj Wtsp'";
$result = mysqli_query($conn, $sql);
$problemsParcels = mysqli_fetch_assoc($result)['problems'];

$sql = "SELECT COUNT(*) AS user_count FROM user_info";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$usersCount = $row['user_count'];

$sql = "SELECT COUNT(*) AS agents_count FROM agent_info";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$agentsCount = $row['agents_count'];

$sql = "SELECT COUNT(*) AS invoices FROM agent_withdrawal_requests";
$result = mysqli_query($conn, $sql);
$invoicesCount = mysqli_fetch_assoc($result)['invoices'];

$sql = "SELECT COUNT(*) AS offer_count FROM offers";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$offerCount = $row['offer_count'];

$sql = "SELECT COUNT(*) AS leads_count FROM leads";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$leadsCount = $row['leads_count'];

$sql = "SELECT COUNT(*) AS parcels_count FROM parcels2";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$parcelsCount = $row['parcels_count'];

$sql = "SELECT * FROM user_info ORDER BY id DESC LIMIT 3";
$result = mysqli_query($conn, $sql);


$endDate = date("Y-m-d H:i:s");
$startDate = date("Y-m-d H:i:s", strtotime("-4 months", strtotime($endDate))); // 4 months ago

$currentTime = strtotime($startDate);
$endTime = strtotime($endDate);
$interval = "1 month"; // Use months for this type of chart

$timeCategories = []; // Array to hold month labels (e.g., "July", "August")
$chartData = [];
$statuses = ["Livré", "Retourné", "Annulé", "Expédié", "nouveau", "injoignable", "Confirmer", "ramasser", "rammassé"];

foreach ($statuses as $status) {
  $dataPoints = [];
  $currentTime = strtotime($startDate); // Reset time for each status
  while ($currentTime <= $endTime) {
    $time = date("Y-m-01 00:00:00", $currentTime); // First day of the month
    $monthLabel = date("F", $currentTime); // Month name for the category

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM leads WHERE status=? AND created_at >= ? AND created_at < DATE_ADD(?, INTERVAL 1 MONTH)");
    $stmt->bind_param("sss", $status, $time, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $stmt->close();


    if ($status == "Confirmer" || $status == "ramasser" || $status == "rammassé" || $status == "nouveau") {
      $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM leads WHERE status=? AND created_at >= ? AND created_at < DATE_ADD(?, INTERVAL 1 MONTH)");
      $stmt->bind_param("sss", $status, $time, $time); // "ss" for two strings
      $stmt->execute();
      $result = $stmt->get_result();  // Get the result
      $row = $result->fetch_assoc();  // Fetch the data
      $count = $row['count'];
      $stmt->close(); // Close the statement
    }

    $dataPoints[] = (int) $count;

    if (!in_array($monthLabel, $timeCategories)) { // Add category only once
      $timeCategories[] = $monthLabel;
    }

    $currentTime = strtotime("+1 month", $currentTime); // Increment by one month
  }
  $chartData[] = [
    "name" => $status,
    "data" => $dataPoints,
  ];
}

$chartDataJson = json_encode($chartData);



$userDataF = '';

while ($row = mysqli_fetch_assoc($result)) {
  $userDataF = '
            <div class="col-lg-4 col-md-6">
            <div class="card text-center">
              <div class="card-body">
                <div class="mt-n2">
                  <span class="badge bg-primary">' . $row['city'] . '</span>
                  <h3 class="card-title mt-3">' . $row['full_name'] . '</h3>
                  <h6 class="card-subtitle">' . $row['phone_number'] . '</h6>
                </div>

              </div>
            </div>
          </div>
    
    ' . $userDataF;

}

$sql = "SELECT withdrawal_requests.*, user_info.full_name 
        FROM withdrawal_requests 
        JOIN user_info ON withdrawal_requests.userID = user_info.id ORDER BY id DESC LIMIT 2";

$result = mysqli_query($conn, $sql);

$table_data2 = '';

while ($row = mysqli_fetch_assoc($result)) {

  if ($row['status'] == 'succès') {
    $status_class = "badge bg-success fw-semibold fs-2";
  } else if ($row['status'] == 'en attente') {
    $status_class = "badge bg-primary fw-semibold fs-2";
  } else if ($row['status'] == 'échoué') {
    $status_class = "badge bg-danger fw-semibold fs-2";
  } else {
    $status_class = "badge bg-primary fw-semibold fs-2";
  }



  $table_data2 = '
            <tr>
                
                <td>
                    <p class="mb-0 fw-normal">' . $row['tracking_id'] . '</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">' . $row['created_at'] . '</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">' . $row['full_name'] . '</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">' . $row['amount'] . ' Dhs</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">' . $row['comments'] . '</p>
                </td>

                <td>
                    <p class="mb-0 fw-normal">5 Dhs</p>
                </td>
                <td>
                    <span
                        class="' . $status_class . '">' . $row['status'] . '</span>
                </td>
                
                

                
                
                </tr>
    
    ' . $table_data2;

}


// Calculate delivery rate based on returned parcels
$deliveryRate = 0;
if ($deliveredParcels < $confirmedParcels) {
  $deliveryRate = round((($deliveredParcels / $confirmedParcels)) * 100, 2);
}
$confirmationRate = 0;
if ($confirmedParcels < $leadsCount) {
  $confirmationRate = round(($confirmedParcels / $leadsCount) * 100, 2);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!--  Title -->
  <title>Admin - Dashboard</title>
  <!--  Required Meta Tag -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="handheldfriendly" content="true" />
  <meta name="MobileOptimized" content="width" />
  <meta name="description" content="Mordenize" />
  <meta name="author" content="" />
  <meta name="keywords" content="Mordenize" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!--  Favicon -->
  <link rel="shortcut icon" type="image/png" href="dist/images/logos/favicon.ico" />
  <link id="themeColors" rel="stylesheet" href="dist/css/style.min.css" />
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <!-- Preloader -->
  <div class="preloader">
    <img src="dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <?php include 'sidebar.php'; ?>
    <!-- Sidebar End -->
    <!-- Main wrapper -->
    <div class="body-wrapper">
      <!-- Header Start -->
      <?php include 'header.php' ?>
      <!-- Header End -->
      <div class="container-fluid">
        <div class="alert alert-warning" role="alert">
          <strong>Avertissement concernant la version bêta : </strong>

          Bienvenue sur la version bêta de notre plateforme ! Veuillez noter qu'il s'agit d'une version préliminaire et
          que vous risquez
          de rencontrer des difficultés techniques, des bugs ou des fonctionnalités incomplètes lors de votre
          utilisation. Nous travaillons activement à
          améliorer et à perfectionner la plateforme en fonction de vos commentaires.

          Votre expérience et vos commentaires sont essentiels au processus de développement et nous apprécions votre
          compréhension et
          votre patience pendant que nous continuons à apporter des améliorations.

          Merci de nous aider à façonner le produit final !
        </div>


        <div class="row">
          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
              <div class="card-body position-relative">
                <div class="row">
                  <div class="col-sm-7">
                    <div class="d-flex align-items-center mb-7">
                      <div class="rounded-circle overflow-hidden me-6">
                        <img src="dist/images/profile/user-1.jpg" alt="" width="40" height="40">
                      </div>
                      <h5 class="fw-semibold mb-0 fs-5">Content de te revoir <?php echo $userData['username'] ?>!</h5>
                    </div>
                    <div class="d-flex align-items-center">
                      <div class="border-end pe-4 border-muted border-opacity-10">
                        <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center"><?php echo $leadsCount ?></h3>
                        <p class="mb-0 text-dark">Total Leads</p>
                      </div>
                      <div class="ps-4">
                        <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center"><?php echo $parcelsCount ?></h3>
                        <p class="mb-0 text-dark">Total Colis</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="welcome-bg-img mb-n7 text-end">
                      <img src="dist/images/backgrounds/welcome-bg.svg" alt="" class="img-fluid">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-4 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold">CRBT</h5>
                <p class="card-subtitle mb-0">Montant total des colis livrés</p>

                <div class="card shadow-none mb-0">
                  <div class="card-body p-0">
                    <div class="d-flex align-items-center mb-3">
                      <h2 class="fw-semibold mb-0"><?php echo $total_balance ?> MAD</h2>

                    </div>
                    <div class="d-flex align-items-center">
                      <span class="text-dark fs-3 fw-bold ms-1">Commissions</span>
                      <span class="text-success fw-bold fs-3 ms-2">(<?php echo $total_comission ?> MAD) </span>
                    </div>
                    <a href="viewWallet.php">
                      <button class="btn btn-light-primary text-primary w-100 mt-3"> Voir details </button></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <h5 class="fw-semibold mb-8">Statisiques livraison</h5>
          <div class="col-sm-6 col-xl-3">
            <div class="card bg-light-primary shadow-none">
              <div class="card-body p-4">
                <div class="d-flex align-items-center">
                  <div class="round rounded bg-primary d-flex align-items-center justify-content-center">

                    <i class="ti ti-clock-filled text-white fs-7"></i>
                  </div>
                  <h6 class="mb-0 ms-3">En attente</h6>
                  <div class="ms-auto text-primary d-flex align-items-center">
                    <i class="ti ti-package text-primary fs-6 me-1"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                  <h3 class="mb-0 fw-semibold fs-7"><?php echo $pendingParcels ?></h3>
                  <span class="fw-bold">Colis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card bg-light-danger shadow-none">
              <div class="card-body p-4">
                <div class="d-flex align-items-center">
                  <div class="round rounded bg-danger d-flex align-items-center justify-content-center">
                    <i class="ti ti-corner-down-left-double text-white fs-7"></i>
                  </div>
                  <h6 class="mb-0 ms-3">Annulé</h6>
                  <div class="ms-auto text-danger d-flex align-items-center">
                    <i class="ti ti-package text-danger fs-6 me-1"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                  <h3 class="mb-0 fw-semibold fs-7"><?php echo $cancelledParcels ?></h3>
                  <span class="fw-bold">Colis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card bg-light-success shadow-none">
              <div class="card-body p-4">
                <div class="d-flex align-items-center">
                  <div class="round rounded bg-success d-flex align-items-center justify-content-center">
                    <i class="ti ti-checkbox text-white fs-7"></i>

                  </div>
                  <h6 class="mb-0 ms-3">Livré</h6>
                  <div class="ms-auto text-info d-flex align-items-center">
                    <i class="ti ti-package text-success fs-6 me-1"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                  <h3 class="mb-0 fw-semibold fs-7"><?php echo $deliveredParcels ?></h3>
                  <span class="fw-bold">Colis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card bg-light-warning shadow-none">
              <div class="card-body p-4">
                <div class="d-flex align-items-center">
                  <div class="round rounded bg-warning d-flex align-items-center justify-content-center">
                    <i class="ti ti-rubber-stamp text-white fs-7"></i>
                  </div>
                  <h6 class="mb-0 ms-3">Injoignable</h6>
                  <div class="ms-auto text-info d-flex align-items-center">
                    <i class="ti ti-package text-warning fs-6 me-1"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                  <h3 class="mb-0 fw-semibold fs-7"><?php echo $unreachableParcels ?></h3>
                  <span class="fw-bold">Colis</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <h5 class="fw-semibold mb-8">Statisiques confirmation</h5>
          <div class="col-sm-6 col-xl-3">
            <a href="" class="p-4 text-center bg-light-success card shadow-none rounded-2">

              <p class="fw-semibold text-success mb-1">Confirmés</p>
              <h4 class="fw-semibold text-success mb-0"><?php echo $confirmedParcels ?></h4>
            </a>
          </div>
          <div class="col-sm-6 col-xl-3">
            <a href="" class="p-4 text-center bg-light-primary card shadow-none rounded-2">

              <p class="fw-semibold text-primary mb-1">Nouveau</p>
              <h4 class="fw-semibold text-primary mb-0"><?php echo $newParcels ?></h4>
            </a>
          </div>
          <div class="col-sm-6 col-xl-3">
            <a href="" class="p-4 text-center bg-light-warning card shadow-none rounded-2">

              <p class="fw-semibold text-warning mb-1">Rammassé</p>
              <h4 class="fw-semibold text-warning mb-0"><?php echo $collectedParcels ?></h4>
            </a>
          </div>
          <div class="col-sm-6 col-xl-3">
            <a href="" class="p-4 text-center bg-light-danger card shadow-none rounded-2">

              <p class="fw-semibold text-danger mb-1">Attente de suivi</p>
              <h4 class="fw-semibold text-danger mb-0"><?php echo $problemsParcels ?></h4>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5>Aperçu des statistiques des 5 derniers mois</h5>
                <div id="chart-bar-stacked"></div>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-info">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <h2 class="fs-7"><?php echo $usersCount ?></h2>
                    <h6 class="fw-medium text-info mb-0">Clients</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-info display-6"><i class="ti ti-user"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-primary">
              <div class="card-body">
                <div class="d-flex no-block align-items-center">
                  <div>
                    <h2 class="fs-7"><?php echo $invoicesCount ?></h2>
                    <h6 class="fw-medium text-primary mb-0">Factures</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-primary display-6"><i class="ti ti-clipboard"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-success">
              <div class="card-body">
                <div class="d-flex no-block align-items-center">
                  <div>
                    <h2 class="fs-7"><?php echo $agentsCount ?></h2>
                    <h6 class="fw-medium text-success mb-0">Agents</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-success display-6"><i class="ti ti-users"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-danger">
              <div class="card-body">
                <div class="d-flex no-block align-items-center">
                  <div>
                    <h2 class="fs-7"><?php echo $offerCount ?></h2>
                    <h6 class="fw-medium text-danger mb-0">Offres</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-danger display-6"><i class="ti ti-shopping-cart"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-7 col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div>
                    <h3 class="fs-6"><?php echo $deliveryRate ?>%</h3>
                    <h6 class="card-subtitle mb-1 text-muted">Taux de livraison</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-success display-6"><i class="ti ti-box"></i></span>
                  </div>
                </div>
                <div class="progress bg-light">
                  <div class="progress-bar bg-success" role="progressbar"
                    style="width: <?php echo $deliveryRate ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5 col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div>
                    <h3 class="fs-6"><?php echo $confirmationRate ?>%</h3>
                    <h6 class="card-subtitle mb-1 text-muted">Taux de confirmation</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-info display-6"><i class="ti ti-cast"></i></span>
                  </div>
                </div>
                <div class="progress bg-light">
                  <div class="progress-bar bg-info" role="progressbar"
                    style="width: <?php echo $confirmationRate ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>


        </div>
        <div class="row">


          <?php echo $userDataF ?>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title fw-semibold">Histoire des transactions (Clients)</h5>
            <p class="card-subtitle mb-0">Aperçu des dernières transactions</p>
            <div class="table-responsive mt-4">
              <table class="table table-borderless text-nowrap align-middle mb-0">
                <thead class="text-dark fs-4">
                  <tr>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Réf</h6>
                    </th>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Date & heure</h6>
                    </th>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Utilisateur</h6>
                    </th>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Montant</h6>
                    </th>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Commentaires</h6>
                    </th>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Frais</h6>
                    </th>
                    <th>
                      <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                    </th>


                  </tr>
                </thead>
                <tbody>
                  <?php echo $table_data2 ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <div class="dark-transparent sidebartoggler"></div>
  </div>
  <!--  Shopping Cart -->
  <div class="offcanvas offcanvas-end shopping-cart" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header py-4">
      <h5 class="offcanvas-title fs-5 fw-semibold" id="offcanvasRightLabel">Shopping Cart</h5>
      <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
    </div>
    <div class="offcanvas-body h-100 px-4 pt-0" data-simplebar>
      <ul class="mb-0">
        <li class="pb-7">
          <div class="d-flex align-items-center">
            <img src="dist/images/products/product-1.jpg" width="95" height="75" class="rounded-1 me-9 flex-shrink-0"
              alt="" />
            <div>
              <h6 class="mb-1">Supreme toys cooker</h6>
              <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
              <div class="d-flex align-items-center justify-content-between mt-2">
                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                <div class="input-group input-group-sm w-50">
                  <button class="btn border-0 round-20 minus p-0 bg-light-success text-success " type="button"
                    id="add1"> - </button>
                  <input type="text"
                    class="form-control round-20 bg-transparent text-muted fs-2 border-0  text-center qty"
                    placeholder="" aria-label="Example text with button addon" aria-describedby="add1" value="1" />
                  <button class="btn text-success bg-light-success  p-0 round-20 border-0 add" type="button" id="addo2">
                    + </button>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li class="pb-7">
          <div class="d-flex align-items-center">
            <img src="dist/images/products/product-2.jpg" width="95" height="75" class="rounded-1 me-9 flex-shrink-0"
              alt="" />
            <div>
              <h6 class="mb-1">Supreme toys cooker</h6>
              <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
              <div class="d-flex align-items-center justify-content-between mt-2">
                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                <div class="input-group input-group-sm w-50">
                  <button class="btn border-0 round-20 minus p-0 bg-light-success text-success " type="button"
                    id="add2"> - </button>
                  <input type="text"
                    class="form-control round-20 bg-transparent text-muted fs-2 border-0  text-center qty"
                    placeholder="" aria-label="Example text with button addon" aria-describedby="add2" value="1" />
                  <button class="btn text-success bg-light-success  p-0 round-20 border-0 add" type="button"
                    id="addon34"> + </button>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li class="pb-7">
          <div class="d-flex align-items-center">
            <img src="dist/images/products/product-3.jpg" width="95" height="75" class="rounded-1 me-9 flex-shrink-0"
              alt="" />
            <div>
              <h6 class="mb-1">Supreme toys cooker</h6>
              <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
              <div class="d-flex align-items-center justify-content-between mt-2">
                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                <div class="input-group input-group-sm w-50">
                  <button class="btn border-0 round-20 minus p-0 bg-light-success text-success " type="button"
                    id="add3"> - </button>
                  <input type="text"
                    class="form-control round-20 bg-transparent text-muted fs-2 border-0  text-center qty"
                    placeholder="" aria-label="Example text with button addon" aria-describedby="add3" value="1" />
                  <button class="btn text-success bg-light-success  p-0 round-20 border-0 add" type="button"
                    id="addon3"> + </button>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
      <div class="align-bottom">
        <div class="d-flex align-items-center pb-7">
          <span class="text-dark fs-3">Sub Total</span>
          <div class="ms-auto">
            <span class="text-dark fw-semibold fs-3">$2530</span>
          </div>
        </div>
        <div class="d-flex align-items-center pb-7">
          <span class="text-dark fs-3">Total</span>
          <div class="ms-auto">
            <span class="text-dark fw-semibold fs-3">$6830</span>
          </div>
        </div>
        <a href="./eco-checkout.html" class="btn btn-outline-primary w-100">Go to shopping cart</a>
      </div>
    </div>
  </div>
  <!--  Mobilenavbar -->
  <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="mobilenavbar"
    aria-labelledby="offcanvasWithBothOptionsLabel">
    <nav class="sidebar-nav scroll-sidebar">
      <div class="offcanvas-header justify-content-between">
        <img src="dist/images/logos/favicon.ico" alt="" class="img-fluid">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body profile-dropdown mobile-navbar" data-simplebar="" data-simplebar>
        <ul id="sidebarnav">
          <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span>
                <i class="ti ti-apps"></i>
              </span>
              <span class="hide-menu">Apps</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level my-3">
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-chat.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Chat Application</h6>
                    <span class="fs-2 d-block fw-normal text-muted">New messages arrived</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-invoice.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Invoice App</h6>
                    <span class="fs-2 d-block fw-normal text-muted">Get latest invoice</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-mobile.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Contact Application</h6>
                    <span class="fs-2 d-block fw-normal text-muted">2 Unsaved Contacts</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-message-box.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Email App</h6>
                    <span class="fs-2 d-block fw-normal text-muted">Get new emails</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-cart.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">User Profile</h6>
                    <span class="fs-2 d-block fw-normal text-muted">learn more information</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-date.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Calendar App</h6>
                    <span class="fs-2 d-block fw-normal text-muted">Get dates</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-lifebuoy.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Contact List Table</h6>
                    <span class="fs-2 d-block fw-normal text-muted">Add new contact</span>
                  </div>
                </a>
              </li>
              <li class="sidebar-item py-2">
                <a href="#" class="d-flex align-items-center">
                  <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                    <img src="dist/images/svgs/icon-dd-application.svg" alt="" class="img-fluid" width="24" height="24">
                  </div>
                  <div class="d-inline-block">
                    <h6 class="mb-1 bg-hover-primary">Notes Application</h6>
                    <span class="fs-2 d-block fw-normal text-muted">To-do and Daily tasks</span>
                  </div>
                </a>
              </li>
              <ul class="px-8 mt-7 mb-4">
                <li class="sidebar-item mb-3">
                  <h5 class="fs-5 fw-semibold">Quick Links</h5>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">Pricing Page</a>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">Authentication Design</a>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">Register Now</a>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">404 Error Page</a>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">Notes App</a>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">User Application</a>
                </li>
                <li class="sidebar-item py-2">
                  <a class="fw-semibold text-dark" href="#">Account Settings</a>
                </li>
              </ul>
            </ul>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="app-chat.html" aria-expanded="false">
              <span>
                <i class="ti ti-message-dots"></i>
              </span>
              <span class="hide-menu">Chat</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="app-calendar.html" aria-expanded="false">
              <span>
                <i class="ti ti-calendar"></i>
              </span>
              <span class="hide-menu">Calendar</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="app-email.html" aria-expanded="false">
              <span>
                <i class="ti ti-mail"></i>
              </span>
              <span class="hide-menu">Email</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
  <!-- Search Bar -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content rounded-1">
        <div class="modal-header border-bottom">
          <input type="search" class="form-control fs-3" placeholder="Search here" id="search" />
          <span data-bs-dismiss="modal" class="lh-1 cursor-pointer">
            <i class="ti ti-x fs-5 ms-3"></i>
          </span>
        </div>
        <div class="modal-body message-body" data-simplebar="">
          <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
          <ul class="list mb-0 py-2">
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Modern</span>
                <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Dashboard</span>
                <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Contacts</span>
                <span class="fs-3 text-muted d-block">/apps/contacts</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Posts</span>
                <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Detail</span>
                <span
                  class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Shop</span>
                <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Modern</span>
                <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Dashboard</span>
                <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Contacts</span>
                <span class="fs-3 text-muted d-block">/apps/contacts</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Posts</span>
                <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Detail</span>
                <span
                  class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
              </a>
            </li>
            <li class="p-1 mb-1 bg-hover-light-black">
              <a href="#">
                <span class="fs-3 text-black fw-normal d-block">Shop</span>
                <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Customizer -->

  <!-- Customizer -->
  <!-- Import Js Files -->
  <script src="dist/libs/jquery/dist/jquery.min.js"></script>
  <script src="dist/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- core files -->
  <script src="dist/js/app.min.js"></script>
  <script src="dist/js/app.init.js"></script>
  <script src="dist/js/app-style-switcher.js"></script>
  <script src="dist/js/sidebarmenu.js"></script>
  <script src="dist/js/custom.js"></script>
  <!-- current page js files -->
  <script src="dist/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="dist/js/dashboard4.js"></script>
  <script src="dist/js/apps/chat.js"></script>
  <script src="dist/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="dist/js/widgets-charts.js"></script>

  <script src="dist/libs/apexcharts/dist/apexcharts.min.js"></script>

  <script>
    var options_stacked = {
      series: <?php echo $chartDataJson; ?>,
      chart: {
        fontFamily: '"Nunito Sans", sans-serif',
        type: "bar",
        height: 350,
        stacked: true,
        toolbar: {
          show: false,
        },
      },
      grid: {
        borderColor: "transparent",
      },
      colors: ["var(--bs-primary)", "var(--bs-secondary)", "#ffae1f", "#fa896b", "#39b69a"],
      plotOptions: {
        bar: {
          horizontal: true,
        },
      },
      stroke: {
        width: 1,
        colors: ["#fff"],
      },
      xaxis: {
        categories: <?php echo json_encode($timeCategories); ?>,
        labels: {
          formatter: function (val) {
            return val;
          },
          style: {
            colors: [
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
            ],
          },
        },
      },
      yaxis: {
        title: {
          text: undefined,
        },
        labels: {
          style: {
            colors: [
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
              "#a1aab2",
            ],
          },
        },
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val;
          },
        },
        theme: "dark",
      },
      fill: {
        opacity: 1,
      },
      legend: {
        position: "top",
        horizontalAlign: "left",
        offsetX: 40,
        labels: {
          colors: ["#a1aab2"],
        },
      },
    };

    var chart_bar_stacked = new ApexCharts(
      document.querySelector("#chart-bar-stacked"),
      options_stacked
    );
    chart_bar_stacked.render();
  </script>
</body>

</html>