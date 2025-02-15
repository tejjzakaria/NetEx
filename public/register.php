<?php
session_start();

include "config.php";
// Initialize the message variable
$message = "";
$alertScript = ''; // This will store the SweetAlert script

// Check if the form is submitted
if (isset($_POST['submit'])) {
  // Get form data
  $full_name = $_POST['full_name'];
  $cin = $_POST['cin'];
  $email = $_POST['email'];
  $phone_number = $_POST['phone_number'];
  $city = $_POST['city'];
  $address = $_POST['address'];
  $bank_name = $_POST['bank_name'];
  $bank_account = $_POST['bank_account'];
  $username = $_POST['username'];
  $password = $_POST['password']; // Password to be hashed
  $confirm_password = $_POST['confirm_password']; // Repeated password

  $status = "INACTIVE";

  // Validate password and confirm password
  if ($password !== $confirm_password) {
    $message = '<div class="alert alert-danger">Les mots de passe ne correspondent pas.</div>';
  } else {
    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM user_info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If username already exists
    if ($result->num_rows > 0) {
      $message = '<div class="alert alert-danger">Le nom d\'utilisateur existe déjà.</div>';
    } else {
      // Hash the password for security
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare an SQL statement to prevent SQL injection
      $stmt = $conn->prepare("INSERT INTO user_info (full_name, cin, email, phone_number, city, address, bank_name, bank_account, username, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

      // Bind parameters
      $stmt->bind_param("sssssssssss", $full_name, $cin, $email, $phone_number, $city, $address, $bank_name, $bank_account, $username, $hashed_password, $status);

      // Execute the statement
      if ($stmt->execute()) {
        // Fetch the newly created user ID
        $user_id = $stmt->insert_id;

        // Set session variable for user ID
        $_SESSION['userID'] = $user_id;

        // Set session timestamp
        $_SESSION['login_timestamp'] = time();

        // Redirect to dashboard
        $alertScript = "
        <script>
    Swal.fire({
        title: 'Demande reçue avec succès', 
        text: 'Merci ! Nous avons bien reçu vos informations. Veuillez nous accorder un peu de temps pour les vérifier et activer votre compte. Ce processus ne devrait pas prendre plus de 24 heures...',
        icon: 'success',
        confirmButtonText: 'Fermer'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php'; // Replace with your actual page
        }
    });
</script>

      ";
      } else {
        $message = '<div class="alert alert-danger">Erreur:</div> ' . $stmt->error;
      }
    }

    // Close the result and statement
    $result->close();
    $stmt->close();
  }
}

// Close the connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <!--  Title -->
  <title>Créer un compte</title>
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
  <link rel="stylesheet" href="dist/libs/sweetalert2/dist/sweetalert2.min.css">


  <!-- Core Css -->
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
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6">
            <div class="card">

              <div class="card-body wizard-content">
                <a href="" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                  <img src="dist/images/logos/dark-logo.svg" width="180" alt="">
                </a>
                <?php echo $message ?>

                <form action="" method="POST">
                  <section>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="firstName1">Nom complet*</label>
                          <input type="text" class="form-control mt-2" id="firstName1" name="full_name"
                            placeholder="Entrer votre nom complet" required />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="lastName1">CIN*</label>
                          <input type="text" class="form-control mt-2" id="lastName1" name="cin"
                            placeholder="Entrer votre C.I.N" required />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="emailAddress1">Adresse Email</label>
                          <input type="email" class="form-control mt-2" id="emailAddress1" name="email"
                            placeholder="Entrer votre adresse email" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="phoneNumber1">Numéro du téléphone*</label>
                          <input type="tel" class="form-control mt-2" id="phoneNumber1" name="phone_number"
                            placeholder="Entrer votre numéro de téléphone" required />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="emailAddress1">Ville*</label>
                          <select class="form-select mt-2" aria-label="Default select example" name="city" required>
                            <option value="Casablanca">Casablanca</option>
                            <option value="Rabat">Rabat</option>
                            <option value="Fes">Fes</option>
                            <option value="Marrakesh">Marrakesh</option>
                            <option value="Tangier">Tangier</option>
                            <option value="Agadir">Agadir</option>
                            <option value="Meknes">Meknes</option>
                            <option value="Oujda">Oujda</option>
                            <option value="Kenitra">Kenitra</option>
                            <option value="Tetouan">Tetouan</option>
                            <option value="Safi">Safi</option>
                            <option value="Khouribga">Khouribga</option>
                            <option value="El Jadida">El Jadida</option>
                            <option value="Nador">Nador</option>
                            <option value="Beni Mellal">Beni Mellal</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="phoneNumber1">Adresse*</label>
                          <input type="text" class="form-control mt-2" id="phoneNumber1" name="address"
                            placeholder="Entrer votre adresse" required />
                        </div>
                      </div>
                    </div>

                  </section>
                  <section>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="jobTitle1">Nom du banque*</label>
                          <select class="form-select mt-2" aria-label="Default select example" name="bank_name"
                            required>
                            <option value="CIH Bank">CIH Bank</option>
                            <option value="BMCE Bank">BMCE Bank</option>
                            <option value="BMCI Bank">BMCI Bank</option>
                            <option value="ATTIJARIWAFA Bank">ATTIJARIWAFA Bank</option>
                            <option value="Banque populaire">Banque Populaire</option>
                            <option value="Barid Bank">Barid Bank</option>
                            <option value="Credit De Maroc">Credit De Maroc</option>
                            <option value="Other">Autre</option>

                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="videoUrl1">Numéro de compte*</label>
                          <input type="text" class="form-control mt-2" id="videoUrl1" name="bank_account" required
                            placeholder="Entrer le numéro de compte " />
                        </div>
                      </div>

                    </div>
                  </section>
                  <section>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="mb-3">
                          <label for="int1">Nom d'utilisateur*</label>
                          <input type="text" class="form-control mt-2" id="int1"
                            placeholder="Entrer un nom d'utilisateur" required name="username" />
                        </div>

                      </div>

                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="int1">Mot de passe*</label>
                          <input type="password" class="form-control mt-2" id="int1"
                            placeholder="Entrer un mot de passe" required name="password" />
                        </div>

                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="int1">Retaper mot de passe*</label>
                          <input type="password" class="form-control mt-2" id="int1"
                            placeholder="Entrer le mot de passe à nouveau " required name="confirm_password" />
                        </div>

                      </div>

                    </div>
                  </section>
                  <div class="d-flex align-items-center justify-content-between">
                    <button type="submit" name="submit" class="btn btn-primary mt-4">Créer un compte</button>
                    <div class="d-flex align-items-center justify-content-center">
                      <p class="mb-0 fw-normal">Avez vous déja un compte?</p>
                      <a class="text-primary fw-normal ms-2" href="index.php">Se connecter ici</a>
                    </div>
                  </div>


                </form>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>






  <!--  Import Js Files -->
  <script src="dist/libs/jquery/dist/jquery.min.js"></script>
  <script src="dist/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!--  core files -->
  <script src="dist/js/app.min.js"></script>
  <script src="dist/js/app.init.js"></script>
  <script src="dist/js/app-style-switcher.js"></script>
  <script src="dist/js/sidebarmenu.js"></script>

  <script src="dist/js/custom.js"></script>

  <script src="dist/libs/jquery-steps/build/jquery.steps.min.js"></script>
  <script src="dist/libs/jquery-validation/dist/jquery.validate.min.js"></script>
  <script src="dist/js/forms/form-wizard.js"></script>

  <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
  <script src="dist/js/forms/sweet-alert.init.js"></script>
  <?php echo $alertScript; ?>


</body>

</html>