<?php $currentTab = 'kekangan'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/constraints.css">
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
        <div class="tab-buttons">
            <button class="tab-btn <?= ($currentTab === 'kelas') ? 'active' : '' ?>" onclick="location.href='constraints.php'">Kelas / Subjek</button>
            <button class="tab-btn <?= ($currentTab === 'had') ? 'active' : '' ?>" onclick="location.href='had-mengajar.php'">Had Mengajar</button>
            <button class="tab-btn <?= ($currentTab === 'kekangan') ? 'active' : '' ?>" onclick="location.href='kekangan-jadual.php'">Kekangan Jadual</button>
        </div>



        <!-- ✅ Wrap each tab content with a common box -->
        <div class="tab-wrapper">
        </div>
    </div>

    <script src="js/dropdown.js"></script>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

            document.getElementById(tabId).style.display = 'block';
            document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        function generateCards() {
            const container = document.getElementById('kelasOutput');
            container.innerHTML = '';

            const darjahCount = parseInt(document.getElementById('bilDarjah').value);
            const kelasCount = parseInt(document.getElementById('bilKelas').value);

            for (let d = 1; d <= darjahCount; d++) {
                const title = document.createElement('h4');
                title.innerText = `DARJAH ${d}`;
                container.appendChild(title);

                const row = document.createElement('div');
                row.style.display = 'flex';
                row.style.flexWrap = 'wrap';
                row.style.gap = '1.5rem';

                const classNames = ["Al-Biruni", "Al-Farabi", "Al-Ghazali", "Al-Kindi"];

                for (let k = 0; k < kelasCount; k++) {
                    const card = document.createElement('div');
                    card.style.border = '1px solid #ccc';
                    card.style.borderRadius = '8px';
                    card.style.padding = '1rem';
                    card.style.width = '200px';
                    card.innerHTML = `
                <strong>${classNames[k] || `Kelas ${k + 1}`}</strong>
                <br><button style="margin-top:10px;" class="btn-update">+ Tambah Subjek</button>
            `;
                    row.appendChild(card);
                }

                container.appendChild(row);
            }
        }
    </script>

</body>

</html>