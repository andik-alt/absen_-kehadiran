<?php
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama_event'] ?? "");
    $tanggal = $_POST['tanggal'] ?? "";
    $lokasi = trim($_POST['lokasi'] ?? "");
    $deskripsi = trim($_POST['deskripsi'] ?? "");

    if ($nama === "" || $tanggal === "") {
        $error = "Nama acara dan tanggal wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO events (nama_event, tanggal, lokasi, deskripsi)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $nama,
            $tanggal,
            $lokasi,
            $deskripsi
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: peserta.php");
            exit;
        } else {
            $error = "Gagal menyimpan acara.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
    <title>Tambah Acara - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width:600px">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4">Tambah Acara</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Acara</label>
                    <input type="text" name="nama_event"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Acara</label>
                    <input type="date" name="tanggal"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi"
                              class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Acara
                </button>

                <a href="peserta.php" class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>