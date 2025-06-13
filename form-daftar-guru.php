<?php
session_start();
include 'db-skpp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username    = trim($_POST['username']);
    $password    = trim($_POST['password']);
    $position    = ucwords(strtolower(trim($_POST['position'])));
    $status      = trim($_POST['status']);
    $panitia_id  = isset($_POST['panitia_id']) && $position === 'Ketua Panitia' ? intval($_POST['panitia_id']) : null;

    if (empty($username) || empty($password) || empty($position) || empty($status)) {
        echo "<script>alert('Sila isi semua maklumat yang diperlukan'); window.history.back();</script>";
        exit;
    }

    $exclusive_positions = [
        'Guru Besar',
        'Gpk 1 (Pentadbiran)',
        'Gpk Hal Ehwal Murid',
        'Gpk Kokurikulum'
    ];

    try {
        // 🔐 Check for exclusive positions
        if (in_array($position, $exclusive_positions)) {
            $checkExclusive = $conn->prepare("SELECT COUNT(*) FROM teachers WHERE LOWER(position) = LOWER(?)");
            $checkExclusive->bind_param("s", $position);
            $checkExclusive->execute();
            $checkExclusive->bind_result($count);
            $checkExclusive->fetch();
            $checkExclusive->close();

            if ($count > 0) {
                header("Location: daftar-guru.php?error=position_exists");
                exit;
            }
        }

        // 🔐 Ketua Panitia check
        if ($position === 'Ketua Panitia' && $panitia_id) {
            $check = $conn->prepare("SELECT head_teacher_id FROM panitia WHERE panitia_id = ?");
            $check->bind_param("i", $panitia_id);
            $check->execute();
            $check->bind_result($existingHead);
            $check->fetch();
            $check->close();

            if (!empty($existingHead)) {
                header("Location: daftar-guru.php?error=panitia_exists");
                exit;
            }
        }

        // ✅ Hash and insert teacher
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO teachers (username, password, position, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $hashed_password, $position, $status);
        $stmt->execute();

        $newTeacherId = $stmt->insert_id;
        $stmt->close();

        // ✅ Update Ketua Panitia
        if ($position === 'Ketua Panitia' && $panitia_id) {
            $updateStmt = $conn->prepare("UPDATE panitia SET head_teacher_id = ? WHERE panitia_id = ?");
            $updateStmt->bind_param("ii", $newTeacherId, $panitia_id);
            $updateStmt->execute();
            $updateStmt->close();
        }

        $conn->close();
        header("Location: daftar-guru.php?success=1");
        exit;

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            header("Location: daftar-guru.php?error=duplicate");
        } else {
            header("Location: daftar-guru.php?error=unknown");
        }
        exit;
    }

} else {
    header("Location: welcome-admin.php");
    exit;
}
