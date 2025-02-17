<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

// Query to get the total commission for the agent
$sql_delivered_parcels = "SELECT COUNT(*) AS delivered_count
                           FROM parcels2
                           WHERE (status = 'livré' OR status = 'LIVRÉ' OR status = 'livre')
                           AND agent = '$agentName'";

$result_delivered_parcels = mysqli_query($conn, $sql_delivered_parcels);
$row_delivered = mysqli_fetch_assoc($result_delivered_parcels);
$delivered_count = $row_delivered['delivered_count'] ?? 0;

// Calculate the agent's total commission (fixed 10 dhs per parcel)
$agent_total_comission = $delivered_count * 10;

// Query to get the total withdrawals for the agent
$sql_agent_withdrawals = "SELECT SUM(amount) AS agent_withdrawals 
                          FROM agent_withdrawal_requests 
                          WHERE agent = '$agentName' AND status != 'ÉCHOUÉ'";

$result_agent_withdrawals = mysqli_query($conn, $sql_agent_withdrawals);
$row_withdrawals = mysqli_fetch_assoc($result_agent_withdrawals);
$agent_withdrawals = $row_withdrawals['agent_withdrawals'] ?? 0; // Use null coalescing operator to handle null values

// Calculate the agent's wallet balance
$agent_wallet_balance = $agent_total_comission - $agent_withdrawals;

$sql = "SELECT COUNT(*) AS invoices FROM agent_withdrawal_requests WHERE agent='$agentName'";
$result = mysqli_query($conn, $sql);
$invoicesCount = mysqli_fetch_assoc($result)['invoices'];






$sql = "SELECT COUNT(*) AS unassigned FROM leads WHERE agent='pas encore attribué'";
$result = mysqli_query($conn, $sql);
$unassignedLeads = mysqli_fetch_assoc($result)['unassigned'];

$sql = "SELECT COUNT(*) AS delivered FROM parcels2 WHERE agent='$agentName' AND status='LIVRÉ'";
$result = mysqli_query($conn, $sql);
$deliveredParcels = mysqli_fetch_assoc($result)['delivered'];

$sql = "SELECT COUNT(*) AS returned FROM parcels2 WHERE agent='$agentName' AND status='RETOURNÉ'";
$result = mysqli_query($conn, $sql);
$returnedParcels = mysqli_fetch_assoc($result)['returned'];

$sql = "SELECT COUNT(*) AS pending FROM parcels2 WHERE agent='$agentName' AND status='NOUVEAU'";
$result = mysqli_query($conn, $sql);
$pendingParcels = mysqli_fetch_assoc($result)['pending'];

$sql = "SELECT COUNT(*) AS confirmed FROM leads WHERE agent='$agentName' AND status='CONFIRMER'";
$result = mysqli_query($conn, $sql);
$confirmedParcels = mysqli_fetch_assoc($result)['confirmed'];



// Calculate delivery rate based on returned parcels
$deliveryRate = 0;
if ($returnedParcels < $deliveredParcels) {
  $deliveryRate = round((($deliveredParcels - $returnedParcels) / $deliveredParcels) * 100, 2);
}


$sql = "SELECT * FROM parcels2 WHERE agent='$agentName' ORDER BY id DESC LIMIT 2";
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
  } else {
    $state_class = "badge bg-secondary fw-semibold fs-2";
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
                        class="' . $state_class . '"></i>' . $row['state'] . '</span>
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

$sql = "SELECT * FROM agent_withdrawal_requests WHERE agent='$agentName' ORDER BY id DESC LIMIT 2";
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
          <strong>Avertissement concernant la version bêta : </strong>

          Bienvenue dans la version bêta de notre plateforme ! Veuillez noter
          qu'il s'agit d'une version préliminaire et que vous pouvez rencontrer des difficultés techniques, des bugs ou
          des fonctionnalités incomplètes lors de votre utilisation. Nous travaillons activement à l'amélioration et au
          perfectionnement de la plateforme en fonction de vos commentaires. Votre expérience et vos commentaires sont
          essentiels au processus de développement et nous apprécions votre compréhension et votre patience pendant que
          nous continuons à apporter des améliorations. Merci de nous aider à façonner le produit final !
        </div>
        <div class="row">
          <div class="col-lg-8">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
              <div class="card-body py-3">
                <div class="row justify-content-between align-items-center">
                  <div class="col-sm-6">
                    <h5 class="fw-semibold mb-9 fs-5">Content de te revoir <?php echo $agentName ?>!</h5>
                    <p class="mb-9" style="line-height: 1.6;">
                      Il y a <span class="badge text-bg-primary fs-2"
                        style="margin-left: 7px; margin-right: 7px;"><?php echo $unassignedLeads ?></span> leads non
                      attribuées, allez en chercher avant que quelqu'un d'autre ne le fasse.
                    </p>
                    <a href="viewLeads.php">
                      <button class="btn btn-primary">Voir les détails</button></a>
                  </div>
                  <div class="col-sm-5">
                    <div class="position-relative mb-n7 text-end">
                      <img src="dist/images/backgrounds/welcome-bg2.png" alt="" class="img-fluid">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 d-flex">
            <div class="card bg-success flex-fill">
              <div class="card-body text-white">
                <div class="d-flex">
                  <div
                    class="round-40 rounded-circle d-flex align-items-center justify-content-center bg-light-success text-success">
                    <i class="ti ti-credit-card fs-6"></i>
                  </div>
                  <div class="ms-3">
                    <h4 class="mb-0 text-white fs-6">Solde de Portfeuille</h4>
                    <span class="text-white-50">Montant total disponible dans le portefeuille</span>
                  </div>
                </div>

                <h2 class="fs-7 mb-0 text-white mt-9"><?php echo $agent_wallet_balance ?> Dhs</h2>


              </div>
            </div>
          </div>
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
                  <h6 class="mb-0 ms-3">Returné</h6>
                  <div class="ms-auto text-danger d-flex align-items-center">
                    <i class="ti ti-package text-danger fs-6 me-1"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                  <h3 class="mb-0 fw-semibold fs-7"><?php echo $returnedParcels ?></h3>
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
          <div class="col-xl-9">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title fw-semibold">Colis</h5>
                <p class="card-subtitle mb-0">Liste des dernières commandes</p>
                <div class="table-responsive rounded-2 mb-4" style="margin-top: 10px;">
                  <table class="table border text-nowrap customize-table mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">ID</h6>
                        </th>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">Nom</h6>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">Adresse</h6>
                        </th>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">Produit</h6>
                        </th>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">Etat</h6>
                        </th>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                        </th>
                        <th>
                          <h6 class="fs-4 fw-semibold mb-0">Commentaires</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php echo $table_data ?>




                    </tbody>
                  </table>
                </div>
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
                    <h3 class="fs-6"><?php echo $invoicesCount ?></h3>
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
        <div class="card">
          <div class="card-body">
            <h5 class="card-title fw-semibold">Histoire des transactions</h5>
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
  <script src="../../dist/js/apps/chat.js"></script>
  <script src="../../dist/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../../dist/js/widgets-charts.js"></script>
</body>

</html>