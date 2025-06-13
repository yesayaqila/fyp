<?php
$currentTab = 'admin-panitia';
include 'db-skpp.php'; // 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link rel="icon" href="img/logo-skpp.png" type="image/x-icon">
    <link rel="stylesheet" href="css/test.css">
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
                    <li><a href="senarai-cuti.php">Permohonan Cuti</a></li>
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
            <form id="panitiaAssignmentForm" method="POST" action="update-panitia.php">
                <h3 class="section-title">Panitia Matapelajaran</h3>
                <div class="action-menu">
                    <button class="action-button"><i class="fas fa-ellipsis-h"></i></button>
                    <div class="dropdown">
                        <a href="#" class="edit-option"><i class="fas fa-pen"></i> Edit</a>
                        <a href="#" class="delete-option"><i class="fas fa-trash"></i> Discard</a>
                    </div>
                </div>
                <table class="panitia-table">
                    <thead>
                        <tr>
                            <th>Kod Subjek</th>
                            <th>Nama Subjek</th>
                            <th>Panitia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");
                        $panitiaList = $conn->query("SELECT * FROM panitia ORDER BY panitia_name ASC");

                        while ($subject = $subjects->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($subject['subject_code']) ?></td>
                                <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                <td>
                                    <select name="panitia_assignments[<?= $subject['subject_id'] ?>]">
                                        <option value="">-- Pilih Panitia --</option>
                                        <?php foreach ($panitiaList as $p): ?>
                                            <option value="<?= $p['panitia_id'] ?>" <?= $p['panitia_id'] == $subject['panitia_id'] ? 'selected' : '' ?>>
                                                <?= $p['panitia_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>

                <div style="text-align: right; margin-top: 2rem;">
                    <button type="button" class="btn-edit" onclick="openPanitiaModal()">Sunting</button>
                    <button type="submit" class="btn-save">Simpan Semua Perubahan</button>
                </div>
            </form>

            <!-- Place this AFTER the closing </form> tag -->

            <div class="modal-overlay" id="panitiaModal">
                <div class="modal-content">
                    <h3>Edit Panitia</h3>
                    <form id="panitiaEditForm">
                        <div style="margin-bottom: 1rem;">
                            <select id="panitiaDropdown" onchange="selectPanitia(this.value)">
                                <option value="">-- Pilih Panitia --</option>
                            </select>
                        </div>
                        <div id="selectedPanitia" style="display:none; margin-bottom:1rem;">
                            <input type="text" id="panitiaNameInput" style="width: 75%;" />
                            <button type="button" onclick="confirmDeletePanitia()">🗑️</button>
                        </div>
                        <div>
                            <input type="text" id="newPanitiaName" placeholder="Tambah panitia baru" style="width: 100%; margin-top: 1rem;">
                        </div>
                        <div style="margin-top: 2rem; text-align: right;">
                            <button type="button" class="btn-save" onclick="savePanitiaChanges()">Simpan</button>
                            <button type="button" class="btn-discard" onclick="closePanitiaModal()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>


    <script src="js/dropdown.js"></script>

    <script>
        let selectedPanitiaId = null;

        function openPanitiaModal() {
            fetch('get-panitia-list.php')
                .then(res => res.json())
                .then(data => {
                    const dropdown = document.getElementById('panitiaDropdown');
                    dropdown.innerHTML = '<option value="">-- Pilih Panitia --</option>';
                    data.forEach(p => {
                        dropdown.innerHTML += `<option value="${p.panitia_id}">${p.panitia_name}</option>`;
                    });

                    selectedPanitiaId = null;
                    document.getElementById('selectedPanitia').style.display = 'none';
                    document.getElementById('panitiaModal').style.display = 'flex';
                });
        }

        function selectPanitia(id) {
            if (!id) return document.getElementById('selectedPanitia').style.display = 'none';

            selectedPanitiaId = id;
            const selectedText = document.querySelector(`#panitiaDropdown option[value="${id}"]`).textContent;
            document.getElementById('panitiaNameInput').value = selectedText;
            document.getElementById('selectedPanitia').style.display = 'block';
        }

        function confirmDeletePanitia() {
            if (!selectedPanitiaId) return;

            if (confirm("Padam panitia ini?")) {
                fetch(`delete-panitia.php?id=${selectedPanitiaId}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log("✅ Response from update-panitia-list.php:", data); // Optional for debugging
                        showToast(data.message); // <-- This should now show the toast
                        closePanitiaModal();
                        location.reload();
                    });
            }
        }

        function savePanitiaChanges() {
            const newPanitia = document.getElementById('newPanitiaName').value.trim();
            const updatedName = document.getElementById('panitiaNameInput').value.trim();

            let updates = [];

            if (selectedPanitiaId && updatedName !== '') {
                updates.push({
                    id: selectedPanitiaId,
                    name: updatedName
                });
            }

            fetch('update-panitia-list.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        updates,
                        newPanitia
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message);
                    closePanitiaModal();
                    location.reload();
                });
        }

        function closePanitiaModal() {
            document.getElementById('panitiaModal').style.display = 'none';
        }

        function showToast(msg) {
            console.log("✅ showToast triggered with message:", msg);

            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>

</body>

</html>