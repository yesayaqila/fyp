<?php
session_start();
include 'auth-check.php';
include 'db-skpp.php';

$teacher_id = $_SESSION['teacher_id'];
$subject1 = $_POST['subject1'];
$subject2 = $_POST['subject2'];
$subject3 = $_POST['subject3'];

// Check if preference already exists
$check = $conn->prepare("SELECT * FROM teacher_subjects WHERE teacher_id = ?");
$check->bind_param("i", $teacher_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Update
    $stmt = $conn->prepare("UPDATE teacher_subjects SET subject1=?, subject2=?, subject3=? WHERE teacher_id=?");
    $stmt->bind_param("sssi", $subject1, $subject2, $subject3, $teacher_id);
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO teacher_subjects (teacher_id, subject1, subject2, subject3) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $teacher_id, $subject1, $subject2, $subject3);
}

if ($stmt->execute()) {
    header("Location: setting.php?status=success");
    exit;
} else {
    header("Location: setting.php?status=error");
    exit;
}
?>
