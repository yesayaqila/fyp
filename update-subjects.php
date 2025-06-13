<?php
// ✅ Always open PHP tag first
file_put_contents("debug.log", print_r($_POST, true), FILE_APPEND);

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db-skpp.php';

$response = ['success' => false, 'message' => ''];

try {
    // ✅ Validate required fields
    if (!isset($_POST['subject_id'], $_POST['subject_code'], $_POST['subject_name'], $_POST['offered_grades'])) {
        throw new Exception('Maklumat tidak lengkap.');
    }

    $id = intval($_POST['subject_id']);
    $code = trim($_POST['subject_code']);
    $name = trim($_POST['subject_name']);
    $grades = trim($_POST['offered_grades']);

    if ($id <= 0 || empty($code) || empty($name) || empty($grades)) {
        throw new Exception('Sila lengkapkan semua maklumat dengan betul.');
    }

    $stmt = $conn->prepare("UPDATE subjects SET subject_code = ?, subject_name = ?, offered_grades = ? WHERE subject_id = ?");
    if (!$stmt) {
        throw new Exception('SQL Error: ' . $conn->error);
    }

    $stmt->bind_param("sssi", $code, $name, $grades, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Subjek berjaya dikemaskini.';
    } else {
        $response['message'] = 'Tiada perubahan dibuat atau ID tidak sah.';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
