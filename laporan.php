<?php
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil rekap laporan per acara dengan menyesuaikan relasi absensi melalui peserta
$query_laporan = mysqli_query($conn, "
    SELECT e.id, e.nama_event, e.tanggal, e.lokasi,
           (SELECT COUNT(*) FROM peserta p WHERE p.event_id = e.id) AS total_peserta,
           (SELECT COUNT(*) FROM absensi a JOIN peserta p ON a.peserta_id = p.id WHERE p.event_id = e.id) AS total_hadir
    FROM events e
    ORDER BY e.tanggal DESC
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
    <link href="style.css" rel="stylesheet">
    <title>Laporan Kehadiran - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary no-print">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand fw-bold">EventAttend</a>
        <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h3 class="fw-bold">Laporan Rekapitulasi Kehadiran</h3>
        <div>
            <button onclick="window.print()" class="btn btn-success btn-sm me-2">Cetak Laporan</button>
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <!-- Tampilan khusus header cetak -->
    <div class="text-center mb-4 d-none d-print-block">
        <h2>Laporan Rekapitulasi Kehadiran EventAttend</h2>
        <p class="text-muted">Dicetak pada: <?= date('d-m-Y H:i:s') ?></p>
        <hr>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Acara</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th class="text-center">Total Peserta</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Belum Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($query_laporan) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query_laporan)): 
                            $belum_hadir = $row['total_peserta'] - $row['total_hadir'];
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-bold"><?= e($row['nama_event']) ?></td>
                                <td><?= e($row['tanggal']) ?></td>
                                <td><?= e($row['lokasi'] ?: '-') ?></td>
                                <td class="text-center"><?= $row['total_peserta'] ?></td>
                                <td class="text-center text-success fw-bold"><?= $row['total_hadir'] ?></td>
                                <td class="text-center text-danger fw-bold"><?= $belum_hadir ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data acara yang terdaftar.</td>
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