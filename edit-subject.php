<?php
$conn = new mysqli("localhost", "root", "", "pintas_puding");

$subjectId = intval($_POST['subject_id']);
$newName = trim($_POST['new_name']);
$applyAll = $_POST['apply_all'] === 'true';

if ($newName === '') {
    echo "Subject name cannot be empty.";
    exit;
}

if ($applyAll) {
    $stmt = $conn->prepare("UPDATE subjects SET subject_name = ? WHERE subject_id = ?");
    $stmt->bind_param("si", $newName, $subjectId);
    $stmt->execute();
    echo "success";
} else {
    // Rename only for 1 class? Not possible unless you have a custom subject per class.
    echo "fail: cannot rename only for 1 class unless using custom subject instance.";
}
