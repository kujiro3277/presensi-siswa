<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
require_once '../core/Database.php';
require_once '../models/Presensi.php';

$db = (new Database())->getConnection();
$presensiModel = new Presensi($db);

$pesanSukses = '';
$pesanError  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $_POST['nisn'] ?? '';
    try {
        $siswa = $presensiModel->tapKartu($nisn);
        $pesanSukses = "Presensi berhasil dicatat: <strong>" . htmlspecialchars($siswa['nama']) . "</strong> (" . htmlspecialchars($siswa['nama_kelas']) . ")";
    } catch (Exception $e) {
        $pesanError = $e->getMessage();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Simulasi Tap Kartu RFID (Gerbang Sekolah)</h5>
            </div>
            <div class="card-body p-4 text-center">
                <p class="text-muted">Tempelkan kartu RFID siswa atau ketik 10 digit NISN lalu tekan <strong>Enter</strong>.</p>

                <?php if ($pesanSukses): ?>
                    <div class="alert alert-success"><?= $pesanSukses ?></div>
                <?php endif; ?>

                <?php if ($pesanError): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($pesanError) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="nisn" maxlength="10" class="form-control form-control-lg text-center fw-bold" 
                               placeholder="Scan / Ketik 10 Digit NISN" required autofocus autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100">Tap Kartu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
