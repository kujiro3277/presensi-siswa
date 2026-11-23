<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../core/Database.php';
require_once '../models/Siswa.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $db = (new Database())->getConnection();
    $siswaModel = new Siswa($db);
    $siswaModel->delete($id);
}

header("Location: index.php?sukses=" . urlencode("Data siswa berhasil dihapus."));
exit;
