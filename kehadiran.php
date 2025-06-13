<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/kehadiran.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!---navigation bar--->
    <header class="header">
        <a href="#" class="logo"> <img src="img/logo-skpp.png" alt="logo" class="logo-img">SISTEM JADUAL WAKTU</a>

        <nav class="navbar">
            <a href="welcome-ct.php">Dashboard</a>
            <a href="panitia.php">Panitia</a>
            <a href="kehadiran.php">Kehadiran</a>
        </nav>


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

    <main class="main-content">
        <div class="content-wrapper">
            <!-- Left Section: Notify Absence Form -->
            <section class="left-section">
                <h2>MAKLUMKAN KETIDAKHADIRAN</h2>
                <form id="absenceForm">
                    <label for="date">Tarikh Mula:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="date">Tarikh Akhir:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="reason">Sebab:</label>
                    <textarea id="reason" name="reason" rows="4" required></textarea>

                    <button type="submit">Hantar</button>
                </form>
            </section>

            <!-- Right Section: Calendar & Record -->
            <section class="right-section-content">
                <div class="calendar-section">
                    <h3>Kalendar</h3>
                    <!-- Replace with your actual calendar script/widget -->
                    <div class="calendar-placeholder">[Kalendar Placeholder]</div>
                </div>

                <div class="record-section">
                    <h3>Rekod Ketidakhadiran</h3>
                    <div class="record-placeholder">[Senarai rekod ketidakhadiran]</div>
                </div>
            </section>
        </div>
    </main>



    <script src="js/navbar.js"></script>
    <script src="js/dropdown.js"></script>

</body>

</html>