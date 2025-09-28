<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

$user_id = (int)$_SESSION['user']['id'];

$query = "
    SELECT o.id AS order_id, o.tanggal, o.total_harga,
           p.nama_produk, oi.quantity, oi.harga_satuan, oi.subtotal
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.tanggal DESC, o.id DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Riwayat Pembelian</h3>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <!-- <th>Total Order</th> -->
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                <td><?= (int)$row['quantity'] ?></td>
                <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                <!-- <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td> -->
                <td>
                    <a href="detail_pesanan.php?id=<?= $row['order_id'] ?>" class="btn btn-info btn-sm">
                        Lihat
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows === 0): ?>
            <tr>
                <td colspan="8" class="text-center">Belum ada pesanan</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="../pembeli/index.php" class="btn btn-secondary">← Kembali</a>
</div>
</body>
</html>
