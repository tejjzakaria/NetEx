<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

// Number of records per page
$records_per_page = 6;

// Get the current page from URL parameters, default is 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Get the selected category from the GET request
$selected_category = isset($_GET['filterByCategory']) ? $_GET['filterByCategory'] : '';

// Calculate the offset for the SQL query
$offset = ($page - 1) * $records_per_page;

// Fetch the total number of offers (with filtering)
$total_query = "SELECT COUNT(*) as total FROM offers";
if (!empty($selected_category)) {
    $total_query .= " WHERE offer_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_offers = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_offers / $records_per_page);

// Fetch offers for the current page (with filtering)
$sql = "SELECT * FROM offers WHERE (status = 'Active' OR status = 'active')";
if (!empty($selected_category)) {
    $sql .= " WHERE offer_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}
$sql .= " ORDER BY id DESC LIMIT $records_per_page OFFSET $offset";

$result = mysqli_query($conn, $sql);

$table_data = '';
while ($row = mysqli_fetch_assoc($result)) {
    $table_data .= '
    <style>
        .image-container {
            position: relative;
            width: 100%; /* Or a specific width like 300px */
            padding-bottom: 100%; /* Creates the 1:1 aspect ratio */
            overflow: hidden;
        }

        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* or contain */
        }
    </style>
        <div class="col-md-6 col-lg-4">
            <div class="card rounded-2 overflow-hidden hover-img">
                <div class="position-relative">
                    <div class="position-relative image-container">  <a href="viewOffer.php?id=' . $row['id'] . '">
                        <img src="'. $row['f_image'] .'" class="card-img-top rounded-0" alt="...">
                    </a>
                    </div>
                    <span class="badge bg-white text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">' . $row['offer_price'] . ' Dhs</span>
                </div>
                <div class="card-body p-4">
                    <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm mt-3">' . $row['offer_category'] . '</span>
                    <a class="d-block my-4 fs-5 text-dark fw-semibold" href="#">' . $row['offer_name'] . '</a>
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center gap-2"><i class="ti ti-packages text-dark fs-5"></i>' . $row['offer_quantity'] . '</div>
                        <div class="d-flex align-items-center gap-2"><i class="ti ti-moneybag text-dark fs-5"></i>' . $row['offer_comission'] . '</div>
                    </div>
                </div>
            </div>
        </div>
    ';
}

// Fetch unique categories for the filter dropdown
$sql_categories = "SELECT DISTINCT offer_category FROM offers";
$result_categories = mysqli_query($conn, $sql_categories);
$categories = [];
while ($row = mysqli_fetch_assoc($result_categories)) {
    $categories[] = $row['offer_category'];
}

mysqli_close($conn);

// Pagination HTML
$pagination = '<nav aria-label="..."><ul class="pagination justify-content-center mb-0 mt-4">';

// Previous button
if ($page > 1) {
    $prev_page = $page - 1;
    $pagination .= '<li class="page-item"><a class="page-link border-0 rounded-circle text-dark round-32 d-flex align-items-center justify-content-center" href="?page=' . $prev_page . '&filterByCategory=' . urlencode($selected_category) . '"><i class="ti ti-chevron-left"></i></a></li>';
}

// Page links
for ($i = 1; $i <= $total_pages; $i++) {
    $active_class = $i == $page ? 'active' : '';
    $pagination .= '<li class="page-item ' . $active_class . '"><a class="page-link border-0 rounded-circle round-32 mx-1 d-flex align-items-center justify-content-center" href="?page=' . $i . '&filterByCategory=' . urlencode($selected_category) . '">' . $i . '</a></li>';
}

// Next button
if ($page < $total_pages) {
    $next_page = $page + 1;
    $pagination .= '<li class="page-item"><a class="page-link border-0 rounded-circle text-dark round-32 d-flex align-items-center justify-content-center" href="?page=' . $next_page . '&filterByCategory=' . urlencode($selected_category) . '"><i class="ti ti-chevron-right"></i></a></li>';
}

$pagination .= '</ul></nav>';
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
                                            <a class="text-muted " href="offers.php">List Offres</a>
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

                <div class="col-md-12">
                    <div class="card">
                        <div class="border-bottom title-part-padding">
                            <h4 class="card-title mb-0">Filtrer les données par :</h4>
                        </div>
                        <div class="card-body">
                            <form class="row" method="GET" action="offers.php">
                                <div class="col-sm-3">
                                    <div class="mb-3">
                                        <select class="form-select" id="filterByCategory" name="filterByCategory">
                                            <option value="">Categorie</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($selected_category == $category) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <button class="btn btn-success font-weight-medium waves-effect waves-light"
                                            type="submit">
                                            <i class="ti ti-adjustments"></i>
                                            Filtrer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">


                    <?php echo $table_data ?>




                </div>

                <?php echo $pagination; ?>





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