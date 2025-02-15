<?php
session_start();

include "config.php";
// Initialize the message variable
$message = "";
$alertScript = ''; // This will store the SweetAlert script

// Check if the form is submitted
if (isset($_POST['submit'])) {
  // Get form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare the SQL query to check if the username exists and fetch status
  $stmt = $conn->prepare("SELECT id, username, password, status FROM user_info WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // If username exists
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Check if the account is active
    if ($row['status'] == "INACTIVE") {
      $message = '<div class="alert alert-danger">Votre compte est inactif. Veuillez contacter l\'administrateur.</div>';
    } 
    // Verify the password
    else if (password_verify($password, $row['password'])) {
      // Set session variables for the logged-in user
      $_SESSION['userID'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      $alertScript = "
        <script>
            Swal.fire({
              title: 'Connecté avec succès', 
              text: 'Redirection vers dashboard...',
              icon: 'success',
              timer: 3000, // Time in milliseconds (3 seconds)
              showConfirmButton: false
            }).then(() => {
              window.location.href = 'user/dashboard.php'; // Redirect after the SweetAlert
            });
        </script>
      ";
    } else {
      $message = '<div class="alert alert-danger">Nom d\'utilisateur ou mot de passe incorrect.</div>';
    }
  } else {
    $message = '<div class="alert alert-danger">Nom d\'utilisateur ou mot de passe incorrect.</div>';
  }

  // Close the result and statement
  $result->close();
  $stmt->close();
}

// Close the connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <!--  Title -->
  <title>Se connecter</title>
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
  <!-- Core Css -->
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
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                  <img src="dist/images/logos/dark-logo.svg" width="180" alt="">
                </a>

                <div class="position-relative text-center my-4">
                  <p class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">Se connecter
                  </p>
                  <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                </div>
                <?php echo $message; ?>
                <form method="POST">
                  <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp"
                      placeholder="Entrez votre nom d'utilisateur" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password"
                      placeholder="Entrez votre mot de passe" required>
                  </div>

                  <button type="submit" name="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Se
                    connecter</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-medium">Nouveau ici?</p>
                    <a class="text-primary fw-medium ms-2" href="register.php">Créer un compte</a>
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

  <script src="dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
  <script src="dist/js/forms/sweet-alert.init.js"></script>
  <?php echo $alertScript; ?>
</body>

</html>