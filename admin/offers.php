<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";



// Prepare SQL query to get agents, ordered by id (latest first)
$sql = "SELECT * FROM offers ORDER BY id DESC";
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
                          <img src="' . $row['f_image'] . '" alt="avatar" class="rounded" width="35" height="35"/>
                          <div class="ms-3">
                            <div class="user-meta-info">
                              <h6 class="user-name mb-0">' . $row['offer_name'] . '</h6>
                              <span class="user-work fs-3">' . $row['offer_category'] . '</span>
                            </div>
                          </div>
            </div>
            </td>
            <td>
                <p class="mb-0 fw-normal">' . $row['offer_price'] . ' MAD</p>
            </td>
            <td>
                <span class="badge bg-info fw-semibold fs-2">' . $row['offer_comission'] . ' MAD</span>
            </td>
            <td>
                <span class="badge bg-dark rounded-pill fw-semibold fs-2">' . $row['offer_quantity'] . '</span>
            </td>
            <td>
                <span class="' . $status_class . '">' . $row['status'] . '</span>
            </td>
            <td>
                <div class="button-group">
                    <a class="btn mb-1 btn-primary btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="#" onclick="viewOffer(' . $row['id'] . ')">
                        <i class="fs-5 ti ti-eye"></i>
                    </a>
                    <a class="btn mb-1 btn-secondary btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="editOffer.php?id=' . $row['id'] . '">
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
    <title>Admin - Offres</title>
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

                <div class="d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center">
                        <a href="" onclick="window.location.reload(true);"><button class="btn btn-secondary mb-3"
                                style="margin-right: 10px;"><i class="ti ti-refresh"
                                    style="margin-right: 6px;"></i>Actualiser les
                                données</button></a>


                    </div>

                    <a href="addOffer.php"><button class="btn btn-outline-dark mb-3"><i class="ti ti-plus"
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
                                                class="fs-5 ti ti-trash" style="margin-right: 6px;"></i>Supprimer la
                                            sélection</button>
                                        <button id="activate-selected" class="btn btn-success mb-4"
                                            style="display: none; transition: all 0.5s ease-in-out; margin-right: 6px;"><i
                                                class="fs-5 ti ti-check" style="margin-right: 6px;"></i>Activer la
                                            sélection</button>
                                        <button id="deactivate-selected" class="btn btn-warning mb-4"
                                            style="display: none; transition: all 0.5s ease-in-out; margin-right: 6px;"><i
                                                class="fs-5 ti ti-x" style="margin-right: 6px;"></i>Désactiver la
                                            sélection</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table border text-nowrap customize-table mb-0 align-middle"
                                            id="offers_table" style="width: 100%;">
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
                                                        <h6 class="fs-4 fw-semibold mb-0">Nom d'offre</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Prix de vente</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Commission</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Quantité</h6>
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

    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>

    <script src="dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/datatable/datatable-basic.init.js"></script>

    <script>
        $(document).ready(function () {
            $('#offers_table').DataTable({
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
                confirmButtonText: "Oui, supprime-le !"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit it to deleteLead.php
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "deleteOffer.php"; // Use POST method

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


        function viewOffer(id) {
            // Send AJAX request to fetch lead details
            fetch("getOfferDetails.php", {
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
                            title: "Détails d'offre",
                            html: `
                            <style>
    .image-container {
        position: relative;
        width: 100%; /* Or a specific width like 300px */
        padding-bottom: 100%; /* This creates the 1:1 aspect ratio */
        overflow: hidden;
    }

    .image-container img {
        position: absolute; /* Important for positioning within the container */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover; /* or contain, depending on your preference */
    }
</style>



                <div class="details" style="text-align: left; margin: 20px; line-height: 2;">
                    <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-md-5"><span class="mb-1 badge font-medium bg-light-secondary text-secondary">ID: ${data.id}</span></div>
                        <div class="col-md-5"><span class="mb-1 badge font-medium bg-light-warning text-warning">${data.created_at}</span></div>
                        

                    </div>
                    <strong>Nom:</strong> ${data.offer_name} <br>
                    <strong>Catégorie:</strong> ${data.offer_category} <br>
                    <strong>Prix de vente:</strong> ${data.offer_price} MAD <br>
                    <strong>Prix d'achat':</strong> ${data.offer_price_buy} MAD<br>
                    <strong>Commission':</strong> ${data.offer_comission} MAD<br>
                    <strong>Charges divers:</strong> ${data.offer_fees}  MAD<br>
                    <strong>Description:</strong> ${data.offer_description}  <br>
                    <strong>Lien:</strong> ${data.link}  <br>
                    <strong>Statut:</strong> <span class="${data.status_class}">${data.status}</span><br>
                    <div class="position-relative image-container mt-3">
                    
                    <strong>Images(s):</strong> <img src="${data.f_image}" class="card-img-top rounded"></div>  <br>
                    
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
    </script>

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
                        fetch("deleteOffers.php", {
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
                                        text: "Les offres sélectionnés ont été supprimés.",
                                        timer: 2000
                                    }).then(() => {
                                        location.reload(); // Reload page after deletion
                                    });

                                    updateButtonVisibility(); // Hide button if no rows left
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Impossible de supprimer certains ou tous les offres."
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
                        fetch("toggleStatusOffersBulk.php", {
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
                                        text: "Les offres sélectionnés ont été mis à jour.",
                                        timer: 2000
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erreur",
                                        text: "Impossible de mettre à jour certains ou tous les offres."
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
                        text: `Vous êtes sur le point de ${newStatus.toLowerCase()} cette offre.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, continuez !',
                        cancelButtonText: 'Non, annule!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the PHP script that handles the status change
                            window.location.href = `toggleStatusOffers.php?id=${statusId}&status=${newStatus}`;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>