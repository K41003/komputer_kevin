<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Keranjang Belanja</h3>
    <a href="daftar_produk.php" class="btn btn-secondary mb-3"><- Kembali</a>
    <form action="checkout.php" method="post">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $id => $qty):
                    $result = $conn->query("SELECT * FROM products WHERE id = '$id'");
                    $row = $result->fetch_assoc();
                    $subtotal = $row['harga'] * $qty;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= $row['nama_produk'] ?></td>
                    <td><?= $qty ?></td>
                    <td>Rp<?= number_format($row['harga']) ?></td>
                    <td>Rp<?= number_format($subtotal) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong>Rp<?= number_format($total) ?></strong></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Checkout</button>
    </form>
</div>
</body>
</html>