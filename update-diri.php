<?php
session_start();
include 'auth-check.php';
include 'db-skpp.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_SESSION['teacher_id'];
    $name = strtoupper(trim($_POST['name']));
    $email = $_POST['email'];
    $phone = $_POST['phone'];

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
