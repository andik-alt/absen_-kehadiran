<?php
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$mode = ($_GET['mode'] ?? 'masuk') === 'pulang' ? 'pulang' : 'masuk';

$judul = $mode === 'pulang' ? 'SCAN BARCODE / QR - CHECK-OUT' : 'SCAN BARCODE / QR - CHECK-IN';
$action = $mode === 'pulang' ? 'proses_pulang.php' : 'proses_scan.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($judul) ?> - EventAttend</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    .scanner-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    }
    #reader {
        width: 100% !important;
        border: none !important;
        border-radius: 12px;
        overflow: hidden;
    }
    #reader video {
        border-radius: 12px;
        object-fit: cover;
    }
    /* Sembunyikan elemen bawaan html5-qrcode yang kurang rapi di mobile */
    #reader__dashboard_section_csr span, 
    #reader__dashboard_section_swaplink {
        display: none !important;
    }
    .manual-input-section {
        background: #f1f3f5;
        border-radius: 12px;
        padding: 15px;
        margin-top: 20px;
    }
</style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand fw-bold">
            <i class="fa-solid fa-barcode me-2"></i>EventAttend
        </a>
        <a href="dashboard.php" class="btn btn-light btn-sm fw-semibold">
            <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</nav>

<div class="container py-4" style="max-width: 500px;">
    <div class="card scanner-card p-4 bg-white">
        
        <!-- Header Judul -->
        <div class="text-center mb-3">
            <span class="badge bg-success-subtle text-success mb-2 px-3 py-2 rounded-pill fw-bold">
                <i class="fa-solid fa-camera me-1"></i> KAMERA BARCODE & QR AKTIF
            </span>
            <h3 class="fw-bold text-dark m-0"><?= htmlspecialchars($judul) ?></h3>
            <p class="text-muted small">Arahkan kamera ke Barcode atau QR Code.</p>
        </div>

        <!-- Area Kamera Utama -->
        <div class="position-relative mb-3">
            <div id="reader"></div>
        </div>

        <!-- Status & Indikator Proses -->
        <div class="alert alert-secondary text-center py-2 mb-3" role="alert" id="statusBox" style="font-size: 14px;">
            <i class="fa-solid fa-spinner fa-spin me-2" id="statusSpinner"></i>
            <span id="status">Memulai kamera...</span>
        </div>

        <!-- Form Tersembunyi untuk Eksekusi Submit -->
        <form method="POST" action="<?= htmlspecialchars($action) ?>" id="formScan">
            <input type="hidden" name="qr_token" id="qr_token">
        </form>

        <!-- Opsi Cadangan: Input Manual / Scanner Fisik -->
        <div class="text-center mt-2">
            <button class="btn btn-link text-decoration-none text-muted btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#manualInputBox">
                <i class="fa-solid fa-keyboard me-1"></i> Masukkan Token / Gunakan Scanner USB
            </button>
            
            <div class="collapse manual-input-section text-start" id="manualInputBox">
                <label for="manual_token" class="form-label small fw-bold text-secondary">Input Token Manual:</label>
                <div class="input-group">
                    <input type="text" id="manual_token" class="form-control form-control-sm" placeholder="Ketik token lalu Enter...">
                    <button class="btn btn-dark btn-sm" type="button" id="btnSubmitManual">Proses</button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Library HTML5-Qrcode & Bootstrap JS -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const statusEl = document.getElementById('status');
const statusBox = document.getElementById('statusBox');
const statusSpinner = document.getElementById('statusSpinner');
const form = document.getElementById('formScan');
const inputHidden = document.getElementById('qr_token');
const manualTokenInput = document.getElementById('manual_token');
const btnSubmitManual = document.getElementById('btnSubmitManual');

let scanner = null;
let sudahScan = false;

function updateStatus(message, type = 'secondary', showSpinner = true) {
    statusBox.className = `alert alert-${type} text-center py-2 mb-3`;
    statusEl.innerText = message;
    statusSpinner.style.display = showSpinner ? 'inline-block' : 'none';
}

// Otomatis jalankan kamera untuk Barcode & QR saat halaman terbuka
window.addEventListener('DOMContentLoaded', async () => {
    scanner = new Html5Qrcode("reader");
    
    try {
        await scanner.start(
            { facingMode: "environment" },
            { 
                fps: 15, 
                // Mengatur kotak pemindai berbentuk persegi panjang (melebar) agar cocok untuk Barcode garis maupun QR Code
                qrbox: (viewfinderWidth, viewfinderHeight) => {
                    let width = Math.floor(viewfinderWidth * 0.85);
                    let height = Math.floor(viewfinderHeight * 0.4); // Lebih tipis/melebar khas barcode
                    return { width: width, height: height };
                }
            },
            (decodedText) => {
                if (sudahScan) return;
                sudahScan = true;

                inputHidden.value = decodedText.trim();
                updateStatus('Kode berhasil terbaca! Memproses...', 'success', true);

                scanner.stop().then(() => {
                    form.submit();
                }).catch(() => {
                    form.submit();
                });
            },
            (errorMessage) => {
                // Abaikan error per-frame pencarian
            }
        );
        updateStatus('Arahkan kamera ke Barcode atau QR Code.', 'info', false);
    } catch (err) {
        updateStatus('Gagal mengakses kamera: ' + err, 'danger', false);
    }
});

// Penanganan input manual / barcode scanner fisik
btnSubmitManual.addEventListener('click', function() {
    executeManual();
});

manualTokenInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        executeManual();
    }
});

function executeManual() {
    let val = manualTokenInput.value.trim();
    if (val !== '') {
        if (scanner) {
            scanner.stop().catch(() => {});
        }
        sudahScan = true;
        inputHidden.value = val;
        updateStatus('Memproses token manual...', 'success', true);
        form.submit();
    }
}
</script>
</body>
</html>