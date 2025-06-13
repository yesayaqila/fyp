<?php
include('db-skpp.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ralat: ID guru tidak sah.");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM teachers WHERE teacher_id = $id");
if (!$result || mysqli_num_rows($result) == 0) {
    die("Guru dengan ID ini tidak dijumpai.");
}

$teacher = mysqli_fetch_assoc($result);

$teachings = mysqli_query($conn, "
    SELECT s.subject_name, c.grade, c.class_name
FROM teacher_subject_class tsc
JOIN subjects s ON tsc.subject_id = s.subject_id
JOIN classes c ON tsc.class_id = c.class_id
WHERE tsc.teacher_id = $id

");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Guru</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/guru-detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Navigation bar -->
    <header class="header">
        <a href="#" class="logo"> <img src="img/logo-skpp.png" alt="logo" class="logo-img">Sistem Jadual Waktu</a>
        <div class="right-section">
            <i class="fas fa-bell notification-icon"></i>
            <div class="user-menu">
                <button class="user-icon"><i class="fas fa-user-circle"></i></button>
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

    <div class="main-content">
        <form method="POST" action="update-teacher.php" class="teacher-form">
            <input type="hidden" name="teacher_id" value="<?= $teacher['teacher_id'] ?>">

            <div class="form-row">
                <label>Nama Penuh</label>
                <input type="text" name="name" value="<?= htmlspecialchars($teacher['name']) ?>">
            </div>

            <div class="form-row">
                <label>Emel</label>
                <input type="email" name="email" value="<?= htmlspecialchars($teacher['email']) ?>">
            </div>

            <div class="form-row">
                <label>Nombor Telefon</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($teacher['phone']) ?>">
            </div>

            <div class="form-row">
                <label>Jawatan</label>
                <select name="role">
                    <?php
                    $positions = [
                        'Guru Besar',
                        'GPK 1 (Pentadbiran)',
                        'GPK Hal Ehwal Murid',
                        'GPK Kokurikulum',
                        'Ketua Panitia',
                        'Guru'
                    ];

                    foreach ($positions as $position) {
                        $selected = $teacher['position'] === $position ? 'selected' : '';
                        echo "<option value=\"$position\" $selected>$position</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="form-row">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $teacher['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="on_leave" <?= $teacher['status'] == 'on_leave' ? 'selected' : '' ?>>Cuti</option>
                </select>
            </div>

            <div class="form-admin">
                <label>
                    <input type="checkbox" name="is_admin" value="1" <?= $teacher['is_admin'] ? 'checked' : '' ?>>
                    Jadikan Guru Ini Sebagai Admin Sistem
                </label>
            </div>

            <h4>Mengajar Matapelajaran</h4>
            <div class="teaching-list">
                <?php if ($teachings && mysqli_num_rows($teachings) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($teachings)): ?>
                        <div class="subject-item">
                            <?= htmlspecialchars($row['subject_name']) ?> - Darjah <?= $row['grade'] ?> <?= $row['class_name'] ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="subject-item">Tiada subjek dijumpai.</div>
                <?php endif; ?>
            </div>

            <div class="form-row full-width" style="margin-top: 2rem;">
                <button type="submit" class="save-btn">Simpan</button>
            </div>
        </form>



    </div>

</body>

<script>
    //dropdown navigation bar
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


</html>