<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Penjualan Komputer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
      <h4 class="mb-4 text-center">Login Sistem Penjualan</h4>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form action="includes/auth.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>

      <!-- Tombol daftar akun -->
      <div class="text-center mt-3">
        <a href="register.php" class="btn btn-outline-secondary w-100">Buat Akun</a>
      </div>
    </div>
  </div>

</body>
</html>