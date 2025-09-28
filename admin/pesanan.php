<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Pesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .main-container {
        padding: 40px 0;
    }
    
    .header-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    
    .title {
        color: #4a5568;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
        text-align: center;
    }
    
    .table-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .table {
        margin: 0;
        border: none;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
        padding: 20px 15px;
        font-size: 0.95rem;
    }
    
    .table tbody td {
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 15px;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .price-text {
        font-weight: 600;
        color: #2d3748;
    }
    
    .total-price {
        font-weight: 700;
        color: #38a169;
        font-size: 1.1rem;
    }
    
    .btn-back {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        border: none;
        border-radius: 25px;
        padding: 12px 30px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(65, 153, 225, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(65, 153, 225, 0.4);
        color: white;
    }
    
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #718096;
        font-size: 1.2rem;
    }
    
    .no-data i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
  </style>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="main-container">
  <div class="container">
    <!-- Header -->
    <div class="card header-card">
      <div class="card-body py-4">
        <h1 class="title">
          <i class="fas fa-history me-3"></i>
          Riwayat Pesanan
        </h1>
      </div>
    </div>
    
    <!-- Table -->
    <div class="card table-card">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th><i class="fas fa-hashtag me-2"></i>No</th>
              <th><i class="fas fa-user me-2"></i>User</th>
              <th><i class="fas fa-calendar me-2"></i>Tanggal</th>
              <th><i class="fas fa-box me-2"></i>Produk</th>
              <th><i class="fas fa-sort-numeric-up me-2"></i>Jumlah</th>
              <th><i class="fas fa-tag me-2"></i>Harga Satuan</th>
              <th><i class="fas fa-calculator me-2"></i>Subtotal</th>
              <th><i class="fas fa-money-bill-wave me-2"></i>Total Pesanan</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $result = $conn->query("
              SELECT o.id AS order_id, o.tanggal, o.total_harga,
                     u.username,
                     p.nama_produk, oi.quantity, oi.harga_satuan, oi.subtotal
              FROM orders o
              JOIN users u ON o.user_id = u.id
              JOIN order_items oi ON o.id = oi.order_id
              JOIN products p ON oi.product_id = p.id
              ORDER BY o.tanggal DESC, o.id DESC
          ");
          
          if ($result && $result->num_rows > 0):
              $no = 1;
              while ($row = $result->fetch_assoc()):
          ?>
              <tr>
                  <td><strong><?= $no++ ?></strong></td>
                  <td>
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    <?= htmlspecialchars($row['username']) ?>
                  </td>
                  <td>
                    <i class="fas fa-clock me-2 text-muted"></i>
                    <?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?>
                  </td>
                  <td>
                    <i class="fas fa-cube me-2 text-info"></i>
                    <?= htmlspecialchars($row['nama_produk']) ?>
                  </td>
                  <td>
                    <span class="badge bg-primary rounded-pill">
                      <?= (int)$row['quantity'] ?>
                    </span>
                  </td>
                  <td class="price-text">
                    Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?>
                  </td>
                  <td class="price-text">
                    Rp <?= number_format($row['subtotal'], 0, ',', '.') ?>
                  </td>
                  <td class="total-price">
                    <i class="fas fa-coins me-1"></i>
                    Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                  </td>
              </tr>
          <?php 
              endwhile;
          else:
          ?>
              <tr>
                <td colspan="8" class="no-data">
                  <i class="fas fa-inbox"></i><br>
                  <strong>Belum ada pesanan</strong><br>
                  <small>Tidak ada riwayat pesanan yang tersedia</small>
                </td>
              </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Back Button -->
    <div class="text-center mt-4">
      <a href="../pembeli/index.php" class="btn-back">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali ke Dashboard
      </a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>