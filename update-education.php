<?php
session_start();
include 'db-skpp.php';

$teacher_id = $_SESSION['teacher_id'];

// Handle file upload
$fileName = '';
$targetFile = '';
if (!empty($_FILES['uploadDokumen']['name'])) {
    $targetDir = "uploads/edu/";
    $fileName = basename($_FILES["uploadDokumen"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;
    move_uploaded_file($_FILES["uploadDokumen"]["tmp_name"], $targetFile);
}

// Get form data
$laluan = $_POST['laluanPendidikan'];
$laluanLain = $_POST['laluanLain'] ?? '';
$bidang = $_POST['bidangPengajian'] ?? '';
$bidangLain = $_POST['bidangLain'] ?? '';

$finalLaluan = $laluan === 'lain' ? $laluanLain : $laluan;
$finalBidang = ($laluan === 'lain' || strtolower($bidang) === 'lain-lain') ? $bidangLain : $bidang;

// Save to database
$stmt = $conn->prepare("REPLACE INTO education_details (teacher_id, education_path, field, document_path) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $teacher_id, $finalLaluan, $finalBidang, $targetFile);

if ($stmt->execute()) {
    header("Location: setting.php?status=success");
    exit;
} else {
    header("Location: setting.php?status=error");
    exit;
}
?>
