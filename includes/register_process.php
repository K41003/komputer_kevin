<?php
session_start();
include 'db.php'; // sudah benar karena register_process.php berada di includes/

$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Password dan konfirmasi password tidak cocok.";
    header("Location: ../register.php");
    exit;
}

// Cek apakah username sudah ada
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = "Username sudah digunakan.";
    header("Location: ../register.php");
    exit;
}

// Set role otomatis menjadi 'pembeli'
$role = 'pembeli';

// Insert user baru dengan role pembeli
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);
if ($stmt->execute()) {
    $_SESSION['success'] = "Akun berhasil dibuat. Silakan login.";
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION['error'] = "Terjadi kesalahan saat membuat akun.";
    header("Location: ../register.php");
    exit;
}
?>
