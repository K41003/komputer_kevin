<?php
session_start();

// Cek login dan role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
        <a class="navbar-brand" href="#">Kalcer Teknologi</a>
        <div class="d-flex">
            <span class="text-white me-3 mt-1">
                Halo, <strong><?php echo $_SESSION['user']['username']; ?></strong>
            </span>
            <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container mt-5 ">
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow text-center bg-success text-white">
                <div class="card-body">
                    <i class="bi bi-box-seam display-4 text-primary"></i>
                    <h5 class="card-title mt-3">Produk</h5>
                    <p class="card-text">Kelola data produk komputer</p>
                    <a href="produk.php" class="btn btn-outline-primary btn-sm">Lihat Produk</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow text-center bg-primary text-white">
                <div class="card-body">
                    <i class="bi bi-cart-check display-4 text-success"></i>
                    <h5 class="card-title mt-3">Pesanan</h5>
                    <p class="card-text">Lihat daftar transaksi pembeli</p>
                    <a href="pesanan.php" class="btn btn-outline-success btn-sm">Lihat Pesanan</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="bi bi-person-gear display-4 text-warning"></i>
                    <h5 class="card-title mt-3">Akun Admin</h5>
                    <p class="card-text">Kelola akun admin atau informasi</p>
                    <a href="#" class="btn btn-outline-warning btn-sm">Kelola Akun</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer class="bg-secondary text-center py-3 mt-5 text-white" >
    <small>© <?php echo date('Y'); ?> Sistem Penjualan Komputer - Admin Panel</small>
</footer>

</body>
</html>