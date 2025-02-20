<?php
include "../config.php";
include 'fetchUserData.php';
include "updateActivity.php";

$userID = $_SESSION['userID'];

$query = "SELECT * FROM notifications WHERE userID = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
 

$table_data44 = '';
while ($notif = $result->fetch_assoc()) {
    $notifID = $notif['id'];
    $message = $notif['message'];
    $status = $notif['status'];
    $created_at = $notif['created_at'];

    // Show the blue dot only for unread notifications
    $dot = ($status == 'unread') ? "<div class='notification-dot bg-primary rounded-circle'></div>" : "";

    $table_data44 = "
    <a href='javascript:void(0)' class='py-6 px-7 d-flex align-items-center dropdown-item position-relative' onclick='markAsRead($notifID, this)'>
        <span class='me-3 position-relative'>
            <img src='dist/images/profile/bell.png' alt='notification' class='rounded-circle' width='48' height='48' />
            $dot
        </span>
        <div class='w-75 d-inline-block v-middle'>
            <h6 class='mb-1 fw-semibold' style='white-space: normal; /* Allows text to wrap */
    word-wrap: break-word; /* Ensures long words break properly */
    overflow-wrap: break-word; /* Alternative for breaking long words */
    max-width: 100%; /* Ensures it does not overflow */'>$message</h6>
            <span class='d-block'>$created_at</span>
        </div>
    </a>
    " . $table_data44;
}



?>

<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            
        </ul>

        <div class="d-block d-lg-none" style="width: 10%;">
            <img src="dist/images/logos/dark-logo.svg" class="dark-logo" />
        </div>
        <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
            </span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
                <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                    aria-controls="offcanvasWithBothOptions">
                    <i class="ti ti-align-justified fs-7"></i>
                </a>
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">


                    <li class="nav-item dropdown">
                        <button type="button" id="markReadBtn" class="nav-link nav-icon-hover bg-transparent border-0"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-bell-ringing"></i>
                            <div id="notificationDot" class="notification bg-primary rounded-circle"
                                style="display: <?php echo ($row['unread_count'] > 0) ? 'block' : 'none'; ?>;">
                            </div>
                        </button>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            id="notificationDropdown">
                            <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                
                            </div>
                            <div class="message-body mb-3" data-simplebar>
                                
                                <?php echo $table_data44; ?>
                            </div>
                        </div>
                    </li>




                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img">
                                    <img src="dist/images/profile/user-1.png" class="rounded-circle" width="35"
                                        height="35" alt="" />
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                <div class="py-3 px-7 pb-0">
                                    <h5 class="mb-0 fs-5 fw-semibold">Profil d'utilisateur</h5>
                                </div>
                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                    <img src="dist/images/profile/user-1.png" class="rounded-circle" width="80"
                                        height="80" alt="" />
                                    <div class="ms-3">
                                        <h5 class="mb-1 fs-3"><?php echo $userData['full_name']; ?></h5>
                                        <span class="mb-1 d-block text-dark"><?php echo $userData['username']; ?></span>
                                        <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                            <i class="ti ti-phone fs-4"></i><?php echo $userData['phone_number']; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="message-body">
                                    <a href="viewProfile.php" class="py-8 px-7 mt-8 d-flex align-items-center">
                                        <span
                                            class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="dist/images/svgs/icon-account.svg" alt="" width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold"> Mon profil </h6>
                                            <span class="d-block text-dark">Paramètres du compte</span>
                                        </div>
                                    </a>

                                    <a href="viewSheets.php" class="py-8 px-7 mt-8 d-flex align-items-center">
                                        <span
                                            class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="dist/images/svgs/icon-tasks.svg" alt="" width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold"> Mes feuilles de calcul </h6>
                                            <span class="d-block text-dark">Paramètres Google Sheets</span>
                                        </div>
                                    </a>


                                </div>
                                <div class="d-grid py-4 px-7 pt-8">

                                    <a href="logout.php" class="btn btn-outline-primary">Se déconnecter</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>





<script>
    document.getElementById("markReadBtn").addEventListener("click", function (event) {
        event.preventDefault();
        event.stopPropagation();

        fetch("markAllNotificationsRead.php", {
            method: "POST", 
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: ""
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Hide the notification dot
                    let notifDot = document.getElementById("notificationDot");
                    if (notifDot) notifDot.style.display = (data.unread_count > 0) ? "block" : "none";

                    // Update the notification count dynamically
                    let notifCount = document.getElementById("notifCount");
                    if (notifCount) notifCount.innerText = `${data.unread_count} Notifications`;

                    // Manually show the dropdown
                    let dropdownMenu = document.getElementById("notificationDropdown");
                    if (dropdownMenu) {
                        dropdownMenu.classList.add("show");
                        document.getElementById("markReadBtn").setAttribute("aria-expanded", "true");
                    }
                }
            })
            .catch(error => console.error("Error:", error));
    });

</script>