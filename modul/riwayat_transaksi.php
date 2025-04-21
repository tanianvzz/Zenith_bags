<?php
session_start();
include "../lib/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$sql = "SELECT t.*, p.nama_barang, p.foto_produk, p.harga, p.diskon, p.harga_setelah_diskon, t.id_user
        FROM transaksi t 
        JOIN histori_diskon p ON t.id_produk = p.id 
        ORDER BY t.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi | Zenith Bags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #561C24;
            --secondary-color: #EFECEC;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1000px;
            margin-top: 60px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }

        .btn-back {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-back:hover {
            background-color: #3e151b;
        }

        .card-body {
            padding: 20px;
        }

        .img-fluid {
            max-height: 130px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="mb-4">Riwayat Transaksi</h3>

        <?php if (count($transaksi) === 0): ?>
            <div class="alert alert-warning text-center">
                Belum ada transaksi yang dilakukan.
            </div>
        <?php else: ?>
            <?php foreach ($transaksi as $t): ?>
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-3 text-center">
                            <img src="../uploads/<?= htmlspecialchars($t['foto_produk']); ?>" class="img-fluid rounded" alt="Produk">
                        </div>
                        <div class="col-md-9">
                            <h5><?= htmlspecialchars($t['nama_barang']); ?></h5>
                            <p><strong>ID Pemesan:</strong> <?= htmlspecialchars($t['id_user']); ?></p> <!-- Menampilkan ID pemesan -->
                            <p><strong>Jumlah:</strong> <?= $t['jumlah']; ?></p>
                            <p><strong>Total:</strong> Rp. <?= number_format($t['total_harga'], 0, ',', '.'); ?></p>
                            <p><strong>Alamat Pengiriman:</strong> <?= htmlspecialchars($t['alamat']); ?></p>
                            <p><strong>No Telp:</strong> <?= htmlspecialchars($t['no_telp']); ?></p>
                            <p><strong>Metode Pembayaran:</strong> <?= strtoupper($t['metode_pembayaran']); ?></p>
                            <p><strong>Tanggal Transaksi:</strong> <?= date('d-m-Y H:i', strtotime($t['tanggal_transaksi'])); ?></p>
                            <p><strong>Harga Awal:</strong> Rp. <?= number_format($t['harga'], 0, ',', '.'); ?></p>
                            <?php if ($t['diskon'] > 0): ?>
                                <p><strong>Diskon:</strong> <?= $t['diskon']; ?>%</p>
                                <p><strong>Harga Setelah Diskon:</strong> Rp. <?= number_format($t['harga_setelah_diskon'], 0, ',', '.'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-back mt-4">⬅ Kembali ke Beranda</a>
    </div>
</body>
</html>
