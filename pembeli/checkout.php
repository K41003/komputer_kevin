<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Laporkan error mysqli sebagai exception supaya bisa di-handle rapi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$startedTransaction = false;
try {
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Keranjang kosong!'); window.location='daftar_produk.php';</script>";
        exit();
    }

    $user_id = (int)$_SESSION['user']['id'];
    $tanggal = date("Y-m-d H:i:s");

    // Mulai transaksi agar pengecekan stok + update stok + insert order atomic
    $conn->begin_transaction();
    $startedTransaction = true;

    // 1) Validasi produk & hitung total, gunakan SELECT ... FOR UPDATE untuk mengunci baris stok
    $total_harga = 0.0;
    $stmt_select = $conn->prepare("SELECT harga, stok FROM products WHERE id = ? FOR UPDATE");
    foreach ($_SESSION['cart'] as $product_id => $jumlah) {
        $product_id = (int)$product_id;
        $jumlah = (int)$jumlah;

        $stmt_select->bind_param("i", $product_id);
        $stmt_select->execute();
        $res = $stmt_select->get_result();
        $produk = $res->fetch_assoc();
        $res->free();

        if (!$produk) {
            throw new Exception("Produk ID $product_id tidak ditemukan.");
        }
        if ((int)$produk['stok'] < $jumlah) {
            throw new Exception("Stok tidak cukup untuk produk ID $product_id.");
        }

        $total_harga += (float)$produk['harga'] * $jumlah;
    }
    $stmt_select->close();

    // 2) Simpan ke tabel orders
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, tanggal, total_harga) VALUES (?, ?, ?)");
    $stmt_order->bind_param("isd", $user_id, $tanggal, $total_harga);
    $stmt_order->execute();
    $order_id = (int)$conn->insert_id; // gunakan $conn->insert_id, bukan $stmt->insert_id
    $stmt_order->close();

    // 3) Siapkan statement untuk menyimpan order_items dan update stok
    $stmt_detail = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt_update_stok = $conn->prepare("UPDATE products SET stok = stok - ? WHERE id = ?");
    $stmt_price = $conn->prepare("SELECT harga FROM products WHERE id = ?");

    // Masukkan setiap item dan kurangi stok
    foreach ($_SESSION['cart'] as $product_id => $jumlah) {
        $product_id = (int)$product_id;
        $jumlah = (int)$jumlah;

        // Ambil harga lagi (telah terkunci karena FOR UPDATE sebelumnya)
        $stmt_price->bind_param("i", $product_id);
        $stmt_price->execute();
        $res = $stmt_price->get_result();
        $prod = $res->fetch_assoc();
        $res->free();
        if (!$prod) throw new Exception("Produk ID $product_id tidak ditemukan saat menyimpan detail.");

        $harga = (float)$prod['harga'];
        $subtotal = $harga * $jumlah;

        // Masukkan detail item (perhatikan urutan dan tipe pada bind_param)
        $stmt_detail->bind_param("iiidd", $order_id, $product_id, $jumlah, $harga, $subtotal);
        $stmt_detail->execute();

        // Kurangi stok
        $stmt_update_stok->bind_param("ii", $jumlah, $product_id);
        $stmt_update_stok->execute();
    }

    // Tutup statement
    $stmt_price->close();
    $stmt_detail->close();
    $stmt_update_stok->close();

    // Commit transaksi
    $conn->commit();

    // Kosongkan keranjang dan redirect ke riwayat
    unset($_SESSION['cart']);
    header("Location: riwayat_pembelian.php");
    exit();

} catch (Exception $e) {
    // rollback jika transaksi sudah dimulai
    if ($startedTransaction) {
        $conn->rollback();
    }
    $msg = $e->getMessage();
    // tampilkan alert user dan kembalikan ke keranjang
    echo "<script>alert('Terjadi kesalahan: " . addslashes($msg) . "'); window.location='keranjang.php';</script>";
    exit();
}
