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
    <link rel="stylesheet" href="css/admin-panitia.css">
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

                <div class="section-header">
                    <h3 class="section-title">Panitia Matapelajaran</h3>
                    <button type="button" class="btn-add" onclick="openAddPanitiaModal()">+ Tambah Panitia</button>
                </div>


                <?php
                $unassignedSubjects = $conn->query("SELECT * FROM subjects WHERE panitia_id IS NULL ORDER BY subject_name ASC");
                ?>

                <div class="panitia-card"
                    data-panitia-id=""
                    ondrop="handleDrop(event)"
                    ondragover="allowDrop(event)">

                    <div class="card-header">
                        <h4 style="color: #cc0000;">Tanpa Panitia</h4>
                    </div>

                    <ul class="subject-list">
                        <?php while ($subject = $unassignedSubjects->fetch_assoc()): ?>
                            <li class="subject-item"
                                draggable="true"
                                ondragstart="handleDragStart(event)"
                                data-subject-id="<?= $subject['subject_id'] ?>">
                                <?= htmlspecialchars($subject['subject_code']) ?> - <?= htmlspecialchars($subject['subject_name']) ?>
                                <a href="detail-panitia.php?subject_id=<?= $subject['subject_id'] ?>" class="btn-detail" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>



                <div class="panitia-card-container">
                    <?php
                    $panitiaList = $conn->query("SELECT * FROM panitia ORDER BY panitia_name ASC");

                    while ($panitia = $panitiaList->fetch_assoc()):
                        $panitia_id = $panitia['panitia_id'];
                        $subjects = $conn->query("SELECT * FROM subjects WHERE panitia_id = $panitia_id ORDER BY subject_name ASC");
                    ?>
                        <div class="panitia-card"
                            data-panitia-id="<?= $panitia_id ?>"
                            ondrop="handleDrop(event)"
                            ondragover="allowDrop(event)">

                            <div class="card-header">
                                <div class="panitia-name-wrapper" data-panitia-id="<?= $panitia_id ?>">
                                    <span class="panitia-name-text"><?= htmlspecialchars($panitia['panitia_name']) ?></span>
                                    <input class="panitia-name-input" type="text" value="<?= htmlspecialchars($panitia['panitia_name']) ?>" style="display:none;">
                                    <button type="button" class="btn-save" style="display:none;" onclick="savePanitiaName(this)">💾</button>
                                    <button type="button" class="btn-discard" style="display:none;" onclick="cancelPanitiaEdit(this)">❌</button>
                                </div>


                                <div class="dropdown-action">
                                    <button type="button" class="round-btn" onclick="toggleCardActionMenu(event, <?= $panitia_id ?>)">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="action-menu" id="card-action-<?= $panitia_id ?>">
                                        <a href="#" onclick="enablePanitiaEdit(<?= $panitia_id ?>)">Edit Panitia</a>
                                        <a href="#" onclick="confirmDeletePanitia(<?= $panitia_id ?>)">Padam Panitia</a>
                                    </div>
                                </div>
                            </div>

                            <ul class="subject-list">
                                <?php while ($subject = $subjects->fetch_assoc()): ?>
                                    <li class="subject-item"
                                        draggable="true"
                                        ondragstart="handleDragStart(event)"
                                        data-subject-id="<?= $subject['subject_id'] ?>">
                                        <?= htmlspecialchars($subject['subject_code']) ?> - <?= htmlspecialchars($subject['subject_name']) ?>
                                        <a href="detail-panitia.php?subject_id=<?= $subject['subject_id'] ?>" class="btn-detail" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    <?php endwhile; ?>
                </div>



            </form>

            <!-- Edit Panitia Modal
            <div class="modal-overlay" id="editPanitiaModal">
                <div class="modal-content">
                    <h3>Edit Panitia</h3>
                    <form id="panitiaEditForm">
                        <div id="selectedPanitia" style="display:none; margin-bottom:1rem;">
                            <input type="text" id="panitiaNameInput" style="width: 75%;" />
                            <button type="button" onclick="confirmDeletePanitia()">🗑️</button>
                        </div>
                        <div style="margin-top: 2rem; text-align: right;">
                            <button type="button" class="btn-save" onclick="savePanitiaChanges()">Kemaskini</button>
                            <button type="button" class="btn-discard" onclick="closeEditPanitiaModal()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>-->

            <!-- Tambah Panitia Modal -->
            <div class="modal-overlay" id="addPanitiaModal">
                <div class="modal-content">
                    <h3>Tambah Panitia Baru</h3>
                    <form id="panitiaAddForm">
                        <div style="margin-bottom: 1rem;">
                            <input type="text" id="newPanitiaInput" placeholder="Nama Panitia" style="width: 100%;">
                        </div>
                        <div style="margin-top: 2rem; text-align: right;">
                            <button type="button" class="btn-save" onclick="addNewPanitia()">Simpan</button>
                            <button type="button" class="btn-discard" onclick="closeAddPanitiaModal()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <script src="js/dropdown.js"></script>

    <script>
        function enablePanitiaEdit(id) {
            const wrapper = document.querySelector(`.panitia-name-wrapper[data-panitia-id="${id}"]`);
            if (!wrapper) return;

            wrapper.querySelector('.panitia-name-text').style.display = 'none';
            wrapper.querySelector('.panitia-name-input').style.display = 'inline-block';
            wrapper.querySelector('.btn-save').style.display = 'inline-block';
            wrapper.querySelector('.btn-discard').style.display = 'inline-block';

            // Optional: Close the dropdown menu
            const actionMenu = document.getElementById('card-action-' + id);
            if (actionMenu) actionMenu.classList.remove('show');
        }

        function savePanitiaName(btn) {
            const wrapper = btn.closest('.panitia-name-wrapper');
            const id = wrapper.dataset.panitiaId;
            const input = wrapper.querySelector('.panitia-name-input');
            const newName = input.value.trim();

            if (!newName) {
                showToast('Nama panitia tidak boleh kosong.', true);
                return;
            }

            fetch('update-panitia-list.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        updates: [{
                            id: id,
                            name: newName
                        }]
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        wrapper.querySelector('.panitia-name-text').textContent = newName;
                        showToast('Nama panitia berjaya dikemaskini.');
                    } else {
                        showToast('Ralat semasa mengemaskini panitia.', true);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Ralat sambungan ke pelayan.', true);
                })
                .finally(() => {
                    cancelPanitiaEdit(btn); // hide input, show label
                });
        }


        function cancelPanitiaEdit(btn) {
            const wrapper = btn.closest('.panitia-name-wrapper');
            wrapper.querySelector('.panitia-name-input').style.display = 'none';
            wrapper.querySelector('.btn-save').style.display = 'none';
            wrapper.querySelector('.btn-discard').style.display = 'none';
            wrapper.querySelector('.panitia-name-text').style.display = 'inline-block';
        }
    </script>

    <script>
        function allowDrop(event) {
            event.preventDefault();
        }

        function handleDragStart(event) {
            event.dataTransfer.setData("subjectId", event.target.dataset.subjectId);
        }

        function handleDrop(event) {
            event.preventDefault();
            const subjectId = event.dataTransfer.getData("subjectId");
            const panitiaId = event.currentTarget.dataset.panitiaId;

            fetch("assign-subject-to-panitia.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        subject_id: subjectId,
                        panitia_id: panitiaId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 500);
                });
        }

        document.addEventListener('dragover', function(e) {
            const buffer = 150; // px from top or bottom
            const scrollSpeed = 15;

            if (e.clientY < buffer) {
                window.scrollBy(0, -scrollSpeed);
            } else if (e.clientY > window.innerHeight - buffer) {
                window.scrollBy(0, scrollSpeed);
            }
        });


        function toggleCardActionMenu(event, id) {
            event.stopPropagation();
            const menu = document.getElementById('card-action-' + id);

            document.querySelectorAll('.action-menu').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });

            menu.classList.toggle('show');

            document.addEventListener('click', function closeOutside(e) {
                if (!e.target.closest(`#card-action-${id}`) && !e.target.closest('.round-btn')) {
                    menu.classList.remove('show');
                    document.removeEventListener('click', closeOutside);
                }
            });
        }
    </script>


    <script>
        let selectedPanitiaId = null;

        // --- Toggle Action Menu ---
        function toggleCardActionMenu(event, id) {
            event.stopPropagation();

            const menu = document.getElementById('card-action-' + id);

            // Hide all other menus first
            document.querySelectorAll('.action-menu').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });

            // Toggle current menu
            menu.classList.toggle('show');

            // Add global listener to close dropdown if clicked outside
            document.addEventListener('click', function closeOutside(e) {
                if (!e.target.closest(`#card-action-${id}`) && !e.target.closest('.round-btn')) {
                    menu.classList.remove('show');
                    document.removeEventListener('click', closeOutside);
                }
            });
        }


        function closeMenuOutside(e) {
            if (!e.target.closest('.dropdown-action')) {
                document.querySelector('.action-menu').classList.remove('show');
                document.removeEventListener('click', closeMenuOutside);
            }
        }

        // --------- Edit Modal ---------
        function openEditPanitiaModal(id, name) {
            selectedPanitiaId = id;
            document.getElementById('panitiaNameInput').value = name;
            document.getElementById('selectedPanitia').style.display = 'block';
            document.getElementById('editPanitiaModal').style.display = 'flex';
        }


        function selectPanitia(id) {
            if (!id) return document.getElementById('selectedPanitia').style.display = 'none';

            selectedPanitiaId = id;
            const selectedText = document.querySelector(`#panitiaDropdown option[value="${id}"]`).textContent;
            document.getElementById('panitiaNameInput').value = selectedText;
            document.getElementById('selectedPanitia').style.display = 'block';
        }

        function savePanitiaChanges() {
            const updatedName = document.getElementById('panitiaNameInput').value.trim();

            if (!selectedPanitiaId || updatedName === '') {
                showToast("Sila pilih dan masukkan nama panitia yang sah.", true);
                return;
            }

            fetch('update-panitia-list.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        updates: [{
                            id: selectedPanitiaId,
                            name: updatedName
                        }]
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message);
                    closeEditPanitiaModal();
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(err => {
                    console.error(err);
                    showToast("Ralat semasa mengemaskini panitia.", true);
                });
        }

        function confirmDeletePanitia(id) {
            if (!confirm("Adakah anda pasti mahu memadam panitia ini?")) return;

            fetch(`delete-panitia.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    showToast(data.message, !data.success);

                    if (data.success) {
                        // Remove the card from DOM
                        const card = document.querySelector(`.panitia-card[data-panitia-id="${id}"]`);
                        if (card) card.remove();
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast("Ralat sambungan ke pelayan.", true);
                });
        }


        function closeEditPanitiaModal() {
            document.getElementById('editPanitiaModal').style.display = 'none';
        }

        // --------- Add Modal ---------
        function openAddPanitiaModal() {
            document.getElementById('newPanitiaInput').value = '';
            document.getElementById('addPanitiaModal').style.display = 'flex';
        }

        function addNewPanitia() {
            const newPanitia = document.getElementById('newPanitiaInput').value.trim();
            if (!newPanitia) return;

            fetch('insert-panitia.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: newPanitia
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message);

                    if (data.success && data.new_id) {
                        const container = document.querySelector('.panitia-card-container');
                        const newCard = document.createElement('div');
                        newCard.className = 'panitia-card';
                        newCard.setAttribute('data-panitia-id', data.new_id);
                        newCard.setAttribute('ondrop', 'handleDrop(event)');
                        newCard.setAttribute('ondragover', 'allowDrop(event)');

                        newCard.innerHTML = `
                <div class="card-header">
                    <div class="panitia-name-wrapper" data-panitia-id="${data.new_id}">
                        <span class="panitia-name-text">${data.name}</span>
                        <input class="panitia-name-input" type="text" value="${data.name}" style="display:none;">
                        <button type="button" class="btn-save" style="display:none;" onclick="savePanitiaName(this)">💾</button>
                        <button type="button" class="btn-discard" style="display:none;" onclick="cancelPanitiaEdit(this)">❌</button>
                    </div>
                    <div class="dropdown-action">
                        <button type="button" class="round-btn" onclick="toggleCardActionMenu(event, ${data.new_id})">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="action-menu" id="card-action-${data.new_id}">
                            <a href="#" onclick="enablePanitiaEdit(${data.new_id})">Edit Panitia</a>
                            <a href="#" onclick="confirmDeletePanitia(${data.new_id})">Padam Panitia</a>
                        </div>
                    </div>
                </div>
                <ul class="subject-list"></ul>
            `;

                        container.appendChild(newCard);
                        closeAddPanitiaModal();
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    showToast("Ralat semasa menambah panitia.", true);
                });
        }


        function closeAddPanitiaModal() {
            document.getElementById('addPanitiaModal').style.display = 'none';
        }

        // --------- Toast ---------
        function showToast(msg, isError = false) {
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.textContent = msg;
            toast.style.backgroundColor = isError ? '#e74c3c' : '#2ecc71';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }
    </script>


</body>

</html>