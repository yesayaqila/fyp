<?php $currentTab = 'kelas'; ?>

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

        <div class="tab-wrapper">
        </div>

        <script src="js/dropdown.js"></script>

        <script>
            let isEditing = false;

            function toggleEdit() {
                const inputs = document.querySelectorAll('input[type="text"]');
                inputs.forEach(input => input.disabled = false);

                // Enable bilDarjah and bilKelas (remove readonly)
                document.getElementById('bilDarjah').removeAttribute('readonly');
                document.getElementById('bilKelas').removeAttribute('readonly');
            }




            function saveChanges() {
                const bilDarjah = document.getElementById('bilDarjah').value;
                const bilKelas = document.getElementById('bilKelas').value;
                let namaKelas = [];

                for (let i = 1; i <= bilKelas; i++) {
                    let input = document.querySelector(`#namaKelasContainer #k${i}`);
                    if (input && input.value.trim() !== '') {
                        namaKelas.push(input.value.trim());
                    }
                }

                fetch('update-kelas.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            bilDarjah,
                            bilKelas,
                            namaKelas
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload(); // Refresh to reflect changes
                    })
                    .catch(error => {
                        alert("Error: " + error);
                    });

                document.getElementById('bilDarjah').setAttribute('readonly', true);
                document.getElementById('bilKelas').setAttribute('readonly', true);

            }

            document.getElementById('bilKelas').addEventListener('input', function() {
                const bilKelas = this.value;
                const grade = 1; // or let user choose grade later

                fetch('get-kelas.php?grade=' + grade + '&bilKelas=' + bilKelas)
                    .then(response => response.json())
                    .then(data => {
                        for (let i = 1; i <= 6; i++) {
                            let input = document.getElementById('k' + i);
                            if (input) {
                                if (i <= bilKelas) {
                                    input.disabled = false;
                                    input.style.display = 'inline-block';
                                    input.value = data[i - 1] || '';
                                } else {
                                    input.style.display = 'none';
                                }
                            }
                        }
                    });
            });
        </script>

        <script>
            window.onload = function() {
                fetch('get-kelas.php')
                    .then(response => response.json())
                    .then(data => {
                        const bilKelas = data.length;
                        document.getElementById('bilKelas').value = bilKelas;
                        generateNamaKelasFields(bilKelas, data);
                    });
            };



            document.getElementById('bilKelas').addEventListener('input', function() {
                const bilKelas = parseInt(this.value);
                const grade = 1; // or change if you allow selecting grade

                fetch('get-kelas.php?grade=' + grade + '&bilKelas=' + bilKelas)
                    .then(response => response.json())
                    .then(data => {
                        generateNamaKelasFields(bilKelas, data);
                    });
            });



            function generateNamaKelasFields(bilKelas, existingValues = []) {
                const container = document.getElementById('namaKelasContainer');
                container.innerHTML = ''; // Clear previous fields

                for (let i = 1; i <= bilKelas; i++) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.id = 'k' + i;
                    input.name = 'k' + i;
                    input.className = 'class-input';
                    input.disabled = true;
                    input.value = existingValues[i - 1] || '';
                    container.appendChild(input);
                }
            }

            function toggleSubjectEdit(group) {
                const subjectList = document.querySelectorAll(`#subject-list-${group} input`);
                const deleteButtons = document.querySelectorAll(`#subject-list-${group} .delete-subject`);

                subjectList.forEach(input => input.disabled = false);
                deleteButtons.forEach(btn => btn.style.display = 'inline-block');

                document.querySelector(`#actions-${group}`).style.display = 'block';
                document.querySelector(`#darjah${group} .edit-subject-btn`).style.display = 'none';
                document.querySelector(`#darjah${group} .save-subject-btn`).style.display = 'inline-block';
            }

            function addSubjectField(group) {
                const list = document.getElementById(`subject-list-${group}`);
                const li = document.createElement('li');

                li.innerHTML = `
        <input type="text" value="">
        <button class="delete-subject" onclick="removeSubjectField(this)">🗑️</button>
    `;
                list.insertBefore(li, list.firstChild);
            }

            function removeSubjectField(btn) {
                const li = btn.parentElement;
                const subjectId = li.getAttribute('data-subject-id');

                const confirmDelete = confirm("Adakah anda pasti ingin memadam subjek ini?");

                if (confirmDelete) {
                    if (subjectId) {
                        // Call backend to delete from DB
                        fetch('delete-subject.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    subjectId: subjectId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    li.remove();
                                } else {
                                    alert("Gagal memadam subjek dari pangkalan data.");
                                }
                            })
                            .catch(err => {
                                alert("Ralat berlaku: " + err);
                            });
                    } else {
                        // Just remove unsaved/new field
                        li.remove();
                    }
                }
            }



            function saveSubjects(group) {
                const inputs = document.querySelectorAll(`#subject-list-${group} input`);
                const subjectNames = [];

                inputs.forEach(input => {
                    const name = input.value.trim();
                    if (name !== '') subjectNames.push(name);
                });

                const gradeGroup = group === '1to3' ? '1-3' : '4-6';

                fetch('update-subjects.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            gradeGroup,
                            subjectNames
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message || 'Subjects updated.');
                        location.reload();
                    })
                    .catch(err => alert('Error: ' + err));
            }
        </script>



</body>

</html>