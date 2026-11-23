<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Siswa.php';
require_once '../models/Kelas.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);
$kelasModel = new Kelas($db);

$id = (int)($_GET['id'] ?? 0);
$siswa = $siswaModel->findById($id);

if (!$siswa) {
    echo "<div class='alert alert-danger'>Data siswa tidak ditemukan.</div>";
    require_once '../layouts/footer.php';
    exit;
}

$daftarKelas = $kelasModel->getAll();
$errors = [];
$old = [
    'nisn'     => $siswa['nisn'],
    'nama'     => $siswa['nama'],
    'alamat'   => $siswa['alamat'] ?? '',
    'kelas_id' => $siswa['kelas_id']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nisn']     = trim($_POST['nisn'] ?? '');
    $old['nama']     = trim($_POST['nama'] ?? '');
    $old['alamat']   = trim($_POST['alamat'] ?? '');
    $old['kelas_id'] = (int)($_POST['kelas_id'] ?? 0);

    $siswaModel->setId($id);
    $siswaModel->setNisn($old['nisn']);
    $siswaModel->setNama($old['nama']);
    $siswaModel->setAlamat($old['alamat']);
    $siswaModel->setKelasId($old['kelas_id']);

    $errors = $siswaModel->validate($id);

    if (empty($errors)) {
        $siswaModel->update();
        header("Location: index.php?sukses=" . urlencode("Data siswa berhasil diperbarui."));
        exit;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Data Siswa</h5>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label class="form-label">NISN (10 Digit Angka)</label>
                        <input type="text" name="nisn" maxlength="10" class="form-control <?= isset($errors['nisn']) ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars($old['nisn']) ?>">
                        <?php if (isset($errors['nisn'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['nisn']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars($old['nama']) ?>">
                        <?php if (isset($errors['nama'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select <?= isset($errors['kelas_id']) ? 'is-invalid' : '' ?>">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($daftarKelas as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= (int)$old['kelas_id'] === (int)$k['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['kelas_id'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['kelas_id']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat <small class="text-muted">(Opsional)</small></label>
                        <textarea name="alamat" rows="2" class="form-control"><?= htmlspecialchars($old['alamat']) ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
