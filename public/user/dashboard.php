<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";



$endDate = date("Y-m-d H:i:s");
$startDate = date("Y-m-d H:i:s", strtotime("-4 months", strtotime($endDate))); // 4 months ago

$currentTime = strtotime($startDate);
$endTime = strtotime($endDate);
$interval = "1 month"; // Use months for this type of chart

$timeCategories = []; // Array to hold month labels (e.g., "July", "August")
$chartData = [];
$statuses = ["Livré", "Retourné", "Annulé", "Expédié", "nouveau", "injoignable", "Confirmer", "ramasser", "rammassé"];

$userID = $_SESSION['userID']; // Get the logged-in user's ID

foreach ($statuses as $status) {
    $dataPoints = [];
    $currentTime = strtotime($startDate); // Reset time for each status
    
    while ($currentTime <= $endTime) {
        $time = date("Y-m-01 00:00:00", $currentTime); // First day of the month
        $monthLabel = date("F", $currentTime); // Month name for the category

        // Query to count status for the logged-in user
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM leads WHERE userID = ? AND status = ? AND created_at >= ? AND created_at < DATE_ADD(?, INTERVAL 1 MONTH)");
        $stmt->bind_param("isss", $userID, $status, $time, $time);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

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








// Initialize total withdrawals count variable
$total_requests = 0;

// SQL query to count the number of rows in the withdrawal_requests table for the user
$sql_count = "SELECT COUNT(*) AS total_requests FROM withdrawal_requests WHERE userID = ?";
if ($stmt = $conn->prepare($sql_count)) {
  $stmt->bind_param("i", $userID);  // Bind the userID to the query
  $stmt->execute();
  $stmt->bind_result($total_requests);  // Bind the result to the $total_requests variable
  $stmt->fetch();
  $stmt->close();
}

$sql = "SELECT COUNT(*) AS delivered FROM parcels2 WHERE userID='$userID' AND status='Livré'";
$result = mysqli_query($conn, $sql);
$deliveredParcels = mysqli_fetch_assoc($result)['delivered'];

$sql = "SELECT COUNT(*) AS returned FROM parcels2 WHERE userID='$userID' AND status='Retourné'";
$result = mysqli_query($conn, $sql);
$returnedParcels = mysqli_fetch_assoc($result)['returned'];

$sql = "SELECT COUNT(*) AS cancelled FROM parcels2 WHERE userID='$userID' AND status='Annulé'";
$result = mysqli_query($conn, $sql);
$cancelledParcels = mysqli_fetch_assoc($result)['cancelled'];

$sql = "SELECT COUNT(*) AS pending FROM parcels2 WHERE userID='$userID' AND status='nouveau'";
$result = mysqli_query($conn, $sql);
$pendingParcels = mysqli_fetch_assoc($result)['pending'];

$sql = "SELECT COUNT(*) AS confirmed FROM leads WHERE userID='$userID' AND status='Confirmer'";
$result = mysqli_query($conn, $sql);
$confirmedParcels = mysqli_fetch_assoc($result)['confirmed'];

$sql = "SELECT COUNT(*) AS leads_count FROM leads WHERE userID='$userID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$leadsCount = $row['leads_count'];

$deliveryRate = 0;
if ($deliveredParcels < $confirmedParcels) {
  $deliveryRate = round((($deliveredParcels / $confirmedParcels)) * 100, 2);
}
if ($leadsCount > 0) {
  $confirmationRate = round(($confirmedParcels / $leadsCount) * 100, 2);
} else {
  $confirmationRate = 0; // Default value when there are no leads
}

$sql = "SELECT * FROM parcels2 WHERE userID='$userID' ORDER BY id DESC LIMIT 2";
$result = mysqli_query($conn, $sql);
$status = '';

$table_data = '';

while ($row = mysqli_fetch_assoc($result)) {

  $product_name = $row['product'];
  $sql_comission = "SELECT product_comission FROM stock_requests WHERE product_name = ?";

  if ($stmt = mysqli_prepare($conn, $sql_comission)) {
    mysqli_stmt_bind_param($stmt, "s", $product_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $product_comission);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
  }




  if ($row['state'] == 'payé') {
    $state_class = "badge bg-success fw-semibold fs-2";
  } else if ($row['state'] == 'non payé') {
    $state_class = "badge bg-danger fw-semibold fs-2";
  }

  if ($row['status'] == 'livré') {
    $status_class = "badge bg-success fw-semibold fs-2";
  } else if ($row['status'] == 'refusé') {
    $status_class = "badge bg-danger fw-semibold fs-2";
  } else if ($row['status'] == 'annulé') {
    $status_class = "badge bg-danger fw-semibold fs-2";
  } else if ($row['status'] == 'pas de réponse') {
    $status_class = "badge bg-warning fw-semibold fs-2";
  } else if ($row['status'] == 'occupé') {
    $status_class = "badge bg-warning fw-semibold fs-2";
  } else if ($row['status'] == 'annulé') {
    $status_class = "badge bg-warning fw-semibold fs-2";
  } else if ($row['status'] == 'message whatsapp') {
    $status_class = "badge bg-warning fw-semibold fs-2";
  } else {
    $status_class = "badge bg-primary fw-semibold fs-2";
  }



  $table_data = '
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                            <h6 class="fs-4 fw-normal mb-0">' . $row['tracking_id'] . '</h6>
                            <span class="fw-normal">' . $row['created_at'] . '</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                            <h6 class="fs-4 fw-normal mb-0">' . $row['name'] . '</h6>
                            <span class="fw-normal">' . $row['phone_number'] . '</span>
                        </div>
                    </div>
                </td>
                
                
                
                <td>
                    <span
                        class="' . $status_class . '">' . $row['status'] . '</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                            <h6 class="fs-4 fw-normal mb-0">' . $row['address'] . ',</h6>
                            <span class="fw-normal">' . $row['city'] . '</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                            <h6 class="fs-4 fw-normal mb-0">' . $row['product'] . '</h6>
                            <span class="fw-normal">' . $row['price'] . ' Dhs</span>
                            
                        </div>
                    </div>
                </td>
                
                <td>
                    <p class="mb-0 fw-normal">' . $row['comments'] . '</p>
                </td>
                
                

                
                
                </tr>
      
    ' . $table_data;

}

$sql = "SELECT * FROM withdrawal_requests WHERE userID='$userID' ORDER BY id DESC LIMIT 2";
$result = mysqli_query($conn, $sql);
$status = '';

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
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!--  Title -->
  <title>Dashboard</title>
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
        <div class="alert alert-warning" role="alert" style="line-height: 2;">
          <strong>Beta Version Warning: </strong>

          Welcome to the beta version of our platform! Please be aware that this is a pre-release version, and you may
          experience technical difficulties, bugs, or incomplete features during your use. We are actively working to
          improve and enhance the platform based on your feedback.

          Your experience and input are vital to the development process, and we appreciate your understanding and
          patience as we continue to make improvements.

          Thank you for helping us shape the final product!
        </div>
        <div class="row">
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
                  <h6 class="mb-0 ms-3">Confirmé</h6>
                  <div class="ms-auto text-info d-flex align-items-center">
                    <i class="ti ti-package text-warning fs-6 me-1"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                  <h3 class="mb-0 fw-semibold fs-7"><?php echo $confirmedParcels ?></h3>
                  <span class="fw-bold">Colis</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-9">
            <div class="card">
              <div class="card-body">
                <h5>Aperçu des statistiques des 5 derniers mois</h5>
                <div id="chart-bar-stacked"></div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 mt-4 mt-md-0">


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
            <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div>
                    <h3 class="fs-6"><?php echo $confirmationRate ?>%</h3>
                    <h6 class="card-subtitle mb-1 text-muted">Taux de confirmation</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-secondary display-6"><i class="ti ti-headset"></i></span>
                  </div>
                </div>
                <div class="progress bg-light">
                  <div class="progress-bar bg-secondary" role="progressbar"
                    style="width: <?php echo $confirmationRate ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div>
                    <h3 class="fs-6"><?php echo $total_requests ?></h3>
                    <h6 class="card-subtitle mb-1 text-muted">Factures</h6>
                  </div>
                  <div class="ms-auto">
                    <span class="text-info display-6"><i class="ti ti-clipboard"></i></span>
                  </div>
                </div> 

              </div>
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