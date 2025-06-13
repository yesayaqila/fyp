<?php
$successMessage = '';
$errorMessage = '';

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = "Akaun guru berjaya didaftarkan.";
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'duplicate') {
        $errorMessage = "Nama pengguna telah didaftarkan.";
    } elseif ($_GET['error'] === 'panitia_exists') {
        $errorMessage = "Panitia ini telah mempunyai Ketua Panitia.";
    } elseif ($_GET['error'] === 'position_exists') {
        $errorMessage = "Jawatan ini telah diisi oleh guru lain.";
    } else {
        $errorMessage = "Ralat semasa mendaftar akaun.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/daftar-guru.css">
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
        <div class="form-container">

            <?php if ($successMessage): ?>
                <div class="alert success">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="alert error">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>


            <form action="form-daftar-guru.php" method="POST" class="teacher-form">

                <div class="tajuk">
                    <h1>Daftar Akaun Guru</h1>
                </div>
                <div class="form-row">
                    <label for="username">Nama Pengguna</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-row">
                    <label for="password">Kata Laluan</label>
                    <input type="text" id="password" name="password" required>
                </div>

                <div class="form-row">
                    <label for="position">Jawatan</label>
                    <select id="position" name="position" required>
                        <option value="">--- Pilih Jawatan ---</option>
                        <option value="Guru Besar">Guru Besar</option>
                        <option value="GPK 1">GPK 1 (Pentadbiran)</option>
                        <option value="GPK HEM">GPK Hal Ehwal Murid</option>
                        <option value="GPK Kokurikulum">GPK Kokurikulum</option>
                        <option value="Ketua Panitia">Ketua Panitia</option>
                        <option value="Guru">Guru</option>
                    </select>

                    <div class="form-row1" id="panitiaRow" style="display: none;">
                        <select id="panitia_id" name="panitia_id">
                            <option value="">--- Pilih Panitia ---</option>
                            <?php
                            include 'db-skpp.php';
                            $panitia = $conn->query("SELECT * FROM panitia ORDER BY panitia_name ASC");
                            while ($row = $panitia->fetch_assoc()) {
                                echo '<option value="' . $row['panitia_id'] . '">' . htmlspecialchars($row['panitia_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>




                <div class="form-row">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Aktif</option>
                        <option value="on_leave">Cuti</option>
                    </select>
                </div>
                <div class="form-row full-width">
                    <button type="submit">Cipta Akaun</button>
                </div>
            </form>
        </div>
    </div>
</body>


<script>
    // dropdown navigation
    document.querySelectorAll('.submenu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('open');
            const submenu = parent.querySelector('.submenu');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        });
    });

    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 300);
        }
    }, 3000);
</script>

<script>
    document.getElementById('position').addEventListener('change', function() {
        const selected = this.value;
        const panitiaRow = document.getElementById('panitiaRow');
        if (selected === 'Ketua Panitia') {
            panitiaRow.style.display = 'block';
        } else {
            panitiaRow.style.display = 'none';
            document.getElementById('panitia_id').value = '';
        }
    });
</script>




</html>