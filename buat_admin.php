<?php
require_once "config.php";

$nama = "Administrator";
$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

$cek = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
mysqli_stmt_bind_param($cek, "s", $username);
mysqli_stmt_execute($cek);
$result = mysqli_stmt_get_result($cek);

if (mysqli_num_rows($result) > 0) {
    die("Akun admin sudah tersedia.");
}

// Simpan akun
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users (nama, username, password, role)
     VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $nama,
    $username,
    $password,
    $role
);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin berhasil dibuat.<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "Segera hapus file buat_admin.php setelah berhasil.";
} else {
    echo "Gagal membuat admin.";
}
?>