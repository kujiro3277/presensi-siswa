<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../presensi/index.php">RFID SMK TI Airlangga</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../presensi/tap.php">Simulasi Tap Kartu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../presensi/index.php">Rekap Presensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../siswa/index.php">Kelola Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../kelas/index.php">Kelola Kelas</a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">Admin: <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm" onclick="return confirm('Keluar dari sistem?')">Logout</a>
            </div>
        </div>
    </div>
</nav>
<main class="container flex-grow-1">
