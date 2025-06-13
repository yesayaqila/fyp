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

    <div class="main-content">
        <form action="form-daftar-guru.php" method="POST" class="teacher-form">
            <div class="form-row">
                <label for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-row">
                <label for="password">Kata Laluan</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-row">
                <label>Jawatan</label>
                <input type="text" id="position" name="position">
            </div>

            <div class="form-row">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Aktif</option>
                    <option value="on_leave">Cuti</option>
                </select>
            </div>

            <label for="matapelajaran">Mengajar</label>
            <div id="subject-class-container">
                <div class="form-row subject-class-row">
                    <select name="matapelajaran[]" class="dropdown">
                        <option value="">Matapelajaran</option>
                        <?php while ($row = mysqli_fetch_assoc($subjects)) : ?>
                            <option value="<?= htmlspecialchars($row['subject_name']) ?>">
                                <?= htmlspecialchars($row['subject_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>


                    <select name="gred[]" class="dropdown gred-select" onchange="filterSubjects(this)">
                        <option value="">Darjah</option>
                        <?php while ($row = mysqli_fetch_assoc($grades)) : ?>
                            <option value="<?= htmlspecialchars($row['grade']) ?>">
                                <?= htmlspecialchars($row['grade']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>


                    <select name="kelas[]" class="dropdown">
                        <option value="">Kelas</option>
                        <?php while ($row = mysqli_fetch_assoc($classes)) : ?>
                            <option value="<?= htmlspecialchars($row['class_name']) ?>">
                                <?= htmlspecialchars($row['class_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>


                    <button type="button" class="delete-row" onclick="removeRow(this)">🗑️</button>
                </div>
            </div>

            <!-- Add Subjects-->
            <div style="margin-top: 1rem;">
                <button type="button" onclick="addSubjectClassRow()" style="background-color: green; color: white; padding: 0.7rem 1.5rem; border: none; border-radius: 5px; font-size: 1.4rem;">+ Tambah Lagi</button>
            </div>



            <div class="form-row full-width">
                <button type="submit">Cipta Akaun</button>
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

    //format untuk no phone
    document.getElementById("phone").addEventListener("input", function(e) {
        let input = e.target.value.replace(/\D/g, "");
        if (input.length > 3) {
            input = input.slice(0, 3) + "-" + input.slice(3, 11);
        }
        e.target.value = input.slice(0, 12);
    });

    function addSubjectClassRow() {
        const container = document.getElementById('subject-class-container');
        const firstRow = container.querySelector('.subject-class-row');
        const newRow = firstRow.cloneNode(true);

        // Clear selections in the new row
        newRow.querySelectorAll('select').forEach(select => select.value = '');
        container.appendChild(newRow);
    }

    function removeRow(button) {
        const row = button.closest('.subject-class-row');
        const container = document.getElementById('subject-class-container');
        if (container.querySelectorAll('.subject-class-row').length > 1) {
            row.remove();
        } else {
            alert("Guru perlu mengajar sekurang-kurangnya satu matapelajaran.");
        }
    }

    function filterSubjects(select) {
        const grade = parseInt(select.value);
        const row = select.closest('.subject-class-row');
        const subjectDropdown = row.querySelector('select[name="matapelajaran[]"]');

        Array.from(subjectDropdown.options).forEach(opt => {
            if (["SEJ", "RBT"].includes(opt.value.toUpperCase())) {
                opt.disabled = grade >= 1 && grade <= 3;
            } else {
                opt.disabled = false;
            }
        });
    }
</script>


</html>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

:root {
    --primary-blue: #0033cc;
    --primary-red: #cc0000;
    --black: #121212;
    --white: #ffffff;
    --light-color: #666666;
    --box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .1);
}

* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    outline: none;
    border: none;
    text-decoration: none;
    transition: all .2s linear;
}

html {
    font-size: 62.5%;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

body {
    background-color: #f4f6f8;
}

/* Header */
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2rem 9%;
    background: var(--white);
    box-shadow: var(--box-shadow);
}

.logo-img {
    width: 8vw;
    max-width: 50px;
    height: auto;
    vertical-align: middle;
    margin-right: 2rem;
    margin-left: -8rem;
}

.header .logo {
    font-size: 2.5rem;
    font-weight: bolder;
    color: var(--black);
}

.user-menu {
    position: relative;
    display: flex;
    align-items: center;
    cursor: pointer;
}

.user-menu .user-icon {
    font-size: 2.5rem;
    color: var(--black);
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s ease;
}

.user-menu .user-icon:hover {
    color: var(--primary-red);
}

.user-menu .dropdown-menu {
    display: none;
    position: absolute;
    top: 4rem;
    right: 0;
    background: var(--white);
    box-shadow: var(--box-shadow);
    border-radius: 0.5rem;
    z-index: 10;
    width: 15rem;
    text-align: center;
}

.user-menu .dropdown-menu a {
    display: block;
    padding: 1rem 0;
    font-size: 1.6rem;
    color: var(--black);
    border-bottom: 0.1rem solid #ddd;
}

.user-menu .dropdown-menu a:hover {
    background-color: var(--white);
    color: var(--primary-red);
}

.user-menu:hover .dropdown-menu {
    display: block;
}

.right-section {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.notification-icon {
    font-size: 2rem;
    color: var(--black);
    cursor: pointer;
    transition: color 0.3s;
}

.notification-icon:hover {
    color: var(--primary-red);
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 8rem;
    left: 0;
    width: 22rem;
    height: 100%;
    background-color: #F9FAFC;
    padding-top: 2rem;
    box-shadow: var(--box-shadow);
    z-index: 999;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    width: 100%;
    margin-bottom: 1rem;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    font-size: 1.6rem;
    color: var(--black);
    padding: 1rem 2rem;
    text-decoration: none;
    transition: background 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: var(--primary-blue);
    color: var(--white);
    border-radius: 0 1rem 1rem 0;
}

.sidebar ul li i {
    margin-right: 1rem;
}

.sidebar .submenu {
    display: none;
    padding-left: 3rem;
    background-color: #eef1f5;
    border-left: 3px solid var(--primary-blue);
}

.sidebar .submenu li {
    margin: 0.5rem 0;
}

.sidebar .submenu li a {
    font-size: 1.5rem;
    color: var(--black);
    padding: 0.8rem 1rem;
    display: block;
}

.sidebar .submenu li a:hover {
    background-color: var(--primary-red);
    color: var(--white);
    border-radius: 0.5rem;
}

.sidebar .arrow {
    margin-left: auto;
    transition: transform 0.3s ease;
}

.sidebar .has-submenu.open > a .arrow {
    transform: rotate(90deg);
}

/* Main Content */
.main-content {
    margin-left: 22rem;
    padding: 3rem;
    padding-top: 12rem;
    background-color: #f4f6f8;
    min-height: 100vh;
}

/* Teacher Form */
.teacher-form {
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    font-size: 1.5rem;
    background-color: var(--white);
    padding: 3rem;
    border-radius: 1rem;
    box-shadow: var(--box-shadow);
}

.teacher-form .form-row {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
}

.teacher-form label {
    width: 200px;
    color: var(--black);
    font-weight: 500;
}

.teacher-form input,
.teacher-form select {
    flex: 1;
    padding: 1rem;
    font-size: 1.4rem;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
}

.teacher-form button {
    padding: 1rem 2rem;
    background: var(--primary-blue);
    color: var(--white);
    border: none;
    border-radius: 0.5rem;
    font-size: 1.5rem;
    cursor: pointer;
    transition: background 0.3s;
}

.teacher-form button:hover {
    background: #3355dd;
}

.teacher-form .full-width {
    justify-content: flex-end;
    display: flex;
}

input.uppercase {
    text-transform: uppercase;
}

/* Profile Card (optional block reuse) */
.profile-card {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px;
    width: 300px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 10px auto;
    background-color: var(--white);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.profile-picture {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-red);
}

.profile-body p {
    color: var(--black);
    font-size: 16px;
}

.subject-class-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.subject-class-row .dropdown {
    padding: 0.7rem 1rem;
    font-size: 1.4rem;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
    flex: 1;
}

.subject-class-row .delete-row {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 0.7rem 1.2rem;
    border-radius: 5px;
    font-size: 1.4rem;
    cursor: pointer;
}
    </style>