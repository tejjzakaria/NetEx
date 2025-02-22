<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

$message = "";
$alertScript = '';

if (isset($_POST['submit'])) {
    if (isset($_POST['status']) && is_array($_POST['status']) && !empty($_POST['status'])) {
        $statuses = $_POST['status'];
        $colors = isset($_POST['color']) && is_array($_POST['color']) ? $_POST['color'] : [];

        $all_inserted = true;

        for ($i = 0; $i < count($statuses); $i++) {
            $status = htmlspecialchars($statuses[$i], ENT_QUOTES, 'UTF-8');
            $div_class = isset($colors[$i]) ? htmlspecialchars($colors[$i], ENT_QUOTES, 'UTF-8') : null;
            $visibility = "ACTIVE";

            if (empty($div_class)) {
                $alertScript = "
                    <script>
                        Swal.fire({
                          title: 'Erreur',
                          text: 'Veuillez sélectionner une couleur pour tous les statuts.',
                          icon: 'error',
                          confirmButtonText: 'OK'
                        });
                    </script>
                ";
                $all_inserted = false;
                break;
            }

            $sql = "INSERT INTO statuses (statut, div_class, visibility) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("sss", $status, $div_class, $visibility);

            if (!$stmt->execute()) {
                $alertScript = "
                    <script>
                        Swal.fire({
                          title: 'Erreur',
                          text: 'Erreur lors de l\'ajout du statut: " . htmlspecialchars($stmt->error) . "',
                          icon: 'error',
                          confirmButtonText: 'OK'
                        });
                    </script>
                ";
                $all_inserted = false;
                break;
            }

            $stmt->close();
        }

        if ($all_inserted) {
            $alertScript = "
                <script>
                    Swal.fire({
                      title: 'Statuts ajoutés',
                      text: 'Redirection vers la liste des statuts...',
                      icon: 'success',
                      timer: 3000,
                      showConfirmButton: false
                    }).then(() => {
                      window.location.href = 'viewStatuses.php';
                    });
                </script>
            ";
        }
    } else {
        // Condition pour gérer le cas où aucun statut n'est ajouté
        if (isset($_POST['color']) && is_array($_POST['color']) && !empty($_POST['color'])) {
            $alertScript = "
                <script>
                    Swal.fire({
                      title: 'Erreur',
                      text: 'Veuillez ajouter au moins un statut.',
                      icon: 'error',
                      confirmButtonText: 'OK'
                    });
                </script>
            ";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Admin - Add Statut</title>
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
                                <h4 class="fw-semibold mb-8">Statuts</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="">Nouveau Statut</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Ajouter nouveau</li>
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


                <form method="POST">
                    <div class="card">
                        <?php echo $message ?>

                        <div class="card-body p-4 border-bottom">
                            <div id="status-rows">
                                <div class="row status-row">
                                    <div class="col-lg-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-danger ms-2 delete-row">X</button>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center">
                                        <div class="mb-4">
                                            <label for="status">Nom statut*</label>
                                            <input type="text" class="form-control mt-2" name="status[]"
                                                placeholder="Entrer un nom de statut" required />
                                        </div>
                                    </div>
                                    <div class="col-lg-7 d-flex align-items-center">
                                        <div class="mb-4">
                                            <label for="color">Couleur</label>
                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-1" value="btn btn-outline-danger font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-danger font-medium mt-2"
                                                        for="btn-check-1">Rouge</label>
                                                </div>
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-2"
                                                        value="btn btn-outline-secondary font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-secondary font-medium mt-2"
                                                        for="btn-check-2">Bleu</label>
                                                </div>
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-3"
                                                        value="btn btn-outline-warning font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-warning font-medium mt-2"
                                                        for="btn-check-3">Jaune</label>
                                                </div>
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-4"
                                                        value="btn btn-outline-success font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-success font-medium mt-2"
                                                        for="btn-check-4">Vert</label>
                                                </div>
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-5" value="btn btn-outline-dark font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-dark font-medium mt-2"
                                                        for="btn-check-5">Noir</label>
                                                </div>
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-6"
                                                        value="btn btn-outline-primary font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-primary font-medium mt-2"
                                                        for="btn-check-6">Mauve</label>
                                                </div>
                                                <div>
                                                    <input type="radio" class="btn-check mt-2" name="color[0]"
                                                        id="btn-check-7" value="btn btn-outline-info font-medium mt-2"
                                                        autocomplete="off" />
                                                    <label class="btn btn-outline-info font-medium mt-2"
                                                        for="btn-check-7">Bleu foncé</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="submit" name="submit" class="btn btn-primary">Ajouter
                                            Statuts</button>
                                        <button type="button" class="btn btn-secondary" id="add-status-row">Ajouter une
                                            autre ligne</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>







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
    <script src="dist/libs/jquery-steps/build/jquery.steps.min.js"></script>
    <script src="dist/libs/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="dist/js/forms/form-wizard.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>
    <?php echo $alertScript; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addStatusRowButton = document.getElementById('add-status-row');
            const statusRowsContainer = document.getElementById('status-rows');

            let rowCounter = 1;
            let colorCounter = 8; // Start at 8 since the first row uses 1-7

            // Add event listener for the delete button on the initial row
            document.querySelector('.status-row .delete-row').addEventListener('click', function () {
                statusRowsContainer.removeChild(document.querySelector('.status-row'));
            });

            addStatusRowButton.addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'status-row');
                let colorInputs = `
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-danger font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-danger font-medium mt-2" for="btn-check-${colorCounter++}">Rouge</label>
                </div>
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-secondary font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-secondary font-medium mt-2" for="btn-check-${colorCounter++}">Bleu</label>
                </div>
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-warning font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-warning font-medium mt-2" for="btn-check-${colorCounter++}">Jaune</label>
                </div>
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-success font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-success font-medium mt-2" for="btn-check-${colorCounter++}">Vert</label>
                </div>
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-dark font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-dark font-medium mt-2" for="btn-check-${colorCounter++}">Noir</label>
                </div>
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-primary font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-primary font-medium mt-2" for="btn-check-${colorCounter++}">Mauve</label>
                </div>
                <div>
                    <input type="radio" class="btn-check mt-2" name="color[${rowCounter}]" id="btn-check-${colorCounter}"
                        value="btn btn-outline-info font-medium mt-2" autocomplete="off" />
                    <label class="btn btn-outline-info font-medium mt-2" for="btn-check-${colorCounter++}">Bleu foncé</label>
                </div>
            `;
                newRow.innerHTML = `
                <div class="col-lg-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger ms-2 delete-row">X</button>
                </div>
                <div class="col-lg-3 d-flex align-items-center">
                
                    <div class="mb-4">
                        <label for="status">Nom statut*</label>
                        <input type="text" class="form-control mt-2" name="status[]"
                            placeholder="Entrer un nom de statut" required />
                    </div>
                </div>
                <div class="col-lg-7 d-flex align-items-center">
                
                    <div class="mb-4">
                        <label for="color">Couleur</label>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            ${colorInputs}
                        </div>
                    </div>
                    
                </div>
            `;
                statusRowsContainer.appendChild(newRow);
                rowCounter++;

                // Add event listener for the delete button
                newRow.querySelector('.delete-row').addEventListener('click', function () {
                    statusRowsContainer.removeChild(newRow);
                });
            });
        });
    </script>

</body>

</html>