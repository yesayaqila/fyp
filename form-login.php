<?php
session_start();
include 'db-skpp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['id'];
    $enteredPassword = $_POST['password'];

    $query = "SELECT * FROM teachers WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($enteredPassword, $user['password'])) {
            $_SESSION['teacher_id'] = $user['teacher_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['position'] = $user['position'];
            $_SESSION['last_activity'] = time();

            if ($user['is_admin'] == 1) {
                header("Location: welcome-admin.php");
            } elseif (strtolower($user['position']) == 'ketua panitia') {
                header("Location: welcome-ct.php");
            } else {
                header("Location: welcome-guru.php");
            }
            exit;
        } else {
            // Wrong password
            echo "<script>alert('Kata laluan salah'); window.location.href='index.php';</script>";
        }
    } else {
        // Username not found
        echo "<script>alert('Nama pengguna tidak wujud'); window.location.href='index.php';</script>";
    }
}
?>
