<?php
require_once "config.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Mengambil statistik peserta
$query_total = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM peserta"
);

$query_hadir = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM absensi"
);

$total = mysqli_fetch_assoc($query_total)['total'];
$hadir = mysqli_fetch_assoc($query_hadir)['total'];
$belum = $total - $hadir;

$nama = htmlspecialchars($_SESSION['nama']);
$role = htmlspecialchars($_SESSION['role']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">

    <title>Dashboard - EventAttend</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand fw-bold">EventAttend</span>
        <a href="logout.php" class="btn btn-light btn-sm">
            Logout
        </a>
    </div>
</nav>

<div class="container py-4">

    <h3 class="fw-bold">Dashboard</h3>

    <p class="text-muted">
        Selamat datang, <?= $nama ?>.
        <span class="badge bg-secondary"><?= $role ?></span>
    </p>

    <div class="row g-3 mt-3">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Peserta</p>
                    <h2 class="fw-bold"><?= $total ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Sudah HADIR / ABSEN</p>
                    <h2 class="fw-bold text-success"><?= $hadir ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Belum HADIR / ABSEN</p>
                    <h2 class="fw-bold text-danger"><?= $belum ?></h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Menu Aplikasi</h5>

            <div class="d-grid gap-2">

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="peserta.php" class="btn btn-outline-primary">
                        Manajemen Peserta
                    </a>
                <?php endif; ?>

                <a href="scan.php" class="btn btn-primary">
                    Scan QR Peserta
                </a>

                <a href="riwayat.php" class="btn btn-outline-primary">
                    Riwayat Hadir
                </a>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="laporan.php" class="btn btn-outline-primary">
                        Laporan Kehadiran
                    </a>
                <?php endif; ?>

            </div>

        </div>
    </div>

</div>

</body>
</html>