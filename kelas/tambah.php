<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

$error = '';
$oldNama = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldNama = trim($_POST['nama_kelas'] ?? '');

    if ($oldNama === '') {
        $error = 'Nama kelas wajib diisi.';
    } elseif ($kelasModel->isDuplicate($oldNama)) {
        $error = 'Nama kelas sudah ada, tidak boleh kembar.';
    } else {
        $kelasModel->setNamaKelas($oldNama);
        $kelasModel->create();
        header("Location: index.php?sukses=" . urlencode("Kelas berhasil ditambahkan."));
        exit;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Kelas Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control <?= $error ? 'is-invalid' : '' ?>" 
                               placeholder="Contoh: X PPLG 3" value="<?= htmlspecialchars($oldNama) ?>" autofocus>
                        <?php if ($error): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
