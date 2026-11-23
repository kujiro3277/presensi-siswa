<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Siswa.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);

$keyword = trim($_GET['cari'] ?? '');
$daftarSiswa = $siswaModel->getAll($keyword);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Kelola Data Siswa</h4>
        <small class="text-muted">Kartu RFID & Nomor Induk Siswa Nasional (NISN)</small>
    </div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Siswa</a>
</div>

<?php if (isset($_GET['sukses'])): ?>
    <div class="alert alert-success py-2"><?= htmlspecialchars($_GET['sukses']) ?></div>
<?php endif; ?>

<!-- Pencarian Siswa -->
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="cari" class="form-control form-control-sm" 
                       placeholder="Cari berdasarkan Nama Siswa atau NISN..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm w-100">Cari</button>
                <?php if ($keyword !== ''): ?>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($daftarSiswa) > 0): ?>
                        <?php foreach ($daftarSiswa as $i => $row): ?>
                            <tr>
                                <td class="ps-3"><?= $i + 1 ?></td>
                                <td><code><?= htmlspecialchars($row['nisn']) ?></code></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['nama_kelas']) ?></span></td>
                                <td><?= $row['alamat'] ? htmlspecialchars($row['alamat']) : '<span class="text-muted fst-italic">-</span>' ?></td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                       onclick="return confirm('Hapus data siswa <?= htmlspecialchars($row['nama']) ?>?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Data siswa tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
