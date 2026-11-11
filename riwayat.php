<?php
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Sesuaikan kueri SELECT dengan mengambil kolom pangkat dan jabatan dari tabel peserta
$query = mysqli_query($conn, "
    SELECT absensi.waktu_hadir, peserta.nama, peserta.pangkat, peserta.jabatan, events.nama_event 
    FROM absensi 
    JOIN peserta ON absensi.peserta_id = peserta.id 
    LEFT JOIN events ON peserta.event_id = events.id 
    ORDER BY absensi.waktu_hadir DESC
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
    <title>Riwayat Check-in - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand fw-bold">EventAttend</a>
        <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Riwayat Check-in Peserta</h3>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Waktu Hadir</th>
                            <th>Nama Peserta</th>
                            <th>Pangkat</th>
                            <th>Jabatan</th>
                            <th>Acara</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= e($row['waktu_hadir']) ?></td>
                                <td class="fw-bold"><?= e($row['nama']) ?></td>
                                <td><?= e($row['pangkat'] ?: '-') ?></td>
                                <td><?= e($row['jabatan'] ?: '-') ?></td>
                                <td><?= e($row['nama_event']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data riwayat check-in.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>