<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);
$daftarKelas = $kelasModel->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Kelola Data Kelas</h4>
        <small class="text-muted">SMK TI Airlangga Samarinda</small>
    </div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Kelas</a>
</div>

<?php if (isset($_GET['sukses'])): ?>
    <div class="alert alert-success py-2"><?= htmlspecialchars($_GET['sukses']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger py-2"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0 align-middle">
            <thead class="table-primary">
                <tr>
                    <th class="ps-3" width="10%">No</th>
                    <th>Nama Kelas</th>
                    <th class="text-center" width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($daftarKelas) > 0): ?>
                    <?php foreach ($daftarKelas as $i => $row): ?>
                        <tr>
                            <td class="ps-3"><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus kelas <?= htmlspecialchars($row['nama_kelas']) ?>?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data kelas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
