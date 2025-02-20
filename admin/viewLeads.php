<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Connect to the database
include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

$sql = "SELECT leads.*, user_info.full_name 
        FROM leads 
        JOIN user_info ON leads.userID = user_info.id ORDER BY id";
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

    $table_data .= '
        <tr>
            <td><input type="checkbox" class="row-select form-check-input contact-chkbox primary" value="' . $row['id'] . '"></td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-0">
                        <h6 class="fs-4 fw-normal mb-0" style="max-width: 400px; word-wrap: break-word; white-space: normal;">' . $row['tracking_id'] . '</h6>
                    </div>
                </div>
            </td>
            <td>
                <span class="mb-1 badge font-medium bg-light-info text-info fs-2">' . $row['full_name'] . '</span>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-0">
                        <h6 class="fs-4 fw-normal mb-0" style="max-width: 300px; word-wrap: break-word; white-space: normal;">' . $row['name'] . '</h6>
                        <span class="fw-normal">' . $row['phone_number'] . '</span>
                    </div> 
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-0">
                        <h6 class="fs-4 fw-normal mb-0" style="max-width: 300px; word-wrap: break-word; white-space: normal;">' . $row['address'] . ',</h6>
                        <span class="fw-normal" style="max-width: 400px; word-wrap: break-word; white-space: normal;">' . $row['city'] . '</span>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge bg-warning fw-semibold fs-2" style="line-height: 1.5; max-width: 300px; word-wrap: break-word; white-space: normal;">' . $row['agent'] . '</span>
            </td>
            <td>
                <span class="' . $status_class . '" style="line-height: 1.5; max-width: 300px; word-wrap: break-word; white-space: normal;">' . $row['status'] . '</span>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-0">
                        <h6 class="fs-4 fw-normal mb-0" style="max-width: 400px; word-wrap: break-word; white-space: normal;">' . $row['product'] . '</h6>
                        <span class="fw-normal">' . $row['price'] . ' Dhs</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="button-group">
                    <a class="btn mb-1 btn-primary btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="#" onclick="viewLead(' . $row['id'] . ')">
                        <i class="fs-4 ti ti-eye"></i>
                    </a>
                    <a class="btn mb-1 btn-warning btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="#" onclick="viewFollowUp(' . $row['id'] . ')">
                        <i class="fs-4 ti ti-info-square-rounded"></i>
                    </a>
                    <a class="btn mb-1 btn-secondary btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="editLead.php?id=' . $row['id'] . '">
                        <i class="fs-4 ti ti-pencil"></i>
                    </a>
                    <a class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center" href="#" onclick="confirmDelete(' . $row['id'] . ')">
                        <i class="fs-4 ti ti-trash"></i>
                    </a>
                </div>
            </td>
        </tr>';
}




mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Admin - Leads</title>
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
                <div class="d-flex align-items-center">
                    <button class="btn btn-secondary mb-3" id="syncButton"><i class="fs-6 ti ti-refresh"
                            style="margin-right: 6px;"></i>Synchroniser les données</button>
                </div>
                <div class="datatables">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="card-body">
                                    <div class="d-flex">
                                        <button id="process-selected" class="btn btn-danger mb-4"
                                            style="display: none; transition: all 0.5s ease-in-out;"><i
                                                class="fs-5 ti ti-trash" style="margin-right: 6px;"></i>Supprimer les
                                            lignes
                                            sélectionnées</button>

                                    </div>
                                    <div class="table-responsive">

                                        <table class="table border text-nowrap customize-table mb-0 align-middle"
                                            id="leads_table">

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
                                                        <h6 class="fs-4 fw-semibold mb-0">Vendeur</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Nom</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Adresse</h6>
                                                    </th>

                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Agent</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Statut</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Produit</h6>
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

    <script src="dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/datatable/datatable-basic.init.js"></script>

    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>
    <script>
        $(document).ready(function () {
            $('#leads_table').DataTable({
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
                    form.action = "deleteLead.php"; // Use POST method

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
                        fetch("deleteLeads.php", {
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
                                        text: "Les leads sélectionnés ont été supprimés.",
                                        timer: 2000
                                    }).then(() => {
                                        location.reload(); // Reload page after deletion
                                    });

                                    updateDeleteButtonVisibility(); // Hide button if no rows left
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Impossible de supprimer certains ou tous les leads."
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


<script>
        document.getElementById('syncButton').addEventListener('click', function () {
            // Show loading state
            Swal.fire({
                title: 'Synchronisation...',
                text: 'Veuillez patienter pendant la synchronisation des données.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            // Make an AJAX request to your PHP script
            fetch('update_leads.php', {
                method: 'POST', // Use POST if you're sending data, GET otherwise
            })
                .then(response => response.json())
                .then(data => {
                    // Hide loading spinner
                    Swal.close();

                    if (data.error) {
                        // If there's an error
                        Swal.fire({
                            title: 'Erreur!',
                            text: data.error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // If success, show success message
                        Swal.fire({
                            title: 'Succès!',
                            text: `${data.updated} leads mis à jour et ${data.inserted} leads insérés.`,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload page after deletion
                        });
                    }
                })
                .catch(error => {
                    // In case of an error with the fetch
                    Swal.close();
                    Swal.fire({
                        title: 'Erreur!',
                        text: 'Quelque chose a mal tourné.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        });
    </script>
</body>

</html>