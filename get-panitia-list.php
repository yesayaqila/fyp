<?php
require 'db-skpp.php';

$result = $conn->query("SELECT * FROM panitia ORDER BY panitia_name ASC");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
