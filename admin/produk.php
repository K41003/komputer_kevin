<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: produk.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h3 class="mb-4">Kelola Produk</h3>
  <a href="tambah_produk.php" class="btn btn-primary mb-3">+ Tambah Produk</a>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $result = $conn->query("SELECT * FROM products");
    $no = 1;
    while ($row = $result->fetch_assoc()):
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_produk'] ?></td>
        <td>Rp<?= number_format($row['harga']) ?></td>
        <td><?= $row['stok'] ?></td>
        <td>
          <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="produk.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
    <a href="../admin/index.php" class="btn btn-secondary">
  ← Kembali ke Riwayat
</body>
</html>
