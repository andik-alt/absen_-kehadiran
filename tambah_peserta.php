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

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$error = "";
$events = mysqli_query($conn, "SELECT id, nama_event, tanggal FROM events ORDER BY tanggal DESC");
$ada_event = mysqli_num_rows($events) > 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_id = (int)($_POST['event_id'] ?? 0);
    $nama     = trim($_POST['nama'] ?? "");
    $pangkat  = trim($_POST['pangkat'] ?? "");
    $jabatan  = trim($_POST['jabatan'] ?? "");

    if ($event_id <= 0 || $nama === "") {
        $error = "Acara dan nama peserta wajib diisi.";
    } else {
        // Diperbaiki: Menambahkan qr_token ke dalam daftar kolom dan 5 tanda tanya (?)
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO peserta (event_id, nama, pangkat, jabatan, qr_token)
             VALUES (?, ?, ?, ?, ?)"
        );

        $berhasil = false;
        // Ulangi jika token kebetulan bentrok (sangat jarang)
        for ($i = 0; $i < 3 && !$berhasil; $i++) {
            $token = bin2hex(random_bytes(16));
            // Bind 1 integer (i) dan 4 string (ssss) -> total "issss"
            mysqli_stmt_bind_param($stmt, "issss", $event_id, $nama, $pangkat, $jabatan, $token);
            try {
                $berhasil = mysqli_stmt_execute($stmt);
            } catch (mysqli_sql_exception $ex) {
                if ($ex->getCode() !== 1062) {
                    break;
                }
            }
        }

        if ($berhasil) {
            // Set pesan sukses (opsional jika menggunakan flash message)
            $_SESSION['flash_message'] = "Peserta berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            header("Location: peserta.php");
            exit;
        }
        $error = "Gagal menyimpan peserta.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
    <title>Tambah Peserta - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width:600px">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4">Tambah Peserta</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php if (!$ada_event): ?>
                <div class="alert alert-warning">
                    Belum ada acara. <a href="tambah_event.php">Buat acara dulu</a>.
                </div>
            <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Acara</label>
                    <select name="event_id" class="form-select" required>
                        <option value="">-- Pilih acara --</option>
                        <?php while ($ev = mysqli_fetch_assoc($events)): ?>
                            <option value="<?= (int)$ev['id'] ?>"
                                <?= ((int)($_POST['event_id'] ?? 0) === (int)$ev['id']) ? 'selected' : '' ?>>
                                <?= e($ev['nama_event']) ?> (<?= e($ev['tanggal']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required
                           value="<?= e($_POST['nama'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Pangkat</label>
                    <input type="text" name="pangkat" class="form-control"
                           value="<?= e($_POST['pangkat'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control"
                           value="<?= e($_POST['jabatan'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Peserta</button>
                <a href="peserta.php" class="btn btn-secondary">Kembali</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>