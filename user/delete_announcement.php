<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete_sql = "DELETE FROM announcements WHERE announcement_id = $id";
    mysqli_query($conn, $delete_sql);
}

header("Location: announcement.php");
exit();
?>
