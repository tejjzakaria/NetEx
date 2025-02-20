<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Connect to the database
include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

$sql = "SELECT * FROM leads WHERE agent = '$agentName' OR agent = 'pas encore attribué' OR agent = 'pas encore attributé'";
$result = mysqli_query($conn, $sql);
$status = '';

$table_data = '';


while ($row = mysqli_fetch_assoc($result)) {



    if ($row['status'] == 'CONFIRMER' or $row['status'] == 'RAMMASSER') {
        $status_class = "badge bg-success fw-semibold fs-2";
    } else if ($row['status'] == 'RAPPEL') {
        $status_class = "badge bg-warning fw-semibold fs-2";
    } else if ($row['status'] == 'BOITE VOCALE') {
        $status_class = "badge bg-danger fw-semibold fs-2";
    } else if ($row['status'] == 'PAS DE RÉPONSE') {
        $status_class = "badge bg-warning fw-semibold fs-2";
    } else if ($row['status'] == 'OCCUPÉ') {
        $status_class = "badge bg-warning fw-semibold fs-2";
    } else if ($row['status'] == 'ANNULÉ') {
        $status_class = "badge bg-warning fw-semibold fs-2";
    } else if ($row['status'] == 'MESSAGE WHATSAPP') {
        $status_class = "badge bg-warning fw-semibold fs-2";
    } else {
        $status_class = "badge bg-primary fw-semibold fs-2";
    }


    // Check if the agent is 'pas encore attribué'
    $assign_button = '';
    if ($row['agent'] == 'pas encore attribué') {
        $assign_button = '
            <li>
                <a class="dropdown-item d-flex align-items-center gap-3" href="assignLead.php?id=' . $row['id'] . '">
                    <i class="fs-4 ti ti-user-plus"></i>Assignez-vous
                </a>
            </li>';
    }

    $modify_button = '';
    if ($row['agent'] != 'pas encore attribué') {
        $modify_button = '
            <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="editLead.php?id=' . $row['id'] . '"><i
                                        class="fs-4 ti ti-edit"></i>Modifer info</a>
                            </li>';
    }



    $table_data = '
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-0" style="max-width: 300px; word-wrap: break-word; white-space: normal;">
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
                    <div class="d-flex align-items-center">
                        <div class="ms-0">
                            <h6 class="fs-4 fw-normal mb-0" style="max-width: 400px; word-wrap: break-word; white-space: normal;">' . $row['address'] . ',</h6>
                            <span class="fw-normal" style="max-width: 400px; word-wrap: break-word; white-space: normal;">' . $row['city'] . '</span>
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
                    <span
                        class="badge bg-secondary fw-semibold fs-2" style="line-height: 1.5; max-width: 300px; word-wrap: break-word; white-space: normal;">' . $row['agent'] . '</span>
                </td>
                
                
                
                <td>
                    <span
                        class="' . $status_class . '" style="line-height: 1.5; max-width: 300px; word-wrap: break-word; white-space: normal;">' . $row['status'] . '</span>
                </td>
                
                <td>
                    <p class="mb-0 fw-normal" style="max-width: 400px; word-wrap: break-word; white-space: normal;">' . $row['comments'] . '</p>
                </td>
                <td>
                    <div class="dropdown dropstart">
                        <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ti ti-dots fs-5"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButto"
                        <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="#" onclick="viewLead(' . $row['id'] . ')"><i
                                        class="fs-4 ti ti-eye"></i>Details</a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="#" onclick="viewFollowUp(' . $row['id'] . ')"><i
                                        class="fs-4 ti ti-info-square-rounded"></i>Suivi</a>
                            </li>
                            ' . $modify_button . '
                            ' . $assign_button . '
                        </ul>
                    </div>
                </td>
                

                
                
                </tr>
    
    ' . $table_data;

}

$sql = "SELECT COUNT(*) AS confirmed FROM leads WHERE status='CONFIRMER' AND agent = '$agentName'";
$result = mysqli_query($conn, $sql);
$confirmedParcels = mysqli_fetch_assoc($result)['confirmed'];

$sql = "SELECT COUNT(*) AS new FROM leads WHERE (status='NOUVEAU' OR status='NOUVEAU COLIS') AND agent = '$agentName'";
$result = mysqli_query($conn, $sql);
$newParcels = mysqli_fetch_assoc($result)['new'];

$sql = "SELECT COUNT(*) AS collected FROM leads WHERE (status='RAMMASSER' OR status='rammassé') AND agent = '$agentName'";
$result = mysqli_query($conn, $sql);
$collectedParcels = mysqli_fetch_assoc($result)['collected'];

$sql = "SELECT COUNT(*) AS problems FROM leads WHERE (status='RAPPEL' OR status='APPEL X4' OR status='BOITE VOCALE' OR status='PAS DE RÉPONSE' OR status='OCCUPÉ' OR status='MSJ WTSP') AND agent = '$agentName'";
$result = mysqli_query($conn, $sql);
$problemsParcels = mysqli_fetch_assoc($result)['problems'];

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Leads</title>
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
    <link rel="stylesheet" href="dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="dist/libs/sweetalert2/dist/sweetalert2.min.css">

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

                <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="fw-semibold mb-8">Leads</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="viewLeads.php">List Leads</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Voir Tous</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-3">
                                <div class="text-center mb-n5">
                                    <img src="dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
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

                <div class="d-flex align-items-center">
                    <button class="btn btn-secondary mb-3" id="syncButton"><i class="ti ti-refresh"
                            style="margin-right: 6px;"></i>Synchroniser les données</button>
                </div>
                <div class="datatables">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table border text-nowrap customize-table mb-0 align-middle"
                                            id="leads_table">
                                            <thead class="text-dark fs-4">
                                                <tr>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">ID</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Nom</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Adresse</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Produit</h6>
                                                    </th>

                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Agent</h6>
                                                    </th>

                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                                                    </th>

                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Commentaires</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Actions</h6>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $table_data ?>
                                            </tbody>

                                            <tfoot>
                                                <!-- start row -->
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <!-- end row -->
                                            </tfoot>
                                        </table>
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
                        <img src="dist/images/products/product-1.jpg" width="95" height="75"
                            class="rounded-1 me-9 flex-shrink-0" alt="" />
                        <div>
                            <h6 class="mb-1">Supreme toys cooker</h6>
                            <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button class="btn border-0 round-20 minus p-0 bg-light-success text-success "
                                        type="button" id="add1"> - </button>
                                    <input type="text"
                                        class="form-control round-20 bg-transparent text-muted fs-2 border-0  text-center qty"
                                        placeholder="" aria-label="Example text with button addon"
                                        aria-describedby="add1" value="1" />
                                    <button class="btn text-success bg-light-success  p-0 round-20 border-0 add"
                                        type="button" id="addo2">
                                        + </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="pb-7">
                    <div class="d-flex align-items-center">
                        <img src="dist/images/products/product-2.jpg" width="95" height="75"
                            class="rounded-1 me-9 flex-shrink-0" alt="" />
                        <div>
                            <h6 class="mb-1">Supreme toys cooker</h6>
                            <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button class="btn border-0 round-20 minus p-0 bg-light-success text-success "
                                        type="button" id="add2"> - </button>
                                    <input type="text"
                                        class="form-control round-20 bg-transparent text-muted fs-2 border-0  text-center qty"
                                        placeholder="" aria-label="Example text with button addon"
                                        aria-describedby="add2" value="1" />
                                    <button class="btn text-success bg-light-success  p-0 round-20 border-0 add"
                                        type="button" id="addon34"> + </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="pb-7">
                    <div class="d-flex align-items-center">
                        <img src="dist/images/products/product-3.jpg" width="95" height="75"
                            class="rounded-1 me-9 flex-shrink-0" alt="" />
                        <div>
                            <h6 class="mb-1">Supreme toys cooker</h6>
                            <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button class="btn border-0 round-20 minus p-0 bg-light-success text-success "
                                        type="button" id="add3"> - </button>
                                    <input type="text"
                                        class="form-control round-20 bg-transparent text-muted fs-2 border-0  text-center qty"
                                        placeholder="" aria-label="Example text with button addon"
                                        aria-describedby="add3" value="1" />
                                    <button class="btn text-success bg-light-success  p-0 round-20 border-0 add"
                                        type="button" id="addon3"> + </button>
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
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-chat.svg" alt="" class="img-fluid" width="24"
                                            height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Chat Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">New messages arrived</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-invoice.svg" alt="" class="img-fluid"
                                            width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Invoice App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get latest invoice</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-mobile.svg" alt="" class="img-fluid"
                                            width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Contact Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">2 Unsaved Contacts</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-message-box.svg" alt="" class="img-fluid"
                                            width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Email App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get new emails</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-cart.svg" alt="" class="img-fluid" width="24"
                                            height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">User Profile</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">learn more information</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-date.svg" alt="" class="img-fluid" width="24"
                                            height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Calendar App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get dates</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-lifebuoy.svg" alt="" class="img-fluid"
                                            width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Contact List Table</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Add new contact</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="dist/images/svgs/icon-dd-application.svg" alt="" class="img-fluid"
                                            width="24" height="24">
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

    <script src="dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/datatable/datatable-api.init.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#leads_table').DataTable({
                "paging": true,
                "scrollX": true,
                "scrollY": true,
                "searching": true,
                "ordering": true,
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Afficher _MENU_ entrées",
                    "zeroRecords": "Aucun enregistrement trouvé",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                    "infoEmpty": "Aucune entrée disponible",
                    "infoFiltered": "(filtré de _MAX_ entrées au total)",
                    "search": "Rechercher:",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    }
                }
            });

            // Specify the columns where you want to add the select input (0-based index)
            var targetColumns = [4, 5]; // 2nd, 5th, and 6th columns

            // Iterate through the specified columns
            targetColumns.forEach(function (colIdx) {
                // Create the select list and search operation
                var select = $('<select class="form-select"><option value="">Selectionner un option</option></select>')
                    .appendTo(
                        table.column(colIdx).footer() // Append to the footer of the specific column
                    )
                    .on('change', function () {
                        table
                            .column(colIdx)
                            .search($(this).val())
                            .draw();
                    });

                // Get the unique search data for the specific column and add to the select list
                table
                    .column(colIdx)
                    .cache('search') // Cache the search data
                    .sort() // Sort the data
                    .unique() // Get unique values
                    .each(function (d) {
                        select.append($('<option value="' + d + '">' + d + '</option>'));
                    });
            });
        });
    </script>

    <script>
        document.getElementById("syncButton").addEventListener("click", function () {
            fetch('update_leads.php') // Adjust the path if needed
                .then(response => response.json())
                .then(data => {
                    if (data.updated > 0 || data.inserted > 0) {
                        Swal.fire({
                            title: "Succès!",
                            text: `${data.inserted} nouveaux leads ajoutés, ${data.updated} leads synchronisés.`,
                            icon: "success"
                        }).then(() => {
                            location.reload(); // Reload page after deletion
                        });
                    } else {
                        Swal.fire({
                            title: "Aucun changement",
                            text: "Aucune nouvelle donnée n’a été extraite de la feuille.",
                            icon: "info"
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: "Error",
                        text: "Une erreur s'est produite lors de la synchronisation des données.",
                        icon: "error"
                    });
                });
        });
    </script>

    <script>
        function viewLead(id) {
            // Send AJAX request to fetch lead details
            fetch("getLeadDetails.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "id=" + id,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire("Erreur", data.error, "error");
                    } else {
                        // Display lead details in SweetAlert
                        Swal.fire({
                            title: "Détails du Lead",
                            html: `
                <div class="details" style="text-align: left; margin: 20px; line-height: 2;">
                    <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-md-5"><span class="mb-1 badge font-medium bg-light-secondary text-secondary">ID: ${data.tracking_id}</span></div>
                        <div class="col-md-5"><span class="mb-1 badge font-medium bg-light-warning text-warning">${data.created_at}</span></div>
                        

                    </div>
                    <strong>Nom:</strong> ${data.name} <br>
                    <strong>Téléphone:</strong> ${data.phone_number} <br>
                    <strong>Adresse:</strong> ${data.address}, ${data.city} <br>
                    <strong>Produit:</strong> ${data.product} <br>
                    <strong>Prix:</strong> ${data.price} Dhs <br>
                    <strong>Agent:</strong> <span class="mb-1 badge bg-light-info text-info">${data.agent} </span><br>
                    <strong>Status:</strong> <span class="${data.status_class}">${data.status}</span><br>

                    <strong>Commission:</strong> ${data.comission} Dhs <br>
                    <strong>Commentaires:</strong> ${data.comments} <br>
                </div>
                `,
                            icon: "info",
                            confirmButtonText: "Fermer"
                        });
                    }
                })
                .catch(error => {
                    console.error("Erreur:", error);
                    Swal.fire("Erreur", "Impossible de récupérer les détails", "error");
                });
        }

        function viewFollowUp(id) {
            fetch("getFollowUpDetails.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "id=" + id,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire("Erreur", data.error, "error");
                    } else {
                        // Construct timeline HTML
                        let timelineHtml = '<ul class="timeline-widget mb-0 position-relative mb-n5">';

                        data.forEach(followUp => {
                            timelineHtml += `
                    <li class="timeline-item d-flex position-relative overflow-hidden">
                        <div class="timeline-time text-dark flex-shrink-0 text-end fs-3">
                            ${followUp.created_at}
                        </div>
                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                            <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                        </div>
                        <div class="timeline-desc fs-3 text-dark mt-n1">
                            <p>${followUp.message ? followUp.message : 'Rien à afficher'}</p>
                        </div>
                    </li>
                `;
                        });

                        timelineHtml += "</ul>";

                        // Display lead details in SweetAlert
                        Swal.fire({
                            title: "Suivi",
                            html: `
        <div class="timeline-container d-flex flex-column" style="height: 35vh; overflow-y: auto;">
            ${timelineHtml}
        </div>
    `,
                            icon: "question",
                            confirmButtonText: "Fermer"
                        });
                    }
                })
                .catch(error => {
                    console.error("Erreur:", error);
                    Swal.fire("Erreur", "Impossible de récupérer les détails", "error");
                });
        }
    </script>


    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>

</body>

</html>