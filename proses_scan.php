<?php
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Akses Ditolak - EventAttend</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='assets/css/style.css' rel='stylesheet'>
    </head>
    <body class='bg-light d-flex align-items-center justify-content-center min-vh-100'>
        <div class='card shadow border-0 text-center p-4' style='max-width: 400px;'>
            <div class='card-body'>
                <h4 class='text-danger fw-bold mb-3'>Akses Ditolak</h4>
                <p class='text-muted'>Silakan login terlebih dahulu untuk menggunakan sistem pemindai.</p>
                <a href='login.php' class='btn btn-primary w-100'>Login Sekarang</a>
            </div>
        </div>
    </body>
    </html>";
    exit;
}

$peserta = null;
$status_type = ""; // 'success', 'warning', 'danger'
$pesan_utama = "";
$pesan_detail = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = trim($_POST['qr_token'] ?? "");
    $user_id = $_SESSION['user_id'];

    if ($token === "") {
        $status_type = "warning";
        $pesan_utama = "Token Tidak Valid";
        $pesan_detail = "QR Code atau token yang dimasukkan kosong/tidak terbaca dengan benar.";
    } else {
        // Ambil data peserta berdasarkan qr_token
        $stmt = mysqli_prepare($conn, "SELECT id, nama, instansi FROM peserta WHERE qr_token = ?");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $peserta = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$peserta) {
            $status_type = "danger";
            $pesan_utama = "Peserta Tidak Ditemukan";
            $pesan_detail = "Token <code>" . htmlspecialchars($token) . "</code> tidak terdaftar di dalam database.";
        } else {
            $peserta_id = $peserta['id'];

            // Cek apakah sudah pernah check-in
            $cek_absen = mysqli_prepare($conn, "SELECT id, waktu_hadir FROM absensi WHERE peserta_id = ?");
            mysqli_stmt_bind_param($cek_absen, "i", $peserta_id);
            mysqli_stmt_execute($cek_absen);
            $sudah_absen = mysqli_fetch_assoc(mysqli_stmt_get_result($cek_absen));

            if ($sudah_absen) {
                $status_type = "warning";
                $pesan_utama = "Sudah Melakukan Check-in!";
                $pesan_detail = "Peserta atas nama <strong>" . htmlspecialchars($peserta['nama']) . "</strong> tercatat sudah hadir pada:<br><span class='badge bg-secondary mt-2 fs-6'>" . $sudah_absen['waktu_hadir'] . "</span>";
            } else {
                // Simpan absensi baru
                $insert = mysqli_prepare($conn, "INSERT INTO absensi (peserta_id, user_id, waktu_hadir) VALUES (?, ?, NOW())");
                mysqli_stmt_bind_param($insert, "ii", $peserta_id, $user_id);
                
                if (mysqli_stmt_execute($insert)) {
                    $status_type = "success";
                    $pesan_utama = "Check-in Berhasil!";
                    $pesan_detail = "Kehadiran untuk <strong>" . htmlspecialchars($peserta['nama']) . "</strong> (" . htmlspecialchars($peserta['instansi'] ?: 'Umum/Mandiri') . ") berhasil dicatat ke sistem.";
                } else {
                    $status_type = "danger";
                    $pesan_utama = "Gagal Menyimpan";
                    $pesan_detail = "Terjadi kendala saat merekam data ke database. Silakan coba beberapa saat lagi.";
                }
            }
        }
    }
} else {
    header("Location: scan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Scan - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .result-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px auto;
        }
        .icon-success { background-color: #d1e7dd; color: #198754; }
        .icon-warning { background-color: #fff3cd; color: #ffc107; }
        .icon-danger  { background-color: #f8d7da; color: #dc3545; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand fw-bold">EventAttend</a>
        <a href="scan.php" class="btn btn-light btn-sm">Kembali ke Scanner</a>
    </div>
</nav>

<div class="container py-5" style="max-width: 550px;">
    <div class="card shadow-sm border-0 text-center p-4">
        <div class="card-body">
            
            <?php if ($status_type === 'success'): ?>
                <div class="result-icon icon-success">✓</div>
                <h3 class="fw-bold text-success mb-2"><?= $pesan_utama ?></h3>
            <?php elseif ($status_type === 'warning'): ?>
                <div class="result-icon icon-warning">!</div>
                <h3 class="fw-bold text-warning mb-2"><?= $pesan_utama ?></h3>
            <?php else: ?>
                <div class="result-icon icon-danger">✕</div>
                <h3 class="fw-bold text-danger mb-2"><?= $pesan_utama ?></h3>
            <?php endif; ?>

            <p class="text-muted mt-3 mb-3 fs-5">
                <?= $pesan_detail ?>
            </p>

            <!-- Informasi Hitung Mundur -->
            <div class="alert alert-info py-2 mb-4" role="alert">
                <small>Kembali ke halaman scan otomatis dalam <span id="countdown" class="fw-bold">5</span> detik...</small>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="scan.php" class="btn btn-primary btn-lg px-4 gap-3">Scan Sekarang</a>
                <a href="riwayat.php" class="btn btn-outline-secondary btn-lg px-4">Lihat Riwayat</a>
            </div>

        </div>
    </div>
</div>

<script>
    // Waktu hitung mundur dalam detik
    let timeLeft = 5;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(() => {
        timeLeft--;
        countdownEl.innerText = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            window.location.href = 'scan.php';
        }
    }, 1000);
</script>

</body>
</html>