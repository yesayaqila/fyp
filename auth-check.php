<?php
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.php");
    exit;
}

// Timeout after 15 minutes
$timeout = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=true");
    exit;
}
$_SESSION['last_activity'] = time();
