<?php
include 'db-skpp.php';

$data = json_decode(file_get_contents("php://input"), true);
$bilDarjah = intval($data['bilDarjah']);
$bilKelas = intval($data['bilKelas']);
$namaKelas = $data['namaKelas'];

$response = [];

for ($i = 0; $i < count($namaKelas); $i++) {
    $className = $namaKelas[$i];

    for ($grade = 1; $grade <= $bilDarjah; $grade++) {
        // Check if this grade has a class at this position
        $check = $conn->prepare("
            SELECT class_id, class_name 
            FROM classes 
            WHERE grade = ? 
            ORDER BY class_id ASC 
            LIMIT ?,1
        ");
        $check->bind_param("ii", $grade, $i);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['class_name'] !== $className) {
                $update = $conn->prepare("UPDATE classes SET class_name = ? WHERE class_id = ?");
                $update->bind_param("si", $className, $row['class_id']);
                $update->execute();
            }
        } else {
            $insert = $conn->prepare("INSERT INTO classes (grade, class_name) VALUES (?, ?)");
            $insert->bind_param("is", $grade, $className);
            $insert->execute();
        }
    }
}

$response['message'] = "Kelas berjaya dikemaskini untuk semua darjah.";
echo json_encode($response);
?>
