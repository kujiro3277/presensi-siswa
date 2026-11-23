<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

$id = (int)($_GET['id'] ?? 0);
$kelas = $kelasModel->findById($id);

if (!$kelas) {
    echo "<div class='alert alert-danger'>Data kelas tidak ditemukan.</div>";
    require_once '../layouts/footer.php';
    exit;
}

$error = '';
$oldNama = $kelas['nama_kelas'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldNama = trim($_POST['nama_kelas'] ?? '');

    if ($oldNama === '') {
        $error = 'Nama kelas wajib diisi.';
    } elseif ($kelasModel->isDuplicate($oldNama, $id)) {
        $error = 'Nama kelas sudah digunakan kelas lain.';
    } else {
        $kelasModel->setId($id);
        $kelasModel->setNamaKelas($oldNama);
        $kelasModel->update();
        header("Location: index.php?sukses=" . urlencode("Data kelas berhasil diperbarui."));
        exit;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Data Kelas</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control <?= $error ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars($oldNama) ?>">
                        <?php if ($error): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
