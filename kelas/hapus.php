<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../core/Database.php';
require_once '../models/Kelas.php';

$id = (int)($_GET['id'] ?? 0);
$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

try {
    $kelasModel->delete($id);
    header("Location: index.php?sukses=" . urlencode("Kelas berhasil dihapus."));
} catch (Exception $e) {
    // Menampilkan pesan ramah dari model, bukan error mentah database
    header("Location: index.php?error=" . urlencode($e->getMessage()));
}
exit;
