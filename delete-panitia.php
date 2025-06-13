<?php
header('Content-Type: application/json');
include 'db-skpp.php';

$response = ['success' => false, 'message' => ''];

$panitia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($panitia_id <= 0) {
    $response['message'] = 'ID panitia tidak sah.';
    echo json_encode($response);
    exit;
}

// Optional: check if any subject is using this panitia
$check = $conn->prepare("SELECT COUNT(*) FROM subjects WHERE panitia_id = ?");
$check->bind_param("i", $panitia_id);
$check->execute();
$check->bind_result($count);
$check->fetch();
$check->close();

if ($count > 0) {
    $response['message'] = 'Panitia tidak boleh dipadam kerana masih mempunyai subjek.';
    echo json_encode($response);
    exit;
}

// Proceed to delete
$stmt = $conn->prepare("DELETE FROM panitia WHERE panitia_id = ?");
$stmt->bind_param("i", $panitia_id);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Panitia berjaya dipadam.';
} else {
    $response['message'] = 'Ralat semasa memadam panitia.';
}

echo json_encode($response);
