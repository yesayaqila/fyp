<?php
include 'db-skpp.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validate and sanitize input
$subject_id = isset($data['subject_id']) ? intval($data['subject_id']) : null;
$raw_panitia_id = $data['panitia_id'] ?? null;
$panitia_id = ($raw_panitia_id === '' || is_null($raw_panitia_id)) ? null : intval($raw_panitia_id);

if (!$subject_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Subjek tidak sah.'
    ]);
    exit;
}

if (is_null($panitia_id)) {
    // Remove panitia assignment (set to NULL)
    $stmt = $conn->prepare("UPDATE subjects SET panitia_id = NULL WHERE subject_id = ?");
    $stmt->bind_param("i", $subject_id);
} else {
    // Assign or update panitia_id
    $stmt = $conn->prepare("UPDATE subjects SET panitia_id = ? WHERE subject_id = ?");
    $stmt->bind_param("ii", $panitia_id, $subject_id);
}

$success = $stmt->execute();

echo json_encode([
    'success' => $success,
    'message' => $success
        ? 'Subjek berjaya dikemaskini.'
        : 'Ralat semasa mengemaskini subjek.'
]);
