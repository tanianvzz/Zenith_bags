<?php
include "../lib/koneksi.php";
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, nama_barang, harga, diskon, harga_setelah_diskon, stok, foto_produk FROM histori_diskon ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$produk_list = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zenith Bags - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #561C24;
            padding-top: 20px;
            position: fixed;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h5 {
            font-size: 1.5rem;
            font-weight: bold;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin: 0 20px 20px;
        }

        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .sidebar a i {
            margin-right: 10px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 25px;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            width: 100%;
        }
        .content h3{
            color: #561C24;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .card img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color:rgb(81, 27, 34);
        }

        .card .price {
            font-size: 16px;
            color: #888;
        }

        .card .text-danger {
            font-size: 18px;
            font-weight: bold;
            color: #561C24 !important;
        }

        .btn-danger {
            background-color: #561C24;
            border: none;
        }

        .btn-danger:hover {
            background-color:#561C24;
        }
    </style>
</head>

<body>
<div class="sidebar d-flex flex-column justify-content-between">
    <div>
        <h5 class="text-center text-light">Admin Zenith Bags</h5>
        <a href="data_diskon.php"><i class="fas fa-tags"></i> Data Diskon</a>
        <a href="data_user.php"><i class="fas fa-tags"></i> Data User</a>
        <a href="tambah_produk.php"><i class="fas fa-plus"></i> Tambah Produk Diskon</a>
        <a href="riwayat_transaksi.php"><i class="fas fa-plus"></i>Riwayat Transaksi </a>
    </div>
    <div>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>


    <div class="content">
        <h3 class="text-center my-4 fw-bold">Katalog Produk</h3>
        <div class="row">
            <?php if (!empty($produk_list)): ?>
                <?php foreach ($produk_list as $produk): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="../uploads/<?= htmlspecialchars($produk['foto_produk']); ?>" class="card-img-top" alt="Produk">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($produk['nama_barang']); ?></h5>
                                <?php if ($produk['diskon'] > 0): ?>
                            <span class="badge-diskon mt-2"><?= htmlspecialchars($produk['diskon']); ?>% OFF</span>
                            <s class="price">Rp. <?= number_format($produk['harga'], 0, ',', '.'); ?></s>
                            <?php endif; ?>
                                <strong class="price text-danger">Rp. <?= number_format($produk['harga_setelah_diskon'], 0, ',', '.'); ?></strong>
                                <a href="../modul/detail.php?id=<?= $produk['id']; ?>" class="btn btn-danger w-100 mt-3">Detail Produk</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Tidak ada produk tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
