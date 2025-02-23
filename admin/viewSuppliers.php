<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";



// Prepare SQL query to get agents, ordered by id (latest first)
$sql = "SELECT * FROM suppliers ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$table_data = '';

while ($row = mysqli_fetch_assoc($result)) {
    
   

    // Determine the status class
    if ($row['status'] == 'ACTIVE') {
        $status_class = "badge bg-success fw-semibold fs-2";
    } else if ($row['status'] == 'INACTIVE') {
        $status_class = "badge bg-danger fw-semibold fs-2";
    } else {
        $status_class = "badge bg-primary fw-semibold fs-2";
    }

    // Create the table row with the agent details
    $table_data .= '
        <tr>
            <td><input type="checkbox" class="row-select form-check-input contact-chkbox primary" value="' . $row['id'] . '"></td>
            <td>
                <p class="mb-0 fw-normal">' . $row['id'] . '</p>
            </td>
            <td>
                <div class="d-flex align-items-center">
                          <img src="' . $row['logo'] . '" alt="avatar" class="rounded" width="35" height="35"/>
                          <div class="ms-3">
                            <div class="user-meta-info">
                              <h6 class="user-name mb-0">' . $row['name'] . '</h6>
                            </div>
                          </div>
            </div>
            </td>
            <td>
                <p class="mb-0 fw-normal">' . $row['contact_person'] . '</p>
            </td>
            <td>
                <div class="ms-3">
                            <div class="user-meta-info">
                              <h6 class="user-name mb-0">' . $row['phone'] . '</h6>
                              <span class="user-work fs-3">' . $row['email'] . '</span>
                            </div>
                          </div>
            </td>
            <td>
                <div class="ms-3">
                            <div class="user-meta-info">
                              <h6 class="user-name mb-0">' . $row['address'] . '</h6>
                              <span class="user-work fs-3">' . $row['city'] . ', ' . $row['country'] . '</span>
                            </div>
                          </div>
            </td>
            <td>
                <span class="' . $status_class . '">' . $row['status'] . '</span>
            </td>
            <td>
                <div class="button-group">
                    <a class="btn mb-1 btn-secondary btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="editSupplier.php?id=' . $row['id'] . '">
                        <i class="fs-5 ti ti-pencil"></i>
                    </a>
                    <a class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="#" onclick="confirmDelete(' . $row['id'] . ')">
                        <i class="fs-5 ti ti-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
    ';
}

mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Fournisseurs</title>
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
                                <h4 class="fw-semibold mb-8">Fournisseurs</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="viewSuppliers.php">List Fournisseurs</a>
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

                <div class="d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center">
                        <a href="" onclick="window.location.reload(true);"><button class="btn btn-secondary mb-3"
                                style="margin-right: 10px;"><i class="ti ti-refresh"
                                    style="margin-right: 6px;"></i>Actualiser les
                                données</button></a>


                    </div>

                    <a href="addSupplier.php"><button class="btn btn-outline-dark mb-3"><i class="ti ti-plus"
                                style="margin-right: 6px;"></i>Nouveau</button></a>

                </div>

                

                <div class="datatables">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                <div class="d-flex align-items-center">
                                        <button id="process-selected" class="btn btn-danger mb-4"
                                            style="display: none; transition: all 0.5s ease-in-out; margin-right: 6px;"><i
                                                class="fs-5 ti ti-trash" style="margin-right: 6px;"></i>Supprimer la sélection</button>
                                        <button id="activate-selected" class="btn btn-success mb-4"
                                            style="display: none; transition: all 0.5s ease-in-out; margin-right: 6px;"><i
                                                class="fs-5 ti ti-check" style="margin-right: 6px;"></i>Activer la sélection</button>
                                        <button id="deactivate-selected" class="btn btn-warning mb-4"
                                            style="display: none; transition: all 0.5s ease-in-out; margin-right: 6px;"><i
                                                class="fs-5 ti ti-x" style="margin-right: 6px;"></i>Désactiver la sélection</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table border text-nowrap customize-table mb-0 align-middle"
                                            id="suppliers_table">
                                            <thead class="text-dark fs-4">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select-all"
                                                            class="form-check-input contact-chkbox primary">
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">ID</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Nom</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Personne de contact</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">N° de téléphone</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Adresse</h6>
                                                    </th>
                                                    
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Statut</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Actions</h6>
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
    <script src="dist/js/datatable/datatable-basic.init.js"></script>

    <script>
        $(document).ready(function () {
            $('#suppliers_table').DataTable({
                "scrollX": true,
                "scrollY": true,
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
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Es-tu sûr?",
                text: "Vous ne pourrez pas revenir en arrière !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, supprime-le !",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit it to deleteLead.php
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "deleteSupplier.php"; // Use POST method

                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "id";
                    input.value = id; // Set the lead ID

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit(); // Submit the form
                }
            });
        }
    </script>


    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let deleteButton = document.getElementById("process-selected");
            let activateButton = document.getElementById("activate-selected");
            let deactivateButton = document.getElementById("deactivate-selected");

            deleteButton.style.display = "none";
            activateButton.style.display = "none";
            deactivateButton.style.display = "none";

            function updateButtonVisibility() {
                let anyChecked = document.querySelectorAll(".row-select:checked").length > 0;
                deleteButton.style.display = anyChecked ? "block" : "none";
                activateButton.style.display = anyChecked ? "block" : "none";
                deactivateButton.style.display = anyChecked ? "block" : "none";
            }

            document.querySelectorAll(".row-select").forEach((checkbox) => {
                checkbox.addEventListener("change", updateButtonVisibility);
            });

            document.getElementById("select-all").addEventListener("change", function () {
                let isChecked = this.checked;
                document.querySelectorAll(".row-select").forEach((checkbox) => {
                    checkbox.checked = isChecked;
                });
                updateButtonVisibility();
            });

            // Delete button logic
            deleteButton.addEventListener("click", function () {
                let selectedIds = [];

                document.querySelectorAll(".row-select:checked").forEach((checkbox) => {
                    selectedIds.push(checkbox.value);
                });

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: "Es-tu sûr?",
                    text: "Vous ne pourrez pas revenir en arrière\u00A0!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Oui, supprimez-les !"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("deleteSuppliers.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ ids: selectedIds })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    selectedIds.forEach(id => {
                                        document.querySelector(`.row-select[value="${id}"]`).closest("tr").remove();
                                    });

                                    Swal.fire({
                                        icon: "success",
                                        title: "Supprimé!",
                                        text: "Les fournisseurs sélectionnés ont été supprimés.",
                                        timer: 2000
                                    }).then(() => {
                                        location.reload(); // Reload page after deletion
                                    });

                                    updateButtonVisibility(); // Hide button if no rows left
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Impossible de supprimer certains ou tous les fournisseurs."
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Something went wrong!"
                                });
                                console.error("Error:", error);
                            });
                    }
                });
            });

            // Activate button logic
            activateButton.addEventListener("click", function () {
                bulkToggleStatus("ACTIVE");
            });

            // Deactivate button logic
            deactivateButton.addEventListener("click", function () {
                bulkToggleStatus("INACTIVE");
            });

            function bulkToggleStatus(newStatus) {
                let selectedIds = [];
                document.querySelectorAll(".row-select:checked").forEach((checkbox) => {
                    selectedIds.push(checkbox.value);
                });

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: "Es-tu sûr?",
                    text: `Vous êtes sur le point de ${newStatus.toLowerCase()} les offres sélectionnés.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui, continuez !",
                    cancelButtonText: "Non, annulez !",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("toggleStatusSuppliersBulk.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ ids: selectedIds, status: newStatus })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Mis à jour !",
                                        text: "Les fournisseurs sélectionnés ont été mis à jour.",
                                        timer: 2000
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erreur",
                                        text: "Impossible de mettre à jour certains ou tous les fournisseurs."
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: "error",
                                    title: "Erreur",
                                    text: "Une erreur s'est produite !"
                                });
                                console.error("Erreur\u00A0:", error);
                            });
                    }
                });
            }

            // Handle the toggle status button click event
            document.querySelectorAll('.toggle-status').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const statusId = this.getAttribute('data-id');
                    const newStatus = this.getAttribute('data-status');

                    // SweetAlert2 confirmation prompt
                    Swal.fire({
                        title: 'Es-tu sûr?',
                        text: `Vous êtes sur le point de ${newStatus.toLowerCase()} ce fournisseur.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, continuez !',
                        cancelButtonText: 'Non, annule!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the PHP script that handles the status change
                            window.location.href = `toggleStatusSuppliers.php?id=${statusId}&status=${newStatus}`;
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>