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
    <link rel="stylesheet" href="css/welcome-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!---navigation bar--->
    <header class="header">
        <a href="#" class="logo"> <img src="img/logo-skpp.png" alt="logo" class="logo-img">SISTEM JADUAL WAKTU</a>


        <div class="right-section">
            <i class="fas fa-bell notification-icon"></i>
            <div class="user-menu">
                <button class="user-icon">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div class="dropdown-menu">

                    <div class="switch-account-section">
                        <p class="switch-label">Tukar Akaun Sebagai:</p>
                        <a href="welcome-guru.php" class="switch-option">
                            <i class="fas fa-chalkboard-teacher"></i> Guru
                        </a>
                    </div>

                    <hr class="dropdown-divider">
                    <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Log Keluar</a>
                </div>

            </div>
        </div>
    </header>

    <nav class="sidebar">
        <ul>
            <li>
                <a href="welcome-admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="has-submenu">
                <a href="#" class="submenu-toggle">
                    <i class="fas fa-user-tie"></i> Guru <span class="arrow">&lt;</span>
                </a>
                <ul class="submenu">
                    <li><a href="daftar-guru.php">Daftar Guru</a></li>
                    <li><a href="senarai-guru.php">Senarai Guru</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" class="submenu-toggle">
                    <i class="fas fa-calendar-alt"></i> Jadual <span class="arrow">&lt;</span>
                </a>
                <ul class="submenu">
                    <li><a href="akademik.php">Akademik</a></li>
                    <li><a href="constraints.php">Tetapan</a></li>
                    <li><a href="jana-jadual.php">Jana Jadual</a></li>
                    <li><a href="senarai-jadual.php">Senarai Jadual</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" class="submenu-toggle">
                    <i class="fas fa-plane-departure"></i> Cuti & Relief <span class="arrow">&lt;</span>
                </a>
                <ul class="submenu">
                    <li><a href="senarai-cuti.php">Ketidakhadiran</a></li>
                    <li><a href="guru-ganti.php">Guru Ganti</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!---navigation bar--->

    <div class="main-content">
        <h2>Selamat Datang ke Sistem Jadual Waktu</h2>
        <p>Sila pilih menu di sebelah kiri untuk mula menggunakan sistem.</p>
    </div>

    <script>
        document.querySelectorAll('.submenu-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle('open');
                const submenu = parent.querySelector('.submenu');
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>

</body>

</html>