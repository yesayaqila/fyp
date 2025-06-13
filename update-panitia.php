<?php
include 'db-skpp.php';

if (isset($_POST['panitia_assignments'])) {
    foreach ($_POST['panitia_assignments'] as $subject_id => $panitia_id) {
        $subject_id = intval($subject_id);
        $panitia_id = $panitia_id ? intval($panitia_id) : 'NULL';

        $conn->query("UPDATE subjects SET panitia_id = $panitia_id WHERE subject_id = $subject_id");
    }

    header("Location: admin-panitia.php?status=success");
    exit;
}
?>
