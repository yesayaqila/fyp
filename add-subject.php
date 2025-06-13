<?php
header('Content-Type: application/json');
include 'db-skpp.php';

$data = json_decode(file_get_contents("php://input"), true);

$code = trim($data['subject_code'] ?? '');
$name = trim($data['subject_name'] ?? '');
$grades = trim($data['offered_grades'] ?? '');

if ($code === '' || $name === '' || $grades === '') {
    echo json_encode(["success" => false, "message" => "Maklumat subjek tidak lengkap."]);
    exit;
}

// Check for duplicates
$check = $conn->prepare("SELECT subject_id FROM subjects WHERE subject_code = ? OR subject_name = ?");
$check->bind_param("ss", $code, $name);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Subjek sudah wujud."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO subjects (subject_code, subject_name, offered_grades) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $code, $name, $grades);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambah subjek."]);
}
