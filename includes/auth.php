<?php
session_start();
include 'db.php'; // sudah benar karena auth.php berada di includes/

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Cek user dan password (sementara tanpa hash)
if ($user && $password === $user['password']) {
    $_SESSION['user'] = $user;

    if ($user['role'] == 'admin') {
        header("Location: ../admin/index.php");
        exit;
    } elseif ($user['role'] == 'manager') {
        header("Location: ../manager/index.php");
        exit;
    } else {
        header("Location: ../pembeli/index.php");
        exit;
    }

} else {
    $_SESSION['error'] = "Username atau password salah.";
    header("Location: ../login.php"); // login.php di root
    exit;
}
?>