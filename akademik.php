<?php $currentTab = 'kelas'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/akademik.css">
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
            <button class="tab-btn <?= ($currentTab === 'kelas') ? 'active' : '' ?>" onclick="location.href='akademik.php'">Kelas / Subjek</button>
            <button class="tab-btn <?= ($currentTab === 'admin-panitia') ? 'active' : '' ?>" onclick="location.href='admin-panitia.php'">Panitia</button>
        </div>

        <div class="tab-wrapper">
            <div class="tab-content" id="kelasSubjek">
                <div class="kelas-subjek-top">
                    <label>&nbsp;&nbsp;Bil Darjah:
                        <input type="number" id="bilDarjah" value="6" min="1" max="6" readonly />
                    </label>
                    <label>Bil Kelas:
                        <input type="number" id="bilKelas" value="4" min="1" max="6" readonly />
                    </label>
                    <label>Nama Kelas:</label>
                    <div id="namaKelasContainer" class="nama-kelas-container"></div>


                    <button class="btn-edit" onclick="toggleEdit()"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn-update" onclick="saveChanges()"><i class="fas fa-save"></i> Simpan</button>

                </div>

                <div class="kelas-subjek-bottom" id="kelasOutput">
                    <?php
                    include 'db-skpp.php'; // Your DB connection file

                    function getSubjectsByGradeRange($conn, $minGrade, $maxGrade)
                    {
                        $stmt = $conn->prepare("
                                SELECT DISTINCT s.subject_id, s.subject_name, s.subject_code, s.color_code, s.offered_grades
                                FROM class_subject cs
                                JOIN classes c ON cs.class_id = c.class_id
                                JOIN subjects s ON cs.subject_id = s.subject_id
                                WHERE c.grade BETWEEN ? AND ?
                                ORDER BY s.subject_name ASC
                                ");

                        if (!$stmt) {
                            return []; // Return empty array on failure
                        }

                        $stmt->bind_param("ii", $minGrade, $maxGrade);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $subjects = [];
                        while ($row = $result->fetch_assoc()) {
                            $subjects[] = $row;
                        }

                        return $subjects;
                    }



                    $darjah1to3Subjects = getSubjectsByGradeRange($conn, 1, 3);
                    $darjah4to6Subjects = getSubjectsByGradeRange($conn, 4, 6);
                    ?>

                    <!-- Color Indicator Grid -->
                    <div class="subject-color-legend">
                        <h3 style="font-size: 2rem; margin-bottom: 2rem;">Subjek dan Warna</h3>

                        <div class="legend-grid">
                            <?php
                            $subjectQuery = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");
                            $subjectCount = 0;
                            while ($row = $subjectQuery->fetch_assoc()):
                                $subjectCount++;
                            ?>
                                <div class="legend-box">
                                    <div class="color-swatch" style="background-color: <?= $row['color_code'] ?>"></div>
                                    <div class="legend-info">
                                        <strong><?= htmlspecialchars($row['subject_code']) ?></strong>
                                        <span><?= htmlspecialchars($row['subject_name']) ?></span>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="legend-box edit-box" style="justify-content: center;">
                                <button class="edit-subjects-btn" onclick="openSubjectEditModal()">
                                    <i class="fas fa-edit"></i> Kemaskini Subjek</button>
                            </div>
                        </div>
                    </div>


                    <div class="darjah-row">
                        <!-- Darjah 1–3 -->
                        <div class="class-card" id="darjah1to3">
                            <div class="class-name"><b>Darjah 1-3</div>
                            <div class="subject-color-inline">
                                <?php foreach ($darjah1to3Subjects as $subject): ?>
                                    <?php
                                    $offered = $subject['offered_grades'];
                                    $isDisabled = ($offered === '4-6');
                                    $dotClass = $isDisabled ? 'color-dot disabled-dot' : 'color-dot';
                                    $tooltip = $subject['subject_code'] . ' - ' . $subject['subject_name'];
                                    if ($isDisabled) {
                                        $tooltip .= ' (Tidak ditawarkan untuk Darjah 1–3)';
                                    }
                                    ?>
                                    <span class="<?= $dotClass ?>"
                                        style="background-color: <?= htmlspecialchars($subject['color_code']) ?>"
                                        title="<?= htmlspecialchars($tooltip) ?>"></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Darjah 4–6 -->
                        <div class="class-card" id="darjah4to6">
                            <div class="class-name">Darjah 4-6</div>
                            <div class="subject-color-inline">
                                <?php foreach ($darjah4to6Subjects as $subject): ?>
                                    <?php
                                    $offered = $subject['offered_grades'];
                                    $isDisabled = ($offered === '1-3');
                                    $dotClass = $isDisabled ? 'color-dot disabled-dot' : 'color-dot';
                                    $tooltip = $subject['subject_code'] . ' - ' . $subject['subject_name'];
                                    if ($isDisabled) {
                                        $tooltip .= ' (Tidak ditawarkan untuk Darjah 4–6)';
                                    }
                                    ?>
                                    <span class="<?= $dotClass ?>"
                                        style="background-color: <?= htmlspecialchars($subject['color_code']) ?>"
                                        title="<?= htmlspecialchars($tooltip) ?>"></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Overlay -->
        <!-- Modal Overlay -->
        <div id="subjectModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <button class="close-modal" onclick="closeSubjectEditModal()">&times;</button> <!-- Close X -->

                <h3>Kemaskini Subjek</h3>

                <!-- Mode Switch -->
                <div class="subject-mode-switch">
                    <button type="button" onclick="switchToAddMode()">+ Tambah Subjek</button>
                    <button type="button" onclick="switchToEditMode()">✏️ Edit Subjek</button>
                </div>

                <form id="subjectEditForm" method="post">
                    <!-- Add Subjek Section -->
                    <div id="newSubjectFields" style="display: none; margin-top: 1rem;">
                        <label for="newCode">Kod Subjek:</label>
                        <input type="text" id="newCode" name="new_code">

                        <label for="newName">Nama Subjek:</label>
                        <input type="text" id="newName" name="new_name">

                        <label for="new_grades">Tahap Ditawarkan:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="new_grades" value="1-3" required> 1</label>
                            <label><input type="radio" name="new_grades" value="4-6"> 2</label>
                            <label><input type="radio" name="new_grades" value="ALL"> Semua</label>
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn-save" onclick="saveNewSubject()"><i class="fas fa-save"></i>&nbsp; Simpan Subjek Baru</button>
                        </div>
                    </div>

                    <!-- Edit Subjek Section -->
                    <div id="editSubjectFields">
                        <label for="subjectSelect">Pilih Subjek:</label>
                        <select id="subjectSelect" name="subject_id" required>
                            <option value="">-- Pilih Subjek --</option>
                            <?php
                            $subjectDropdownQuery = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");
                            while ($subj = $subjectDropdownQuery->fetch_assoc()):
                            ?>
                                <option
                                    value="<?= $subj['subject_id'] ?>"
                                    data-code="<?= htmlspecialchars($subj['subject_code']) ?>"
                                    data-name="<?= htmlspecialchars($subj['subject_name']) ?>"
                                    data-grades="<?= $subj['offered_grades'] ?>">
                                    <?= htmlspecialchars($subj['subject_name']) ?> (<?= htmlspecialchars($subj['subject_code']) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <label for="codeField">Kod Subjek:</label>
                        <input type="text" id="codeField" name="subject_code" required>

                        <label for="nameField">Nama Subjek:</label>
                        <input type="text" id="nameField" name="subject_name" required>

                        <div class="form-row">
                            <label for="offeredGrades">Tahap Ditawarkan:</label>
                            <div class="radio-group" id="offeredGrades">
                                <label><input type="radio" name="offered_grades" value="1-3" required> 1</label>
                                <label><input type="radio" name="offered_grades" value="4-6"> 2</label>
                                <label><input type="radio" name="offered_grades" value="ALL"> Semua</label>
                            </div>
                        </div>

                        <div class="modal-actions">
                            <button type="button" id="deleteSubjectBtn" class="btn-delete" onclick="deleteSubject()" style="display: none;">
                                <i class="fa fa-trash"></i>&nbsp; Buang</button>
                            <button type="submit" class="btn-save"><i class="fas fa-save"></i>&nbsp; Kemaskini</button>
                        </div>
                    </div>
                </form>
            </div>
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
            function openSubjectEditModal() {
                document.getElementById('subjectModal').style.display = 'flex';
            }

            function closeSubjectEditModal() {
                document.getElementById('subjectModal').style.display = 'none';
                document.getElementById('subjectEditForm').reset();
                document.getElementById('deleteSubjectBtn').style.display = 'none'; // Hide the delete button
            }


            document.getElementById('subjectSelect').addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const deleteBtn = document.getElementById('deleteSubjectBtn');

                if (selected.value) {
                    // Populate fields
                    document.getElementById('codeField').value = selected.dataset.code;
                    document.getElementById('nameField').value = selected.dataset.name;

                    // Set radio buttons
                    const offered = selected.dataset.grades;
                    const radios = document.querySelectorAll('input[name="offered_grades"]');
                    radios.forEach(r => r.checked = (r.value === offered));

                    // Show the delete button
                    deleteBtn.style.display = 'inline-block';
                } else {
                    // Clear fields if no subject is selected
                    document.getElementById('codeField').value = '';
                    document.getElementById('nameField').value = '';
                    const radios = document.querySelectorAll('input[name="offered_grades"]');
                    radios.forEach(r => r.checked = false);

                    // Hide the delete button
                    deleteBtn.style.display = 'none';
                }
            });


            document.getElementById('subjectEditForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('update-subjects.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message || "Subjek berjaya dikemaskini.");
                            closeSubjectEditModal();
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showToast(data.message || "Kemaskini gagal.", true);
                        }
                    })
                    .catch(err => {
                        console.error("Error:", err);
                        showToast("Ralat: " + err.message, true);
                    });
            });


            function deleteSubject() {
                const selectedSubjectId = document.getElementById('subjectSelect').value;

                if (!selectedSubjectId) {
                    alert("Sila pilih subjek untuk dipadam.");
                    return;
                }

                if (!confirm("Adakah anda pasti ingin memadam subjek ini?")) {
                    return;
                }

                fetch('delete-subject.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            subject_id: selectedSubjectId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message || "Subjek berjaya dipadam.");
                            closeSubjectEditModal();
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast(data.message || "Gagal memadam subjek.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast("Ralat: " + err.message);
                    });
            }


            // ✅ Toast popup function
            function showToast(message, isError = false) {
                const toast = document.getElementById("toast");
                toast.innerText = message;

                // Set background color based on success or error
                toast.style.backgroundColor = isError ? "#e74c3c" : "#4CAF50";

                toast.classList.add("show");

                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.remove("show");
                }, 3000);
            }
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

                const isEditing = !document.getElementById('bilDarjah').hasAttribute('readonly');

                for (let i = 1; i <= bilKelas; i++) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.id = 'k' + i;
                    input.name = 'k' + i;
                    input.className = 'class-input';
                    input.value = existingValues[i - 1] || '';

                    // Set editable only if currently in edit mode
                    input.disabled = !isEditing;

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

            function toggleAddMode() {
                const fields = document.getElementById('newSubjectFields');
                fields.style.display = fields.style.display === 'none' ? 'block' : 'none';
            }

            function saveNewSubject() {
                const code = document.getElementById('newCode').value.trim();
                const name = document.getElementById('newName').value.trim();
                const grades = document.querySelector('input[name="new_grades"]:checked')?.value;

                if (!code || !name || !grades) {
                    alert("Sila lengkapkan semua maklumat subjek.");
                    return;
                }

                fetch('add-subject.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            subject_code: code,
                            subject_name: name,
                            offered_grades: grades
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast("Subjek baru berjaya ditambah.");
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showToast(data.message || "Gagal menambah subjek.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast("Ralat: " + err.message);
                    });
            }

            function switchToAddMode() {
                document.getElementById('newSubjectFields').style.display = 'block';
                document.getElementById('editSubjectFields').style.display = 'none';
            }

            function switchToEditMode() {
                document.getElementById('newSubjectFields').style.display = 'none';
                document.getElementById('editSubjectFields').style.display = 'block';
            }
        </script>

        <div id="toast" class="toast"></div>

</body>

</html>