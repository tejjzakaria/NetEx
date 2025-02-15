<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config.php";
include "checkSession.php";
include "fetchUserData.php";

$message = "";
$alertScript = ''; // SweetAlert script for user info update
$alertScript2 = ''; // SweetAlert script for password update

// User Info Update
if (isset($_POST['submit'])) {
    // Get form data
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $cin = $_POST['cin'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $bank_name = $_POST['bank_name'];
    $bank_account = $_POST['bank_account'];
    $username = $_POST['username'];

    // Prepare SQL update statement
    $sql = "UPDATE user_info SET 
                full_name = ?, 
                cin = ?, 
                email = ?, 
                phone_number = ?, 
                city = ?, 
                address = ?, 
                bank_name = ?, 
                bank_account = ?, 
                username = ?
            WHERE id = ?";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $full_name, $cin, $email, $phone_number, $city, $address, $bank_name, $bank_account, $username, $id);

    // Execute the statement
    if ($stmt->execute()) {

        $alertScript = "
        <script>
            Swal.fire({
              title: 'Info modifié', 
              text: 'Redirection vers réglages de profile...',
              icon: 'success',
              timer: 3000, // Time in milliseconds (3 seconds)
              showConfirmButton: false
            }).then(() => {
              window.location.href = 'viewProfile.php'; // Redirect after the SweetAlert
            });
        </script>
        ";
    } else {
        $message = "<div class='alert alert-danger'>Error updating user: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Password Update
$message_2 = '';
if (isset($_POST['submit-password'])) {
    $userID = $_POST['id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new_password) {
        $alertScript2 = '
                    <script>
            Swal.fire({
          type: "error",
          title: "Oops...",
          text: "Les mots de passe ne correspond pas",
        });
        </script>';
    } else {
        $sql = "SELECT password FROM user_info WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $hashed_password = $user['password'];

            if (password_verify($current_password, $hashed_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_sql = "UPDATE user_info SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_hashed_password, $userID);

                if ($update_stmt->execute()) {
                    // Trigger SweetAlert on success for password update
                    $alertScript2 = "
                    <script>
            Swal.fire({
              title: 'Mot de passe modifié', 
              text: 'Redirection vers réglages de profile...',
              icon: 'success',
              timer: 3000, // Time in milliseconds (3 seconds)
              showConfirmButton: false
            }).then(() => {
              window.location.href = 'viewProfile.php'; // Redirect after the SweetAlert
            });
        </script>";
                } else {
                    $alertScript2 = '
                    <script>
            Swal.fire({
          type: "error",
          title: "Oops...",
          text: "Une erreur s\'est produite lors de la mise à jour du mot de passe.",
        });
        </script>';

                }
                $update_stmt->close();
            } else {
                $alertScript2 = '
                    <script>
            Swal.fire({
          type: "error",
          title: "Oops...",
          text: "Le mot de passe actuel est incorrect.",
        });
        </script>';
            }
        } else {
            $alertScript2 = '
                    <script>
            Swal.fire({
          type: "error",
          title: "Oops...",
          text: "Utilisateur non trouvé.",
        });
        </script>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Profil d'utilisateur</title>
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
                                <h4 class="fw-semibold mb-8">Utilisateurs</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="viewProfile.php">Modifier Utilisateur</a>
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



                <div class="card">
                    <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-4"
                                id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account"
                                type="button" role="tab" aria-controls="pills-account" aria-selected="true">
                                <i class="ti ti-user-circle me-2 fs-6"></i>
                                <span class="d-none d-md-block">Compte</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4"
                                id="pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#pills-notifications"
                                type="button" role="tab" aria-controls="pills-notifications" aria-selected="false">
                                <i class="ti ti-lock me-2 fs-6"></i>
                                <span class="d-none d-md-block">Sécurité</span>
                            </button>
                        </li>



                    </ul>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-account" role="tabpanel"
                                aria-labelledby="pills-account-tab" tabindex="0">
                                <div class="row">
                                    <?php echo $message ?>


                                    <div class="col-12">
                                        <div class="card w-100 position-relative overflow-hidden mb-0">
                                            <div class="card-body p-4">
                                                <h5 class="card-title fw-semibold">Détails personnels</h5>
                                                <p class="card-subtitle mb-4">Pour modifier vos informations
                                                    personnelles, modifiez-les et enregistrez-les ici</p>
                                                <form action="" method="POST">
                                                    <input type="hidden" name="id"
                                                        value="<?php echo $userData['id'] ?>">
                                                    <section>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="firstName1">Nom complet*</label>
                                                                    <input type="text" class="form-control mt-2"
                                                                        id="firstName1" name="full_name"
                                                                        placeholder="Entrer votre nom complet"
                                                                        value="<?php echo $userData['full_name'] ?>"
                                                                        required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="lastName1">CIN*</label>
                                                                    <input type="text" class="form-control mt-2"
                                                                        id="lastName1" name="cin"
                                                                        placeholder="Entrer votre C.I.N"
                                                                        value="<?php echo $userData['cin'] ?>"
                                                                        required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="emailAddress1">Adresse Email</label>
                                                                    <input type="email" class="form-control mt-2"
                                                                        id="emailAddress1" name="email"
                                                                        placeholder="Entrer votre adresse email"
                                                                        value="<?php echo $userData['email'] ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="phoneNumber1">Numéro du
                                                                        téléphone*</label>
                                                                    <input type="tel" class="form-control mt-2"
                                                                        id="phoneNumber1" name="phone_number"
                                                                        placeholder="Entrer votre numéro de téléphone"
                                                                        required
                                                                        value="<?php echo $userData['phone_number'] ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="emailAddress1">Ville*</label>
                                                                    <select class="form-select"
                                                                        aria-label="Default select example" name="city"
                                                                        required>
                                                                        <option value="Casablanca" <?php echo ($userData['city'] == 'Casablanca') ? 'selected' : ''; ?>>Casablanca
                                                                        </option>
                                                                        <option value="Rabat" <?php echo ($userData['city'] == 'Rabat') ? 'selected' : ''; ?>>Rabat</option>
                                                                        <option value="Fes" <?php echo ($userData['city'] == 'Fes') ? 'selected' : ''; ?>>Fes</option>
                                                                        <option value="Marrakesh" <?php echo ($userData['city'] == 'Marrakesh') ? 'selected' : ''; ?>>Marrakesh</option>
                                                                        <option value="Tangier" <?php echo ($userData['city'] == 'Tangier') ? 'selected' : ''; ?>>Tangier</option>
                                                                        <option value="Agadir" <?php echo ($userData['city'] == 'Agadir') ? 'selected' : ''; ?>>Agadir</option>
                                                                        <option value="Meknes" <?php echo ($userData['city'] == 'Meknes') ? 'selected' : ''; ?>>Meknes</option>
                                                                        <option value="Oujda" <?php echo ($userData['city'] == 'Oujda') ? 'selected' : ''; ?>>Oujda</option>
                                                                        <option value="Kenitra" <?php echo ($userData['city'] == 'Kenitra') ? 'selected' : ''; ?>>Kenitra</option>
                                                                        <option value="Tetouan" <?php echo ($userData['city'] == 'Tetouan') ? 'selected' : ''; ?>>Tetouan</option>
                                                                        <option value="Safi" <?php echo ($userData['city'] == 'Safi') ? 'selected' : ''; ?>>Safi</option>
                                                                        <option value="Khouribga" <?php echo ($userData['city'] == 'Khouribga') ? 'selected' : ''; ?>>Khouribga</option>
                                                                        <option value="El Jadida" <?php echo ($userData['city'] == 'El Jadida') ? 'selected' : ''; ?>>El Jadida</option>
                                                                        <option value="Nador" <?php echo ($userData['city'] == 'Nador') ? 'selected' : ''; ?>>Nador</option>
                                                                        <option value="Beni Mellal" <?php echo ($userData['city'] == 'Beni Mellal') ? 'selected' : ''; ?>>Beni Mellal</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="phoneNumber1">Adresse*</label>
                                                                    <input type="text" class="form-control mt-2"
                                                                        id="phoneNumber1" name="address"
                                                                        placeholder="Entrer votre adresse" required
                                                                        value="<?php echo $userData['address'] ?>" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </section>
                                                    <section>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="jobTitle1">Nom du banque*</label>
                                                                    <select class="form-select mt-2"
                                                                        aria-label="Default select example"
                                                                        name="bank_name" required>
                                                                        <option value="CIH Bank" <?php echo ($userData['bank_name'] == 'CIH Bank') ? 'selected' : ''; ?>>CIH Bank</option>
                                                                        <option value="BMCE Bank" <?php echo ($userData['bank_name'] == 'BMCE Bank') ? 'selected' : ''; ?>>BMCE Bank</option>
                                                                        <option value="BMCI Bank" <?php echo ($userData['bank_name'] == 'BMCI Bank') ? 'selected' : ''; ?>>BMCI Bank</option>
                                                                        <option value="ATTIJARIWAFA Bank" <?php echo ($userData['bank_name'] == 'ATTIJARIWAFA Bank') ? 'selected' : ''; ?>>ATTIJARIWAFA
                                                                            Bank</option>
                                                                        <option value="Banque populaire" <?php echo ($userData['bank_name'] == 'Banque populaire') ? 'selected' : ''; ?>>Banque Populaire
                                                                        </option>
                                                                        <option value="Barid Bank" <?php echo ($userData['bank_name'] == 'Barid Bank') ? 'selected' : ''; ?>>Barid Bank</option>
                                                                        <option value="Credit De Maroc" <?php echo ($userData['bank_name'] == 'Credit De Maroc') ? 'selected' : ''; ?>>Credit De Maroc
                                                                        </option>
                                                                        <option value="Other" <?php echo ($userData['bank_name'] == 'Other') ? 'selected' : ''; ?>>Autre</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="videoUrl1">Numéro de compte*</label>
                                                                    <input type="text" class="form-control mt-2"
                                                                        id="videoUrl1" name="bank_account" required
                                                                        placeholder="Entrer le numéro de compte "
                                                                        value="<?php echo $userData['bank_account'] ?>" />
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </section>
                                                    <section>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="int1">Nom d'utilisateur*</label>
                                                                    <input type="text" class="form-control mt-2"
                                                                        id="int1"
                                                                        placeholder="Entrer un nom d'utilisateur"
                                                                        required name="username"
                                                                        value="<?php echo $userData['username'] ?>" />
                                                                </div>

                                                            </div>

                                                        </div>


                                                    </section>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <button type="submit" name="submit"
                                                            class="btn btn-primary mt-4">Modifier info</button>
                                                    </div>


                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-notifications" role="tabpanel"
                        aria-labelledby="pills-notifications-tab" tabindex="0">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div class="card">

                                    <div class="card-body p-4">
                                        <?php echo $message_2 ?>
                                        <h4 class="fw-semibold mb-3">
                                            Réglages mot de passe
                                        </h4>
                                        <p>
                                            Modifier votre mot de passe ici.
                                        </p>

                                        <form action="" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $userData['id'] ?>">
                                            <section>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="firstName1">Mot de passe actuel*</label>
                                                            <input type="password" class="form-control mt-2"
                                                                id="firstName1" name="current_password"
                                                                placeholder="Votre mot de passe actuel" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="lastName1">Nouveau mot de passe*</label>
                                                            <input type="password" class="form-control mt-2"
                                                                id="lastName1" name="new_password"
                                                                placeholder="Votre nouveau mot de passe" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="lastName1">Confirmer nouveau mot de
                                                                passe*</label>
                                                            <input type="password" class="form-control mt-2"
                                                                id="lastName1" name="confirm_new_password"
                                                                placeholder="Confirmer votre nouveau mot de passe"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>



                                            </section>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <button type="submit" name="submit-password"
                                                    class="btn btn-primary mt-4">Modifier mot de passe</button>
                                            </div>


                                        </form>


                                    </div>

                                </div>

                            </div>


                            <div class="col-12 m-6">

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


    <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="dist/js/forms/sweet-alert.init.js"></script>
    <?php echo $alertScript; ?>
    <?php echo $alertScript2; ?>

</body>

</html>