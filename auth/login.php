<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: ../presensi/index.php");
    exit;
}

require_once '../core/Database.php';
require_once '../models/User.php';

$error = '';
$oldUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldUsername = trim($_POST['username'] ?? '');
    $password    = $_POST['password'] ?? '';

    $db   = (new Database())->getConnection();
    $user = new User($db);

    if ($user->login($oldUsername, $password)) {
        $_SESSION['admin_id']       = $user->getId();
        $_SESSION['admin_username'] = $user->getUsername();
        header("Location: ../presensi/index.php");
        exit;
    } else {
        // Pesan umum tanpa menyebutkan bagian mana yang salah
        $error = "Username atau password tidak sesuai.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - SMK TI Airlangga Samarinda</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="text-center fw-bold mb-1">SMK TI Airlangga Samarinda</h5>
                        <p class="text-center text-muted small mb-4">Sistem Presensi Kartu RFID</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($oldUsername) ?>" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
