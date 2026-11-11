<?php
require_once "config.php";

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT peserta.*, events.nama_event
    FROM peserta
    LEFT JOIN events ON peserta.event_id = events.id
    ORDER BY peserta.id DESC
");

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Peserta - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand fw-bold">EventAttend</a>
        <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container py-4">

    <!-- Flash Message untuk Notifikasi Sukses/Gagal -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['flash_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Data Peserta</h3>
        <a href="tambah_peserta.php" class="btn btn-primary">
            + Tambah Peserta
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="tambah_event.php" class="btn btn-primary">
            + Tambah acara
        </a>
    </div>
    <a href="dashboard.php" class="btn btn-secondary btn-sm mt-3">
                Kembali ke Dashboard
    </a>


    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Pangkat</th>
                            <th>Jabatan</th>
                            <th>Acara</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($p = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= e($p['nama']) ?></td>
                                <td><?= e($p['pangkat']) ?></td>
                                <td><?= e($p['jabatan']) ?></td>
                                <td><?= e($p['nama_event']) ?></td>
                                <td>
                                    <a href="edit_peserta.php?id=<?= (int)$p['id'] ?>"
                                       class="btn btn-warning btn-sm">Edit</a>

                                    <!-- Tombol Hapus Diubah Menggunakan Form POST -->
                                    <form action="hapus_peserta.php" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>

                                    <a href="generate_qr.php?id=<?= (int)$p['id'] ?>" class="btn btn-info btn-sm text-white">QR Code</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <!-- Diperbaiki dari colspan="7" menjadi colspan="6" sesuai jumlah kolom -->
                            <td colspan="6" class="text-center text-muted">
                                Belum ada peserta.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>