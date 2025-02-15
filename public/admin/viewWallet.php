<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Connect to the database
include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

// Initialize variables
$table_data = '';

// Fetch all withdrawal requests for admin view
$sql = "SELECT withdrawal_requests.*, user_info.full_name FROM withdrawal_requests 
        LEFT JOIN user_info ON withdrawal_requests.userID = user_info.id";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {

    if ($row['status'] == 'Succès') {
        $status_class = "badge bg-success fw-semibold fs-2";
    } else if ($row['status'] == 'En attente') {
        $status_class = "badge bg-primary fw-semibold fs-2";
    } else if ($row['status'] == 'échoué') {
        $status_class = "badge bg-danger fw-semibold fs-2";
    } else {
        $status_class = "badge bg-primary fw-semibold fs-2";
    }

    $table_data = '
            <tr>
                <td>
                    <p class="mb-0 fw-normal">' . $row['tracking_id'] . '</p>
                </td>
                <td>
                    <p class="mb-0 fw-normal">' . $row['created_at'] . '</p>
                </td>
                <td>
                    <span
                        class="mb-1 badge font-medium bg-light-info text-info fs-2">' . $row['full_name'] . '</span>
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
                    <span class="' . $status_class . '">' . $row['status'] . '</span>
                </td>
                <td class="bg-transparent">
                <div class="button-group">
                    <a class="btn mb-1 btn-secondary btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="editTransaction.php?id=' . $row['id'] . '">
                        <i class="fs-5 ti ti-pencil"></i>
                    </a>
                    <a class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="#" onclick="confirmDelete(' . $row['id'] . ')">
                        <i class="fs-5 ti ti-trash"></i>
                    </a>
                </div>
            </td>
            </tr>
    ' . $table_data;
}

// Close the connection
mysqli_close($conn);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Admin - Portfeuille</title>
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
                                <h4 class="fw-semibold mb-8">Portfeuille</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="viewWallet.php">List Transactions</a>
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

                <!-- 
                
-->


                <div class="datatables">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title fw-semibold">Histoire des transactions (Clients)</h5>
                                    <p class="card-subtitle mb-0">Aperçu de vos transactions</p>
                                    <div class="table-responsive mt-4">
                                        <table class="table table-borderless text-nowrap align-middle mb-0"
                                            id="transactions_table">
                                            <thead class="text-dark fs-4">
                                                <tr>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Réf</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Date & heure</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Client</h6>
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
    <!-- jQuery (required for DataTables) -->
    <script src="dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/datatable/datatable-basic.init.js"></script>

    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>


    <script>
        $(document).ready(function () {
            $('#transactions_table').DataTable({
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
                confirmButtonText: "Oui, supprime-le !"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit it to deleteLead.php
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "deleteTransaction.php"; // Use POST method

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

</body>

</html>