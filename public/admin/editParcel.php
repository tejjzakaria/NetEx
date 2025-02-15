<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

$sql_ = "SELECT id, full_name FROM user_info";
$userDataP = mysqli_query($conn, $sql_);


// Get parcel data from database
$id = $_GET['id'];
$sql = "SELECT * FROM parcels2 WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$parcelData = mysqli_fetch_assoc($result);

$selectedUserID = $parcelData['userID'];  // Retrieve this from your parcel data

$message = "";
$alertScript = ''; // This will store the SweetAlert script
// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get form data
    $id = $_POST['id'];
    $userID = $_POST['userID'];
    $name = $_POST['name'];  // Assuming userID is stored in session
    $tracking_id = $_POST['tracking_id'];
    $phone_number = $_POST['phone_number'];
    $price = $_POST['price'];
    $city = $_POST['city'];
    $product = $_POST['product'];
    $address = $_POST['address'];
    $comments = $_POST['comments'];
    $status = $_POST['status'];
    $state = $_POST['state'];


    // Update parcel data in the database with error handling
    $sql = "UPDATE parcels2 SET userID='$userID', name='$name', tracking_id='$tracking_id',
phone_number='$phone_number', price='$price', city='$city', product='$product', 
address='$address', comments='$comments', status='$status', state='$state' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        // Redirect to parcels page if successful
        $alertScript = "
        <script>
            Swal.fire({
              title: 'Colis modifié', 
              text: 'Redirection vers la liste des colis...',
              icon: 'success',
              timer: 3000, // Time in milliseconds (3 seconds)
              showConfirmButton: false
            }).then(() => {
              window.location.href = 'viewParcels.php'; // Redirect after the SweetAlert
            });
        </script>
        ";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

}

$sql_ = "SELECT product_name FROM stock_requests";
$productData = mysqli_query($conn, $sql_);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Admin - Modifier Colis</title>
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
                                <h4 class="fw-semibold mb-8">Colis</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="editParcel.php">Modifier Colis</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Modifier existant</li>
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


                        <div class="card-body p-4 border-bottom">
                            <?php echo $message; ?>

                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="hidden" name="id" value="<?php echo $parcelData['id']; ?>">
                                    <div class="mb-4">
                                        <label for="" class="form-label fw-semibold">Destinaire</label>
                                        <input type="text" class="form-control mt-2" id="lastName1" name="name"
                                            placeholder="Entrer le nom du destinaire"
                                            value="<?php echo $parcelData['name'] ?>" required />
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1"
                                            class="form-label fw-semibold">Téléphone</label>
                                        <input type="text" class="form-control" id="exampleInputtext"
                                            placeholder="Entrer le numéro de téléphone" value="<?php echo $parcelData['phone_number'] ?>"
                                            name="phone_number" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label fw-semibold">Ville</label>
                                        <select class="form-select" aria-label="Default select example" name="city"
                                            required>
                                            <option value="Casablanca" <?php echo ($parcelData['city'] == 'Casablanca') ? 'selected' : ''; ?>>Casablanca</option>
                                            <option value="Rabat" <?php echo ($parcelData['city'] == 'Rabat') ? 'selected' : ''; ?>>Rabat</option>
                                            <option value="Fes" <?php echo ($parcelData['city'] == 'Fes') ? 'selected' : ''; ?>>Fes</option>
                                            <option value="Marrakesh" <?php echo ($parcelData['city'] == 'Marrakesh') ? 'selected' : ''; ?>>Marrakesh</option>
                                            <option value="Tangier" <?php echo ($parcelData['city'] == 'Tangier') ? 'selected' : ''; ?>>Tangier</option>
                                            <option value="Agadir" <?php echo ($parcelData['city'] == 'Agadir') ? 'selected' : ''; ?>>Agadir</option>
                                            <option value="Meknes" <?php echo ($parcelData['city'] == 'Meknes') ? 'selected' : ''; ?>>Meknes</option>
                                            <option value="Oujda" <?php echo ($parcelData['city'] == 'Oujda') ? 'selected' : ''; ?>>Oujda</option>
                                            <option value="Kenitra" <?php echo ($parcelData['city'] == 'Kenitra') ? 'selected' : ''; ?>>Kenitra</option>
                                            <option value="Tetouan" <?php echo ($parcelData['city'] == 'Tetouan') ? 'selected' : ''; ?>>Tetouan</option>
                                            <option value="Safi" <?php echo ($parcelData['city'] == 'Safi') ? 'selected' : ''; ?>>Safi</option>
                                            <option value="Khouribga" <?php echo ($parcelData['city'] == 'Khouribga') ? 'selected' : ''; ?>>Khouribga</option>
                                            <option value="El Jadida" <?php echo ($parcelData['city'] == 'El Jadida') ? 'selected' : ''; ?>>El Jadida</option>
                                            <option value="Nador" <?php echo ($parcelData['city'] == 'Nador') ? 'selected' : ''; ?>>Nador</option>
                                            <option value="Beni Mellal" <?php echo ($parcelData['city'] == 'Beni Mellal') ? 'selected' : ''; ?>>Beni Mellal</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="exampleInputPassword1"
                                            class="form-label fw-semibold">Address</label>
                                        <textarea type="text" class="form-control" id="exampleInputtext"
                                            placeholder="Nom de la rue, code postal" name="address"
                                            required><?php echo $parcelData['address'] ?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label fw-semibold">Etat</label>
                                        <select class="form-select" aria-label="Default select example" name="state"
                                            required>
                                            <option value="non payé" <?php echo ($parcelData['state'] == 'non payé') ? 'selected' : ''; ?>>non payé</option>
                                            <option value="payé" <?php echo ($parcelData['state'] == 'payé') ? 'selected' : ''; ?>>payé</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label fw-semibold">Client</label>
                                        <select class="form-select" aria-label="Default select example" name="userID"
                                            required>
                                            <?php
                                            // Loop through the users and create options
                                            while ($row = mysqli_fetch_assoc($userDataP)) {
                                                $userID = htmlspecialchars($row['id']);  // Sanitize output
                                                $full_name = htmlspecialchars($row['full_name']);  // Optional, display full_name
                                            
                                                // Check if this user is the one associated with the parcel
                                                $selected = ($userID == $selectedUserID) ? 'selected' : '';

                                                // Output the option with the selected attribute if it's the current user
                                                echo "<option value='$userID' $selected>$full_name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label fw-semibold">Tracking
                                            ID</label>
                                        <input type="text" class="form-control mt-2" id="lastName1" name="tracking_id"
                                            placeholder="Entrer Tracking ID"
                                            value="<?php echo $parcelData['tracking_id'] ?>" required />
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="" class="form-label fw-semibold">Produit</label>
                                        <select class="form-select" aria-label="Default select example" name="product"
                                            required>
                                            <?php
                                            // Loop through the products and create options
                                            while ($row = mysqli_fetch_assoc($productData)) {
                                                $product_name = htmlspecialchars($row['product_name']); // Sanitize output
                                                // Check if this product is the one selected for the parcel
                                                $selected = ($product_name == $parcelData['product']) ? 'selected' : '';
                                                echo "<option value='$product_name' $selected>$product_name</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="exampleInputPassword1"
                                            class="form-label fw-semibold">Prix</label>
                                            <input type="text" class="form-control" id="lastName1" name="price"
                                            placeholder="Entrer prix de produit"
                                            value="<?php echo $parcelData['price'] ?>" required />
                                    </div>

                                    <div class="mb-4">
                                        <label for="exampleInputPassword1"
                                            class="form-label fw-semibold">Commentaires</label>
                                        <textarea type="text" name="comments" class="form-control" id="exampleInputtext"
                                            placeholder="Ajouter commentaires ici"
                                            required><?php echo $parcelData['comments'] ?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label fw-semibold">Statut</label>
                                        <select class="form-select" aria-label="Default select example" name="status"
                                            required>
                                            <option value="Boite Vocale" <?php echo ($parcelData['status'] == 'Boite Vocale') ? 'selected' : ''; ?>>Boite Vocale</option> 
                                            <option value="Annulé" <?php echo ($parcelData['status'] == 'Annulé') ? 'selected' : ''; ?>>Annulé</option>
                                            <option value="Annulé Par Vendeur" <?php echo ($parcelData['status'] == 'Annulé Par Vendeur') ? 'selected' : ''; ?>>Annulé Par Vendeur</option>
                                            <option value="Client intéressé" <?php echo ($parcelData['status'] == 'Client intéressé') ? 'selected' : ''; ?>>Client intéressé</option>
                                            <option value="Client pas intéressé" <?php echo ($parcelData['status'] == 'Client pas intéressé') ? 'selected' : ''; ?>>Client pas intéressé</option>
                                            <option value="CLIENT PAS COMMENDE" <?php echo ($parcelData['status'] == 'CLIENT PAS COMMENDE') ? 'selected' : ''; ?>>CLIENT PAS COMMENDE</option>
                                            <option value="Client pas intéressé" <?php echo ($parcelData['status'] == 'Client pas intéressé') ? 'selected' : ''; ?>>Client pas intéressé</option>
                                            <option value="Confirmer par le livreur" <?php echo ($parcelData['status'] == 'Confirmer par le livreur') ? 'selected' : ''; ?>>Confirmer par le livreur</option>
                                            <option value="Demande de Retour" <?php echo ($parcelData['status'] == 'Demande de Retour') ? 'selected' : ''; ?>>Demande de Retour</option>
                                            <option value="Livré" <?php echo ($parcelData['status'] == 'Livré') ? 'selected' : ''; ?>>Livré</option>
                                            <option value="Deuxième Appel Pas Réponse" <?php echo ($parcelData['status'] == 'Deuxième Appel Pas Réponse') ? 'selected' : ''; ?>>Deuxième Appel Pas Réponse</option>
                                            <option value="Mise en distribution" <?php echo ($parcelData['status'] == 'Mise en distribution') ? 'selected' : ''; ?>>Mise en distribution</option>
                                            <option value="En Voyage" <?php echo ($parcelData['status'] == 'En Voyage') ? 'selected' : ''; ?>>En Voyage</option>
                                            <option value="Numero_Erroné" <?php echo ($parcelData['status'] == 'Numero_Erroné') ? 'selected' : ''; ?>>Numero_Erroné</option>
                                            <option value="En cours" <?php echo ($parcelData['status'] == 'En cours') ? 'selected' : ''; ?>>En cours</option>
                                            <option value="Pas de réponse" <?php echo ($parcelData['status'] == 'Pas de réponse') ? 'selected' : ''; ?>>Pas de réponse</option>
                                            <option value="Hors-zone" <?php echo ($parcelData['status'] == 'Hors-zone') ? 'selected' : ''; ?>>Hors-zone</option>
                                            <option value="Ramassé" <?php echo ($parcelData['status'] == 'Ramassé') ? 'selected' : ''; ?>>Ramassé</option>
                                            <option value="Reporté" <?php echo ($parcelData['status'] == 'Reporté') ? 'selected' : ''; ?>>Reporté</option>
                                            <option value="Programmé" <?php echo ($parcelData['status'] == 'Programmé') ? 'selected' : ''; ?>>Programmé</option>
                                            <option value="Reçu" <?php echo ($parcelData['status'] == 'Reçu') ? 'selected' : ''; ?>>Reçu</option>
                                            <option value="Refusé" <?php echo ($parcelData['status'] == 'Refusé') ? 'selected' : ''; ?>>Refusé</option>
                                            <option value="Relancé nouveau client" <?php echo ($parcelData['status'] == 'Relancé nouveau client') ? 'selected' : ''; ?>>Relancé nouveau client</option>
                                            <option value="Retourné" <?php echo ($parcelData['status'] == 'Retourné') ? 'selected' : ''; ?>>Retourné</option>
                                            <option value="En retour par AMANA" <?php echo ($parcelData['status'] == 'En retour par AMANA') ? 'selected' : ''; ?>>En retour par AMANA</option>
                                            <option value="Demande de Retour" <?php echo ($parcelData['status'] == 'Demande de Retour') ? 'selected' : ''; ?>>Demande de Retour</option>
                                            <option value="Expédié" <?php echo ($parcelData['status'] == 'Expédié') ? 'selected' : ''; ?>>Expédié</option>
                                            <option value="Troisième Appel Pas Réponse" <?php echo ($parcelData['status'] == 'Troisième Appel Pas Réponse') ? 'selected' : ''; ?>>Troisième Appel Pas Réponse</option>
                                            <option value="Injoignable" <?php echo ($parcelData['status'] == 'Injoignable') ? 'selected' : ''; ?>>Injoignable</option>
                                            <option value="Attende de relancer" <?php echo ($parcelData['status'] == 'Attende de relancer') ? 'selected' : ''; ?>>Attende de relancer</option>

                                        </select>
                                    </div>



                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">

                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="submit" name="submit" class="btn btn-primary">Modifier colis</button>
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

    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>
    <?php echo $alertScript; ?>
</body>

</html>