<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jana Jadual</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/senarai-cuti.css">
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
                    <a href="#">Tetapan</a>
                    <a href="index.php">Log Keluar</a>
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
        <h2>Display senarai application cuti, record2</h2>
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