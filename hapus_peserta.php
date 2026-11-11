<?php
require_once "config.php";

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek autentikasi dan hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Validasi metode request harus POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Metode tidak diizinkan.");
}

// Ambil dan validasi ID agar benar-benar integer positif
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id && $id > 0) {
    $query = "DELETE FROM peserta WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Berhasil dihapus, set pesan sukses
            $_SESSION['flash_message'] = "Data peserta berhasil dihapus.";
            $_SESSION['flash_type'] = "success";
        } else {
            // Gagal eksekusi query
            $_SESSION['flash_message'] = "Gagal menghapus data: " . mysqli_error($conn);
            $_SESSION['flash_type'] = "danger";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['flash_message'] = "Terjadi kesalahan pada sistem.";
        $_SESSION['flash_type'] = "danger";
    }
} else {
    $_SESSION['flash_message'] = "ID peserta tidak valid.";
    $_SESSION['flash_type'] = "danger";
}

// Redirect kembali ke halaman daftar peserta
header("Location: peserta.php");
exit;