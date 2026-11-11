<?php
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: peserta.php");
    exit;
}

// Ambil data peserta
$stmt = mysqli_prepare($conn, "SELECT * FROM peserta WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$peserta = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$peserta) {
    header("Location: peserta.php");
    exit;
}

$error = "";
$events = mysqli_query($conn, "SELECT id, nama_event, tanggal FROM events ORDER BY tanggal DESC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_id = (int)($_POST['event_id'] ?? 0);
    $nama     = trim($_POST['nama'] ?? "");
    $pangkat = trim($_POST['pangkat'] ?? "");
    $jabatan = trim($_POST['jabatan'] ?? "");

    if ($event_id <= 0 || $nama === "") {
        $error = "Acara dan nama peserta wajib diisi.";
    } else {
        $upd = mysqli_prepare(
            $conn,
            "UPDATE peserta
             SET event_id = ?, nama = ?, pangkat = ?, jabatan = ?
             WHERE id = ?"
        );
        
        // Diperbaiki dari "issssi" menjadi "isssi" karena hanya ada 5 parameter
        mysqli_stmt_bind_param($upd, "isssi", $event_id, $nama, $pangkat, $jabatan, $id);

        if (mysqli_stmt_execute($upd)) {
            header("Location: peserta.php");
            exit;
        }
        $error = "Gagal menyimpan perubahan.";
    }

    // Tampilkan kembali input terakhir jika gagal
    $peserta = array_merge($peserta, [
        'event_id' => $event_id,
        'nama'     => $nama,
        'pangkat' => $pangkat,
        'jabatan' => $jabatan,
    ]);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Peserta - EventAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width:600px">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4">Edit Peserta</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?= (int)$peserta['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Acara</label>
                    <select name="event_id" class="form-select" required>
                        <?php while ($ev = mysqli_fetch_assoc($events)): ?>
                            <option value="<?= (int)$ev['id'] ?>"
                                <?= ((int)$peserta['event_id'] === (int)$ev['id']) ? 'selected' : '' ?>>
                                <?= e($ev['nama_event']) ?> (<?= e($ev['tanggal']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required
                           value="<?= e($peserta['nama']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Pangkat</label>
                    <input type="text" name="pangkat" class="form-control"
                           value="<?= e($peserta['pangkat']) ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control"
                           value="<?= e($peserta['jabatan']) ?>">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="peserta.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>