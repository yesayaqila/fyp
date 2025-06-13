<?php
include 'db-skpp.php';

// Get all class names in grade 1, ordered by ID (assuming all grades share structure)
$result = $conn->query("
    SELECT class_name 
    FROM classes 
    WHERE grade = 1 
    ORDER BY class_id ASC
");

$kelas = [];
while ($row = $result->fetch_assoc()) {
    $kelas[] = $row['class_name'];
}

echo json_encode($kelas);
?>
