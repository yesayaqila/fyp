<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db-skpp.php';

$response = [
    'success' => false,
    'message' => '',
    'new_id' => null,
    'name' => null
];

try {
    // Read and decode JSON request body
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name']) || trim($data['name']) === '') {
        throw new Exception('Nama panitia tidak sah.');
    }

    $name = trim($data['name']);

    // Optional: check for duplicate panitia name
    $checkStmt = $conn->prepare("SELECT panitia_id FROM panitia WHERE panitia_name = ?");
    if (!$checkStmt) {
        throw new Exception('SQL error (check duplicate): ' . $conn->error);
    }
    $checkStmt->bind_param("s", $name);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows > 0) {
        throw new Exception('Panitia ini sudah wujud.');
    }

    // Insert new panitia
    $stmt = $conn->prepare("INSERT INTO panitia (panitia_name) VALUES (?)");
    if (!$stmt) {
        throw new Exception('SQL error (insert): ' . $conn->error);
    }

    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Panitia berjaya ditambah.';
        $response['new_id'] = $conn->insert_id;
        $response['name'] = $name;
    } else {
        throw new Exception('Gagal menambah panitia.');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
