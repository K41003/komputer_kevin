<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: produk.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $stmt = $conn->prepare("UPDATE products SET nama_produk = ?, harga = ?, stok = ? WHERE id = ?");
    $stmt->bind_param("siii", $nama_produk, $harga, $stok, $id);
    if ($stmt->execute()) {
        header("Location: produk.php");
        exit();
    } else {
        $error = "Gagal mengupdate produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Edit Produk</h3>
  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="nama_produk" class="form-label">Nama Produk</label>
      <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($product['nama_produk']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="harga" class="form-label">Harga</label>
      <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($product['harga']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="stok" class="form-label">Stok</label>
      <input type="number" class="form-control" id="stok" name="stok" value="<?= htmlspecialchars($product['stok']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Produk</button>
    <a href="produk.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
