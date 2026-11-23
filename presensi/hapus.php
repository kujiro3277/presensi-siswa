<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../core/Database.php';
require_once '../models/Presensi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $db = (new Database())->getConnection();
    $presensi = new Presensi($db);
    $presensi->delete($id);
}

header("Location: index.php?msg=deleted");
exit;
