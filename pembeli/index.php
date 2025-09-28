<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pembeli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
        <a class="navbar-brand" href="#">💻 Penjualan Komputer</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">👤 <?= $_SESSION['user']['username'] ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100 bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">🛒 Belanja Produk</h5>
                    <p class="card-text">Lihat dan beli produk komputer yang tersedia.</p>
                    <a href="daftar_produk.php" class="btn btn-primary">Lihat Produk</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100 bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">📄 Riwayat Pembelian</h5>
                    <p class="card-text">Lihat riwayat transaksi dan pesanan Anda.</p>
                    <a href="riwayat_pembelian.php" class="btn btn-success">Lihat Riwayat</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>