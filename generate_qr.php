<?php
require_once "config.php";

// Pastikan session sudah ada atau sesuaikan dengan validasi akses Anda
// if (!isset($_SESSION['user_id'])) { ... }

// Ambil data peserta / acara berdasarkan ID (Contoh logika)
// $id = $_GET['id'] ?? 0;
// ... query database Anda di sini ...
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kartu Akses Peserta - EventAttend</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    /* Berikan ID khusus pada area kartu yang ingin dijadikan gambar */
    .card-akses {
        max-width: 450px;
        margin: 0 auto;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        background: #ffffff;
    }
</style>
</head>
<body class="py-5">

<div class="container">
    <!-- Area yang akan ditangkap / di-download oleh html2canvas -->
    <div id="captureArea" class="card card-akses p-4 text-center mb-3">
        <h4 class="fw-bold mb-1">EventAttend</h4>
        <p class="text-muted small mb-4">Kartu Akses Peserta</p>
        <hr class="text-muted opacity-25">

        <h5 class="text-primary fw-bold mb-2">andik</h5>
        <p class="text-muted small px-2 mb-4">
            Acara: RAPAT ANEV PROGRAM DAN ANGGARAN POLRES SINGKAWANG T.A.2026
        </p>

        <!-- Bagian QR Code -->
        <div class="mb-3">
            <!-- Ganti src dengan path QR Code dinamis PHP Anda -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=f765031dd3701ff8ea86bf04ef27d848" alt="QR Code" class="img-fluid rounded" style="width: 180px; height: 180px;">
        </div>

        <div class="text-muted" style="font-size: 12px;">
            Token Akses / Kode Manual:<br>
            <strong class="text-dark">f765031dd3701ff8ea86bf04ef27d848</strong>
        </div>
        
        <p class="text-muted small mt-3 mb-0" style="font-size: 11px;">
            Tunjukkan QR Code ini kepada panitia saat check-in.
        </p>
    </div>

    <!-- Tombol-tombol Aksi (berada di luar area capture agar tombol tidak ikut tercetak di gambar) -->
    <div class="text-center" style="max-width: 450px; margin: 0 auto;">
        <div class="d-grid gap-2">
            <!-- Tombol Simpan QR Code -->
            <button type="button" id="btnSimpan" class="btn btn-success fw-semibold">
                <i class="fa-solid fa-download me-2"></i> Simpan QR Code (Gambar)
            </button>
            
            <!-- Tombol Cetak Kartu yang sudah ada -->
            <button type="button" onclick="window.print()" class="btn btn-outline-primary fw-semibold">
                <i class="fa-solid fa-print me-2"></i> Cetak Kartu
            </button>

            <a href="data_peserta.php" class="btn btn-dark fw-semibold">
                Kembali ke Data Peserta
            </a>
        </div>
    </div>
</div>

<!-- Library html2canvas CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.getElementById('btnSimpan').addEventListener('click', function() {
    const btn = this;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Menyimpan...';
    btn.disabled = true;

    // Ambil elemen card berdasarkan ID
    const targetElement = document.getElementById('captureArea');

    html2canvas(targetElement, {
        scale: 2, // Meningkatkan resolusi gambar agar tidak pecah/tajam
        useCORS: true
    }).then(canvas => {
        // Ubah canvas menjadi URL data gambar (PNG)
        const imageURL = canvas.toDataURL('image/png');

        // Buat elemen link virtual untuk mendownload file
        const downloadLink = document.createElement('a');
        downloadLink.href = imageURL;
        downloadLink.download = 'Kartu-Akses-andik.png'; // Nama file unduhan
        
        // Picu klik otomatis
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);

        // Kembalikan tombol ke semula
        btn.innerHTML = '<i class="fa-solid fa-download me-2"></i> Simpan QR Code (Gambar)';
        btn.disabled = false;
    }).catch(err => {
        alert('Gagal menyimpan QR Code: ' + err);
        btn.innerHTML = '<i class="fa-solid fa-download me-2"></i> Simpan QR Code (Gambar)';
        btn.disabled = false;
    });
});
</script>
</body>
</html>