<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: daftar_produk.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.2s ease-in-out;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .btn-add {
            background-color: #ffffff;
            color: #1e3c72;
            font-weight: bold;
            border-radius: 10px;
            transition: 0.2s;
        }
        .btn-add:hover {
            background-color: #dbeafe;
            color: #1e3c72;
        }
        
    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4 text-center fw-bold">Daftar Produk</h3>
    <div class="d-flex justify-content-between mb-3">
        <a href="keranjang.php" class="btn btn-success">🛒 Lihat Keranjang</a>
    </div>
    <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 product-card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                    <p class="card-text">Harga: Rp<?= number_format($row['harga']) ?></p>
                    <p class="card-text">Stok: <?= $row['stok'] ?></p>
                    <a href="?add=<?= $row['id'] ?>" class="btn btn-add">+ Tambah ke Keranjang</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
        <a href="../pembeli/index.php" class="btn btn-danger">← Kembali ke Riwayat</a>
</body>
</html>