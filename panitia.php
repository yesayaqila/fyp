<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/panitia.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
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
                    <a href="setting.php">Tetapan</a>
                    <a href="index.php">Log Keluar</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <section class="top-section">
            <div class="search-bar-container">
                <input type="text" id="searchInput" placeholder="Cari Guru..." class="search-input" onkeyup="filterTeachers()" />
                <div id="searchDropdown" class="search-dropdown"></div>
            </div>
        </section>

        <!-- Lower Section: Teacher Subject Assignment Cards -->
        <section class="bottom-section">
            <div class="card-container">
                <!-- Sample card, repeat dynamically with PHP later -->
                <div class="teacher-card">
                    <div class="card-header">
                        <h3 class="teacher-name">MOHD IRFAN BIN ZIN</h3>
                        <div class="action-menu">
                            <button class="action-button"><i class="fas fa-ellipsis-h"></i></button>
                            <div class="dropdown">
                                <a href="#" class="edit-option"><i class="fas fa-pen"></i> Edit</a>
                                <a href="#" class="delete-option"><i class="fas fa-trash"></i> Discard</a>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="subject-rows" id="subjectRows">
                        <!-- Subject rows will be added here -->
                    </div>

                    <button class="add-subject-btn" onclick="addSubjectRow(this)"><i class="fas fa-plus"></i> Tambah Subjek</button>
                    <button class="save-subject-btn" onclick="saveSubjectData(this)"><i class="fas fa-save"></i></button>
                </div>
                <div class="teacher-card">
                    <div class="card-header">
                        <h3 class="teacher-name">test</h3>
                        <div class="action-menu">
                            <button class="action-button"><i class="fas fa-ellipsis-h"></i></button>
                            <div class="dropdown">
                                <a href="#" class="edit-option"><i class="fas fa-pen"></i> Edit</a>
                                <a href="#" class="delete-option"><i class="fas fa-trash"></i> Discard</a>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="subject-rows" id="subjectRows">
                        <!-- Subject rows will be added here -->
                    </div>

                    <button class="add-subject-btn" onclick="addSubjectRow(this)"><i class="fas fa-plus"></i> Tambah Subjek</button>
                    <button class="save-subject-btn" onclick="saveSubjectData(this)"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>
    </div>

    <script src="js/navbar.js"></script>
    <script src="js/dropdown.js"></script>
    <script src="js/add-subject.js"></script>
    
    <script src="js/cari-guru.js"></script>

</body>

</html>