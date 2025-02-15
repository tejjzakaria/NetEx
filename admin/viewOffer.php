<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";  // Make sure to include your database connection file
include "checkSession.php";
include "fetchUserData.php";

// Get parcel data from database
$id = $_GET['id'];
$sql = "SELECT * FROM offers WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$offerData = mysqli_fetch_assoc($result);

$message = '';

// Check if the form is submitted
if (isset($_POST['submit'])) {

    // Get the product name and quantity from the form
    $product_name = $_POST['product_name'];
    $quantityRequested = $_POST['quantityRequested'];
    $product_category = $_POST['product_category'];
    $product_price = $_POST['product_price'];
    $product_comission = $_POST['product_comission'];

    // Get the userID from the session
    $userID = $_SESSION['userID'];

    // Check if the product exists and fetch current quantity from the offers table
    $sql_check = "SELECT offer_quantity FROM offers WHERE offer_name = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "s", $product_name);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $offer_quantity);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);

        // Check if there is enough quantity available
        if ($offer_quantity >= $quantityRequested) {

            // Update the offer_quantity in the offers table
            $new_quantity = $offer_quantity - $quantityRequested;
            $sql_update = "UPDATE offers SET offer_quantity = ? WHERE offer_name = ?";
            if ($stmt_update = mysqli_prepare($conn, $sql_update)) {
                mysqli_stmt_bind_param($stmt_update, "is", $new_quantity, $product_name);
                mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);

                // Insert the request into stock_requests table
                $sql_insert = "INSERT INTO stock_requests (product_name, quantity, product_category, product_price, product_comission, userID) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt_insert = mysqli_prepare($conn, $sql_insert)) {
                    mysqli_stmt_bind_param($stmt_insert, "sssssi", $product_name, $quantityRequested, $product_category, $product_price, $product_comission, $userID);
                    if (mysqli_stmt_execute($stmt_insert)) {
                        // Redirect to viewProducts.php on successful insertion
                        header("Location: viewProducts.php");
                        exit();
                    } else {
                        $message = "Error: Could not execute insert query: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt_insert);
                } else {
                    $message = "Error: Could not prepare insert query: " . mysqli_error($conn);
                }
            } else {
                $message = "Error: Could not prepare update query: " . mysqli_error($conn);
            }
        } else {
            $message = "Error: Not enough quantity available. Requested: $quantityRequested, Available: $offer_quantity.";
        }
    } else {
        $message = "Error: Could not fetch product information: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}

// Optionally, display $message in your HTML if there are errors

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Offres</title>
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

                <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="fw-semibold mb-8">Offres</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="viewOffer.php">View Offres</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Voir details</li>
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
                    <div class="col-lg-6">
                        <div id="sync1" class="owl-carousel owl-theme">
                            <div class="item rounded overflow-hidden">
                                <img src="<?php echo $offerData['f_image'] ?>" alt="" class="img-fluid">
                            </div>

                        </div>


                    </div>
                    <div class="col-lg-6">
                        <div class="shop-content">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span
                                    class="badge text-bg-secondary fs-2 fw-semibold mb-3"><?php echo $offerData['offer_category'] ?></span>
                            </div>
                            <h4 class="fw-semibold mb-3 fs-10"><?php echo $offerData['offer_name'] ?></h4>
                            <h4 class="fw-semibold fs-8 mb-3"><?php echo $offerData['offer_price'] ?> Dhs</h4>

                            <form method="POST">

                                <div class="d-flex align-items-center gap-7 pb-7 mb-7 border-bottom">
                                    <h6 class="mb-0 fs-4 fw-semibold">Quantité:</h6>
                                    <div class="input-group input-group-sm rounded">
                                        <button
                                            class="btn minus min-width-40 py-0 border-end border-secondary fs-5 border-end-0 text-secondary"
                                            type="button" id="add1"><i class="ti ti-minus"></i></button>
                                            <input type="hidden" name="product_name" value="<?php echo $offerData['offer_name']?>">
                                            <input type="hidden" name="product_category" value="<?php echo $offerData['offer_category']?>">
                                            <input type="hidden" name="product_price" value="<?php echo $offerData['offer_price']?>">
                                            <input type="hidden" name="product_comission" value="<?php echo $offerData['offer_comission']?>">
                                        <input type="text"
                                            class="min-width-40 flex-grow-0 border border-secondary text-secondary fs-4 fw-semibold form-control text-center qty"
                                            placeholder="" aria-label="Example text with button addon"
                                            name="quantityRequested" aria-describedby="add1" value="10"
                                            max="<?php echo $offerData['offer_quantity'] ?>">
                                        <button
                                            class="btn min-width-40 py-0 border border-secondary fs-5 border-start-0 text-secondary add"
                                            type="button" id="addo2"><i class="ti ti-plus"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <p>Nous avons <span
                                            class="badge text-bg-warning fs-2 fw-semibold mb-3"><?php echo $offerData['offer_quantity'] ?></span>
                                        unités de ce produit disponibles en stock</p>
                                </div>
                                <div class="d-sm-flex align-items-center gap-3 pt-8 mb-7">
                                    
                                    
                                    <a href="<?php echo $offerData['link'] ?>"
                                        class="btn d-block btn-danger px-7 py-8">Voir la page de l'offre</a>
                                </div>
                            </form>
                            <?php echo $message; ?>

                        </div>
                    </div>
                </div>






            </div>
        </div>
        <div class="dark-transparent sidebartoggler"></div>
        <div class="dark-transparent sidebartoggler"></div>
    </div>
    <!--  Shopping Cart -->

    <!--  Mobilenavbar -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="mobilenavbar"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <nav class="sidebar-nav scroll-sidebar">
            <div class="offcanvas-header justify-content-between">
                <img src="dist/images/logos/favicon.ico" alt="" class="img-fluid">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div cs="offcanvas-body profile-dropdown mobile-navbar" data-simplebar="" data-simplebar>
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
</body>

</html>