<?php
include 'db-skpp.php';

if (isset($_GET['query'])) {
    $searchTerm = $conn->real_escape_string($_GET['query']);

    $sql = "SELECT teacher_name FROM teachers 
            WHERE teacher_name LIKE '%$searchTerm%' 
            ORDER BY teacher_name ASC LIMIT 10";
    $result = $conn->query($sql);

    $names = [];
    while ($row = $result->fetch_assoc()) {
        $names[] = $row['teacher_name'];
    }

    echo json_encode($names);
}
?>
