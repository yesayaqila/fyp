<?php
session_start();
include 'auth-check.php';
include 'db-skpp.php';

$teacher_id = $_SESSION['teacher_id'];

// Get current teacher data
$query = "SELECT name, email, phone, position FROM teachers WHERE teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get all subjects for dropdowns
$subjects = [];
$subjectResult = $conn->query("SELECT subject_code, subject_name FROM subjects ORDER BY subject_name ASC");
while ($row = $subjectResult->fetch_assoc()) {
    $subjects[] = $row;
}

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strtoupper(trim($_POST['name']));
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $query = "UPDATE teachers SET name = ?, email = ?, phone = ? WHERE teacher_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $name, $email, $phone, $teacher_id);

    if ($stmt->execute()) {
        header("Location: setting.php?status=success");
        exit;
    } else {
        header("Location: setting.php?status=error");
        exit;
    }
}

// Get saved education details
$edu = [];
$eduQuery = $conn->prepare("SELECT education_path, field, document_path FROM education_details WHERE teacher_id = ?");
$eduQuery->bind_param("i", $teacher_id);
$eduQuery->execute();
$eduResult = $eduQuery->get_result();
if ($eduResult->num_rows > 0) {
    $edu = $eduResult->fetch_assoc();
}
$selectedEducationPath = isset($edu['education_path']) ? $edu['education_path'] : '';


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketetapan</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/setting.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!---navigation bar--->
    <header class="header">
        <a href="#" class="logo"> <img src="img/logo-skpp.png" alt="logo" class="logo-img">SISTEM JADUAL WAKTU</a>

        <nav class="navbar">
            <a href="welcome-guru.php">Dashboard</a>
            <a href="kehadiran-guru.php">Kehadiran</a>
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

    <div id="myToast" class="toast">Maklumat berjaya dikemaskini</div>

    <div class="main-content">
        <div class="settings-wrapper">
            <!-- Sidebar (left) -->
            <aside class="settings-sidebar">
                <ul>
                    <li class="active" data-target="diri">Maklumat Diri</li>
                    <li data-target="pendidikan">Maklumat Pendidikan</li>
                    <li data-target="pengajaran">Subjek Pilihan</li>
                    <li data-target="keselamatan">Keselamatan</li>
                </ul>
            </aside>

            <!-- Right Content Area -->
            <div class="settings-content">
                <!-- Maklumat Diri -->
                <div class="settings-section section-diri">
                    <div class="profile-flex">
                        <!-- Profile Image -->
                        <div class="profile-picture-wrapper">
                            <img id="profilePreview" src="img/default-profile.png" class="profile-img" alt="Profile">
                            <label for="uploadImg" class="upload-overlay">
                                <i class="fas fa-plus"></i> <!-- Font Awesome icon -->
                            </label>
                            <input type="file" id="uploadImg" accept="image/*" style="display: none;">
                        </div>


                        <!-- Username -->
                        <div class="profile-username">
                            <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
                        </div>

                    </div>

                    <!-- Info Fields -->
                    <form id="formMaklumatDiri" method="POST" action="update-diri.php">
                        <div class="field-group-horizontal">
                            <label>Nama Penuh</label>
                            <input type="text" name="name" value="<?php echo strtoupper(htmlspecialchars($user['name'])); ?>" class="editable-field" style="text-transform: uppercase;">
                        </div>
                        <div class="field-group-horizontal">
                            <label>Emel</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="editable-field">
                        </div>
                        <div class="field-group-horizontal">
                            <label>No. Telefon</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="editable-field" pattern="\d{3}-\d{7,8}" title="Contoh: 012-3456789" required>
                        </div>
                        <div class="field-group-horizontal">
                            <label>Jawatan</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['position']); ?>" readonly style="background-color: #f0f0f0; cursor: not-allowed;">
                        </div>

                        <div class="button-right">
                            <button type="button" class="btn-secondary" id="btnBatal" style="display: none;">
                                <i class="fas fa-times"></i>&nbsp; Batal
                            </button>
                            <button type="submit" class="btn-primary" id="btnSimpan" disabled>
                                <i class="fas fa-save"></i>&nbsp; Kemaskini
                            </button>

                        </div>
                    </form>

                </div>

                <!-- Maklumat Pendidikan -->
                <form method="POST" action="update-education.php" enctype="multipart/form-data">
                    <div class="settings-section section-pendidikan" style="display: none;">
                        <br><br><br>

                        <?php
                        $currentLaluan = $edu['education_path'] ?? '';
                        $isLainLaluan = !in_array($currentLaluan, ['pismp', 'bed', 'dpli', 'luar', 'tvet']);
                        $displayLaluanLain = $isLainLaluan ? 'block' : 'none';
                        $valueLaluanLain = $isLainLaluan ? htmlspecialchars($currentLaluan) : '';
                        ?>

                        <label for="laluanPendidikan">Laluan Pendidikan (Program Pengajian)</label>
                        <select id="laluanPendidikan" name="laluanPendidikan" onchange="tunjukBidang()">
                            <option value="" disabled <?= $currentLaluan === '' ? 'selected' : '' ?>>--- Pilih Laluan Pendidikan ---</option>
                            <option value="pismp" <?= $currentLaluan === 'pismp' ? 'selected' : '' ?>>PISMP</option>
                            <option value="bed" <?= $currentLaluan === 'bed' ? 'selected' : '' ?>>Ijazah Sarjana Muda Pendidikan (B.Ed)</option>
                            <option value="dpli" <?= $currentLaluan === 'dpli' ? 'selected' : '' ?>>Ijazah Bukan Pendidikan + DPLI</option>
                            <option value="luar" <?= $currentLaluan === 'luar' ? 'selected' : '' ?>>Ijazah Luar Negara</option>
                            <option value="tvet" <?= $currentLaluan === 'tvet' ? 'selected' : '' ?>>Ijazah TVET</option>
                            <option value="lain" <?= $isLainLaluan ? 'selected' : '' ?>>Lain-lain (Nyatakan)</option>
                        </select>

                        <div class="settings-section" id="laluanLainInput" style="display: <?= $displayLaluanLain ?>; margin-top: 10px;">
                            <label for="laluanLain">Nyatakan Laluan Pendidikan</label>
                            <input type="text" id="laluanLain" name="laluanLain" value="<?= $valueLaluanLain ?>">
                        </div>

                        <?php
                        $currentField = $edu['field'] ?? '';
                        ?>

                        <div class="settings-section" id="bidangContainer" style="margin-top: 10px;">
                            <label for="bidangPengajian">Bidang / Kepakaran</label>
                            <select id="bidangPengajian" name="bidangPengajian" onchange="semakLain()">
                                <!-- will be populated by JS, JS will also auto-select saved one -->
                            </select>
                        </div>

                        <div class="settings-section" id="bidangLainInput" style="display: none; margin-top: 10px;">
                            <label for="bidangLain">Nyatakan Bidang / Kepakaran</label>
                            <input type="text" id="bidangLain" name="bidangLain" value="<?= htmlspecialchars($currentField) ?>">
                        </div>

                        <div class="attach-file" style="margin-top: 20px;">
                            <label for="uploadDokumen"> Dokumen Sokongan (.pdf, .jpg, .png)</label>
                            <input type="file" id="uploadDokumen" name="uploadDokumen" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <?php if (!empty($edu['document_path'])): ?>
                            <div style="margin-top:10px;">
                                <p><strong>Dokumen Sedia Ada:</strong> <a href="<?= $edu['document_path'] ?>" target="_blank">Lihat Dokumen</a></p>
                            </div>
                        <?php endif; ?>

                        <div class="button-right" style="margin-top: 20px;">
                            <button class="btn-secondary" type="button" id="btnBatalPendidikan" style="display: none;">
                                <i class="fas fa-times"></i>&nbsp; Batal
                            </button>
                            <button class="btn-primary" type="submit" name="save_education" id="btnSimpanPendidikan" disabled>
                                <i class="fas fa-save"></i>&nbsp; Simpan
                            </button>
                        </div>
                    </div>
                </form>



                <!-- Maklumat Pengajaran -->
                <?php
                $selected = ['subject1' => '', 'subject2' => '', 'subject3' => ''];

                $prefQuery = $conn->prepare("SELECT subject1, subject2, subject3 FROM teacher_subjects WHERE teacher_id = ?");
                $prefQuery->bind_param("i", $teacher_id);
                $prefQuery->execute();
                $prefResult = $prefQuery->get_result();

                if ($prefResult->num_rows > 0) {
                    $selected = $prefResult->fetch_assoc();
                }
                ?>

                <form method="POST" action="update-preference.php">
                    <div class="settings-section section-pengajaran" style="display: none;">
                        <br><br><br>

                        <label>Pilihan Mengajar Matapelajaran:</label><br><br>

                        <select name="subject1" required>
                            <option value="" disabled>--- Pilihan Pertama ---</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['subject_code'] ?>" <?= $selected['subject1'] === $subject['subject_code'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject['subject_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>

                        <select name="subject2" required>
                            <option value="" disabled>--- Pilihan Kedua ---</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['subject_code'] ?>" <?= $selected['subject2'] === $subject['subject_code'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject['subject_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>

                        <select name="subject3" required>
                            <option value="" disabled>--- Pilihan Ketiga ---</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['subject_code'] ?>" <?= $selected['subject3'] === $subject['subject_code'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject['subject_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div class="button-right" style="margin-top: 20px;">
                            <button class="btn-secondary" type="button" id="btnBatalPengajaran" style="display: none;">
                                <i class="fas fa-times"></i>&nbsp; Batal
                            </button>
                            <button class="btn-primary" type="submit" name="save_preference" id="btnSimpanPengajaran" disabled>
                                <i class="fas fa-save"></i>&nbsp; Simpan
                            </button>
                        </div>

                    </div>
                </form>


                <!-- Keselamatan -->
                <div class="settings-section section-keselamatan" style="display: none;">

                    <br><br><br><br>
                    <div class="field-group-horizontal-p">
                        <label>Kata Laluan Lama</label>
                        <input type="password">
                    </div>
                    <div class="field-group-horizontal-p">
                        <label>Kata Laluan Baharu</label>
                        <input type="password">
                    </div>
                    <div class="field-group-horizontal-p">
                        <label>Sahkan Kata Laluan</label>
                        <input type="password">
                    </div>

                    <div class="button-right">
                        <button class="btn-primary"><i class="fas fa-save"></i>&nbsp; Kemaskini</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="js/dropdown.js"></script>
    <script src="js/bidang.js"></script>

    <script>
        function previewProfile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

    <script>
        const tabs = document.querySelectorAll('.settings-sidebar li');
        const sections = document.querySelectorAll('.settings-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const target = tab.getAttribute('data-target');
                sections.forEach(sec => sec.style.display = 'none');
                document.querySelector(`.section-${target}`).style.display = 'block';
            });
        });

        // Image preview
        document.getElementById('uploadImg').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profilePreview').src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>

    <script>
        const fields = document.querySelectorAll('.editable-field');
        const btnSimpan = document.getElementById('btnSimpan');
        const btnBatal = document.getElementById('btnBatal');
        const originalValues = {};

        // Store original values
        fields.forEach(field => {
            originalValues[field.name] = field.value;

            field.addEventListener('input', () => {
                btnSimpan.disabled = false;
                btnBatal.style.display = 'inline-block';
            });
        });

        // Discard changes
        btnBatal.addEventListener('click', () => {
            fields.forEach(field => {
                field.value = originalValues[field.name];
            });
            btnSimpan.disabled = true;
            btnBatal.style.display = 'none';
        });
    </script>

    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById("myToast");
            toast.textContent = message;
            toast.className = "toast show " + type; // Add class like 'toast show success'

            setTimeout(() => {
                toast.className = "toast"; // Reset classes after 3 seconds
            }, 3000);
        }

        // Trigger toast if status in URL
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            window.onload = function() {
                showToast("Maklumat berjaya dikemaskini", "success");
            };
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            window.onload = function() {
                showToast("Ralat semasa mengemaskini", "error");
            };
        <?php endif; ?>
    </script>


    <script>
        document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 3) {
                e.target.value = value.slice(0, 3) + '-' + value.slice(3, 11);
            } else {
                e.target.value = value;
            }
        });
    </script>

    <script>
        const selects = document.querySelectorAll('select[name^="subject"]');
        const btnSimpanMT = document.getElementById('btnSimpanPengajaran');
        const btnBatalMT = document.getElementById('btnBatalPengajaran');

        // Store original values
        const originalSubjects = {
            subject1: document.querySelector('select[name="subject1"]').value,
            subject2: document.querySelector('select[name="subject2"]').value,
            subject3: document.querySelector('select[name="subject3"]').value
        };

        selects.forEach(select => {
            select.addEventListener('change', () => {
                // Enable Simpan and show Batal if any change
                const hasChanged = (
                    document.querySelector('select[name="subject1"]').value !== originalSubjects.subject1 ||
                    document.querySelector('select[name="subject2"]').value !== originalSubjects.subject2 ||
                    document.querySelector('select[name="subject3"]').value !== originalSubjects.subject3
                );

                btnSimpanMT.disabled = !hasChanged;
                btnBatalMT.style.display = hasChanged ? 'inline-block' : 'none';

                // Prevent duplicate selections
                const selectedValues = [...selects].map(s => s.value);
                selects.forEach(s => {
                    [...s.options].forEach(opt => {
                        opt.disabled = selectedValues.includes(opt.value) && s.value !== opt.value && opt.value !== "";
                    });
                });
            });
        });

        // Batal button click: Reset to original
        btnBatalMT.addEventListener('click', () => {
            document.querySelector('select[name="subject1"]').value = originalSubjects.subject1;
            document.querySelector('select[name="subject2"]').value = originalSubjects.subject2;
            document.querySelector('select[name="subject3"]').value = originalSubjects.subject3;

            btnSimpanMT.disabled = true;
            btnBatalMT.style.display = 'none';

            // Re-enable all options
            selects.forEach(s => {
                [...s.options].forEach(opt => opt.disabled = false);
            });
        });
    </script>

    <script>
        const pendidikanFields = [
            document.getElementById('laluanPendidikan'),
            document.getElementById('laluanLain'),
            document.getElementById('bidangPengajian'),
            document.getElementById('bidangLain'),
            document.getElementById('uploadDokumen')
        ];

        const btnSimpanPendidikan = document.getElementById('btnSimpanPendidikan');
        const btnBatalPendidikan = document.getElementById('btnBatalPendidikan');

        const originalEduValues = {};

        // Store original values on load
        window.addEventListener('load', () => {
            pendidikanFields.forEach(field => {
                if (field) {
                    originalEduValues[field.name] = field.value;
                }
            });
        });

        // Detect change
        pendidikanFields.forEach(field => {
            if (field) {
                field.addEventListener('input', () => {
                    const hasChanged = pendidikanFields.some(f =>
                        f && originalEduValues[f.name] !== f.value
                    );

                    btnSimpanPendidikan.disabled = !hasChanged;
                    btnBatalPendidikan.style.display = hasChanged ? 'inline-block' : 'none';
                });
            }
        });

        // Reset on Batal click
        btnBatalPendidikan.addEventListener('click', () => {
            pendidikanFields.forEach(field => {
                if (field && originalEduValues[field.name] !== undefined) {
                    field.value = originalEduValues[field.name];
                }
            });

            btnSimpanPendidikan.disabled = true;
            btnBatalPendidikan.style.display = 'none';
        });
    </script>



</body>

</html>