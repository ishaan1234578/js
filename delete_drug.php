<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$conn->query("DELETE FROM drugs WHERE id = $id");
header("Location: drugs.php");
exit();
?>
