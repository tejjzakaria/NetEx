<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Connect to the database
include "../config.php";
include "checkSession.php";
include "fetchUserData.php";


$userID = $_SESSION['userID'];

$alertScript = ''; // This will store the SweetAlert script

// Initialize variables
$total_comission = 0;
$total_withdrawals = 0;
$wallet_balance = 0;

// SQL query to calculate total commission where status is 'livré' and state is 'non payé'
$sql = "SELECT SUM(comission) AS total_comission FROM parcels2 WHERE status = 'livré' OR status = 'Livré' OR status = 'livre' AND userID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($total_comission);
    $stmt->fetch();
    $stmt->close(); 
}

// Set total_comission to 0 if no value found
$total_comission = $total_comission ? $total_comission : 0;

// SQL query to get the total withdrawals for the user
$sql_withdrawals = "SELECT SUM(amount) AS total_withdrawals FROM withdrawal_requests WHERE userID = ? AND status != 'echoué'";
if ($stmt = $conn->prepare($sql_withdrawals)) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($total_withdrawals);
    $stmt->fetch();
    $stmt->close();
}

// Set total_withdrawals to 0 if no value found
$total_withdrawals = $total_withdrawals ? $total_withdrawals : 0;

// Calculate remaining balance
$wallet_balance = $total_comission - $total_withdrawals;



$tracking_id = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
$message = '';

if (isset($_POST['submit'])) {
    // Get form data
    $userID = $_SESSION['userID'];
    $tracking_id = $_POST['tracking_id'];
    $amount = $_POST['amount'];
    $account = $_POST['account'];
    $comments = $_POST['comments'];
    $status = 'en attente';

    // Prepare the SQL query
    $sql = "INSERT INTO withdrawal_requests (userID, tracking_id, amount, account, comments, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Initialize the prepared statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind the parameters to the query
    $stmt->bind_param("ssssss", $userID, $tracking_id, $amount, $account, $comments, $status);

    // Execute the query
    if ($stmt->execute()) {
        // Success
        $alertScript = "
        <script>
            Swal.fire({
              title: 'Retrait demandé', 
              text: 'Redirection vers portfeuille...',
              icon: 'success',
              timer: 3000, // Time in milliseconds (3 seconds)
              showConfirmButton: false
            }).then(() => {
              window.location.href = 'viewWallet.php'; // Redirect after the SweetAlert
            });
        </script>
        ";
    } else {
        // Error
        $message = "<div class='alert alert-danger'>Something went wrong. Please try again!</div>";
    }

    // Close the statement
    $stmt->close();
}

$sql = "SELECT * FROM withdrawal_requests WHERE userID='$userID'";
$result = mysqli_query($conn, $sql);
$status = '';

$table_data = '';

while ($row = mysqli_fetch_assoc($result)) {

    if ($row['status'] == 'SUCCÈS') {
        $status_class = "badge bg-success fw-semibold fs-2";
    } else if ($row['status'] == 'EN ATTENTE') {
        $status_class = "badge bg-primary fw-semibold fs-2";
    } else if ($row['status'] == 'ÉCHOUÉ') {
        $status_class = "badge bg-danger fw-semibold fs-2";
    } else {
        $status_class = "badge bg-primary fw-semibold fs-2";
    }



    $table_data = '
            <tr>
                <td><input type="checkbox" class="row-select form-check-input contact-chkbox primary" value="' . $row['id'] . '"></td>
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
    
    ' . $table_data;

}


mysqli_close($conn);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Portfeuille</title>
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


                <div class="row d-flex">
                    <!-- Column 1 -->
                    <div class="col-sm-12 col-md-6 d-flex">
                        <div class="card bg-success flex-fill">
                            <div class="card-body text-white">
                                <div class="d-flex flex-row align-items-center">
                                    <div
                                        class="round-40 rounded-circle d-flex align-items-center justify-content-center bg-light-success text-success">
                                        <i class="ti ti-credit-card fs-6"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0 text-white fs-6">Solde de Portfeuille</h4>
                                        <span class="text-white-50">Montant total disponible dans le portefeuille</span>
                                    </div>
                                    <div class="ms-auto">
                                        <h2 class="fs-7 mb-0 text-white"><?php echo $wallet_balance ?> Dhs</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-sm-12 col-md-6 d-flex">
                        <div class="card bg-secondary text-white flex-fill">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center">
                                    <a href="JavaScript: void(0);"><i class="ti ti-currency-dollar display-6 text-white"
                                            title="ETH"></i></a>
                                    <div class="ms-3 mt-2">
                                        <h4 class="mb-0 text-white">Total retraits</h4>
                                        <h5 class="text-white"><?php echo $total_withdrawals ?> Dhs</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3 -->

                </div>

                <div class="row d-flex">
                    <div class="col-12">
                        <div class="card">

                            <div class="border-bottom title-part-padding">
                                <h4 class="card-title mb-0">Demander un retrait ici</h4>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning" role="alert" style="line-height: 2;">
                                    Les frais de chaque demande de retrait (virement bancaire) sont:
                                    <strong> 5.00MAD </strong>
                                </div>
                                <form class="mt-0" method="POST" id="withdrawForm">
                                    <input type="hidden" name="tracking_id" value="<?php echo $tracking_id ?>">
                                    <div class="">
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Montant"
                                                aria-label="Amount" name="amount" id="amount" required />
                                            <span class="input-group-text">DHS</span>
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Compte" name="account"
                                                required value="<?php echo $userData['bank_account']; ?>" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <textarea type="text" name="comments" class="form-control"
                                                id="exampleInputtext" placeholder="Ajouter info ici"
                                                required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <button
                                                class="btn px-4 btn-light-primary text-primary font-weight-medium waves-effect waves-light"
                                                type="submit" name="submit" id="submitBtn">
                                                Demander retrait
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>




                </div>
                <div class="datatables">
                    <div class="row">
                        <div class="col-12"></div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold">Histoire des transactions</h5>
                                <p class="card-subtitle mb-0">Aperçu de vos transactions</p>
                                <div class="table-responsive mt-4">
                                <button id="process-selected" class="btn btn-danger mb-4"
                                        style="display: none; transition: all 0.5s ease-in-out;"><i
                                            class="fs-5 ti ti-trash" style="margin-right: 6px;"></i>Supprimer les lignes
                                        sélectionnées</button>
                                    <table class="table table-borderless text-nowrap align-middle mb-0"
                                        id="transactions_table">
                                        <thead class="text-dark fs-4">
                                            <tr>
                                            <th>
                                                        <input type="checkbox" id="select-all"
                                                            class="form-check-input contact-chkbox primary">
                                                    </th>
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

    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>
    <?php echo $alertScript; ?>

    <script>
        document.getElementById("withdrawForm").addEventListener("submit", function (event) {
            var walletBalance = <?php echo $wallet_balance; ?>; // Get wallet balance from PHP
            var withdrawAmount = parseFloat(document.getElementById("amount").value); // Get entered amount

            if (walletBalance <= 0) {
                event.preventDefault(); // Stop form submission
                Swal.fire({
                    title: "Solde insuffisant",
                    text: "Vous n'avez pas assez de fonds pour effectuer un retrait.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            if (withdrawAmount > walletBalance) {
                event.preventDefault(); // Stop form submission
                Swal.fire({
                    title: "Montant trop élevé",
                    text: "Le montant demandé dépasse votre solde actuel de " + walletBalance + " DHS.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }
        });
    </script>


<script>
        document.addEventListener("DOMContentLoaded", function () {
            let deleteButton = document.getElementById("process-selected");
            deleteButton.style.display = "none"; // Hide button initially

            function updateDeleteButtonVisibility() {
                let anyChecked = document.querySelectorAll(".row-select:checked").length > 0;
                deleteButton.style.display = anyChecked ? "block" : "none";
            }

            document.querySelectorAll(".row-select").forEach((checkbox) => {
                checkbox.addEventListener("change", updateDeleteButtonVisibility);
            });

            document.getElementById("select-all").addEventListener("change", function () {
                let isChecked = this.checked;
                document.querySelectorAll(".row-select").forEach((checkbox) => {
                    checkbox.checked = isChecked;
                });
                updateDeleteButtonVisibility();
            });

            deleteButton.addEventListener("click", function () {
                let selectedIds = [];

                document.querySelectorAll(".row-select:checked").forEach((checkbox) => {
                    selectedIds.push(checkbox.value);
                });

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: "Es-tu sûr?",
                    text: "Vous ne pourrez pas revenir en arrière !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Oui, supprimez-les !"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("deleteTransactions.php", {
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
                                        text: "Les transactions sélectionnés ont été supprimés.",
                                        timer: 2000
                                    }).then(() => {
                                        location.reload(); // Reload page after deletion
                                    });

                                    updateDeleteButtonVisibility(); // Hide button if no rows left
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Impossible de supprimer certains ou tous les transactions."
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
        });

    </script>

</body>

</html>