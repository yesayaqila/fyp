<?php
header('Content-Type: application/json');
include 'db-skpp.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['subject_id'])) {
    echo json_encode(["success" => false, "message" => "ID subjek tidak diterima."]);
    exit;
}

$subjectId = intval($data['subject_id']);

// Step 1: Check if subject is in use in class_subject table
$checkStmt = $conn->prepare("SELECT COUNT(*) AS total FROM class_subject WHERE subject_id = ?");
$checkStmt->bind_param("i", $subjectId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result()->fetch_assoc();

if ($checkResult['total'] > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Subjek tidak boleh dipadam kerana sedang digunakan dalam jadual kelas."
    ]);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// Step 2: Proceed to delete
$deleteStmt = $conn->prepare("DELETE FROM subjects WHERE subject_id = ?");
$deleteStmt->bind_param("i", $subjectId);

if ($deleteStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Subjek berjaya dipadam."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal memadam subjek."]);
}

$deleteStmt->close();
$conn->close();
