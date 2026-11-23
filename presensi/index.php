<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Presensi.php';
require_once '../models/Kelas.php';

$db = (new Database())->getConnection();
$presensiModel = new Presensi($db);
$kelasModel    = new Kelas($db);

// Default tanggal adalah hari ini jika parameter tanggal belum diset
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$kelasId = isset($_GET['kelas_id']) && $_GET['kelas_id'] !== '' ? (int)$_GET['kelas_id'] : null;
$keyword = trim($_GET['cari'] ?? '');

$daftarKelas    = $kelasModel->getAll();
$daftarPresensi = $presensiModel->getFiltered($tanggal, $kelasId, $keyword);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Rekap Presensi Siswa</h4>
        <small class="text-muted">Pantauan Kehadiran Gerbang RFID - Ibu Mira (Waka Kesiswaan)</small>
    </div>
    <a href="tap.php" class="btn btn-success">+ Simulasi Tap Kartu</a>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-warning py-2">Log presensi berhasil dihapus.</div>
<?php endif; ?>

<!-- Filter & Pencarian -->
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="<?= htmlspecialchars($tanggal) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Filter Kelas</label>
                <select name="kelas_id" class="form-select form-select-sm">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($daftarKelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $kelasId === (int)$k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Cari Nama / NISN</label>
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Ketik nama atau NISN..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Hari Ini</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Rekap Presensi -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Waktu Tap Masuk</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($daftarPresensi) > 0): ?>
                        <?php foreach ($daftarPresensi as $i => $row): ?>
                            <tr>
                                <td class="ps-3"><?= $i + 1 ?></td>
                                <td><code><?= htmlspecialchars($row['nisn']) ?></code></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['nama_kelas']) ?></span></td>
                                <td><?= htmlspecialchars($row['tanggal_waktu']) ?></td>
                                <td class="text-center">
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Hapus log presensi ini? (Log presensi tidak dapat diubah, hanya dapat dihapus jika salah catat)')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data presensi untuk filter ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
