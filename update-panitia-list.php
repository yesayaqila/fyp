<?php
include 'db-skpp.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['updates']) || !is_array($data['updates'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak sah']);
    exit;
}

foreach ($data['updates'] as $item) {
    $id = intval($item['id']);
    $name = trim($item['name']);

    if (!$id || !$name) continue;

    $stmt = $conn->prepare("UPDATE panitia SET panitia_name = ? WHERE panitia_id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
}

echo json_encode(['success' => true, 'message' => 'Panitia dikemaskini']);
