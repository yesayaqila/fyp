<?php
include 'auth-check.php';

// Optional: Redirect if wrong page
$currentPage = basename($_SERVER['PHP_SELF']);

if ($_SESSION['is_admin'] == 1 && $currentPage != "welcome-admin.php") {
    header("Location: welcome-admin.php");
    exit;
} elseif (strtolower($_SESSION['position']) == 'ketua panitia' && $currentPage != "welcome-ct.php") {
    header("Location: welcome-ct.php");
    exit;
} elseif (strtolower($_SESSION['position']) != 'ketua panitia' && $_SESSION['is_admin'] == 0 && $currentPage != "welcome-guru.php") {
    header("Location: welcome-guru.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/welcome-guru.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!---navigation bar--->
    <header class="header">
        <a href="#" class="logo"> <img src="img/logo-skpp.png" alt="logo" class="logo-img">SISTEM JADUAL WAKTU</a>

        <nav class="navbar">
            <a href="welcome-guru.php">Dashboard</a>
            <a href="kehadiran-guru.php">Kehadiran</a>
        </nav>


        <div class="right-section">
            <i class="fas fa-bell notification-icon"></i>
            <div class="user-menu">
                <button class="user-icon">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="setting.php">Ketetapan</a>
                    <a href="logout.php">Log Keluar</a>
                </div>
            </div>
        </div>
    </header>

    <script src="js/navbar.js"></script>
    <script src="js/dropdown.js"></script>

</body>

</html>