<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi RFID - SMK TI Airlangga Samarinda</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
